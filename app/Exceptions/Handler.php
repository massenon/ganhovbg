<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Auth;
use Exception;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
           //
        });
    }
    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Exception && $exception->getMessage() === 'processing data') {
            return redirect()->route('admin.testinginfo');
        }
        if ($exception instanceof \Exception && $exception->getMessage() === 'error response') {
            return new Response('');
        }
        if ($this->isDatabaseSaturated($exception)) {
            // Base saturee (limite de connexions de l'hebergeur) : page claire, sans acces a la base, et nouvel essai conseille.
            if ($request->expectsJson()) {
                return response()->json(['error' => 'service_busy', 'message' => 'Le service est tres sollicite. Reessayez dans quelques secondes.'], 503, ['Retry-After' => '15']);
            }
            return response()->view('errors.gn-min', [
                'gcCode' => '503',
                'gcPhoto' => '18',
                'gcHeading' => 'Le site est très sollicité',
                'gcText' => 'Beaucoup de personnes se connectent en même temps. Patientez quelques secondes puis réessayez : rien n’est perdu.',
                'gcActions' => [['Réessayer', $request->fullUrl(), 'gn-btn--light']],
            ], 503, ['Retry-After' => '15', 'Cache-Control' => 'no-store']);
        }
        return parent::render($request, $exception);
    }

    /** Erreurs de connexion MySQL liees a la saturation de l'hebergeur (1040, 1203) ou a une coupure (2002, 2006, 2013). */
    protected function isDatabaseSaturated(Throwable $e)
    {
        for ($x = $e; $x; $x = $x->getPrevious()) {
            if ($x instanceof \PDOException || $x instanceof \Illuminate\Database\QueryException) {
                $code = (int) ($x instanceof \Illuminate\Database\QueryException ? ($x->errorInfo[1] ?? $x->getCode()) : $x->getCode());
                if (in_array($code, [1040, 1203, 1226, 2002, 2006, 2013], true)) {
                    return true;
                }
            }
        }
        return false;
    }


    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson())
        {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        if ($request->is('espace-client') || $request->is('espace-client/*'))
        {
            return redirect()->guest('/espace-client/connexion');
        }
        if ($request->is('administration') || $request->is('administration/*'))
        {
            return redirect()->guest('/administration/connexion');
        }

        return redirect()->guest(route('login'));
    }
}
