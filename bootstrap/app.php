<?php

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
     )

    ->withMiddleware(function (Middleware $middleware): void {
	    
	$middleware->web(append: [
          \App\Http\Middleware\SetLocale::class,
    ]);

	$middleware->alias([
        'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
	]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        // Prevent vulnerability enumeration via response differentiation.
        // On vulnerability routes an authenticated user can hit three outcomes
        // that would otherwise be distinguishable:
        //   - non-existent id  -> 404 "No query results for model ... {id}"
        //   - role-denied      -> 403 "User does not have the right roles."
        //   - policy-denied    -> 403 "This action is unauthorized."
        // Both the status code AND the message leak whether a real record exists.
        // Collapse all of them into one identical 403 so responses are
        // byte-for-byte indistinguishable (status, message, and rendering).
        $uniformForbidden = 'This action is unauthorized.';

        $exceptions->render(function (Throwable $e, Request $request) use ($uniformForbidden) {
            if (! ($request->user() && $request->routeIs('vulnerabilities.*'))) {
                return null; // normal handling for every other route
            }

            $isForbidden = $e instanceof HttpExceptionInterface && $e->getStatusCode() === 403;
            $isMissing   = $e instanceof NotFoundHttpException; // would-be 404 from binding

            if (! $isForbidden && ! $isMissing) {
                return null; // leave 419/500/etc. untouched
            }

            // Sentinel: our normalized exception has come back around on a
            // re-entrant pass — let the framework render it like a normal 403.
            // This is what stops the re-render below from recursing.
            if ($e instanceof AccessDeniedHttpException && $e->getMessage() === $uniformForbidden) {
                return null;
            }

            // abort() here would escape the kernel as a 500, so re-render through
            // the handler with the uniform exception instead.
            return app(ExceptionHandler::class)->render(
                $request,
                new AccessDeniedHttpException($uniformForbidden),
            );
        });
    })->create();
