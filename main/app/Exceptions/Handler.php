<?php

namespace App\Exceptions;

use Throwable;
use Inertia\Inertia;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Request;
use App\Modules\SuperAdmin\Models\ErrLog;
use Illuminate\Auth\AuthenticationException;
use App\Modules\SuperAdmin\Models\SuperAdmin;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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
    'password',
    'password_confirmation',
  ];

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
    // dd($exception);
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
    $response = parent::render($request, $exception);

    if (($request->ajax() || $request->expectsJson()) && !$request->header('X-Inertia')) {
      if ($exception instanceof NotFoundHttpException) {
        $this->log404($request);
        return response()->json(['message' => 'No such endpoint'], 404);
      } elseif ($exception instanceof ModelNotFoundException) {
        if (getenv('APP_ENV') === 'local') {
          return response()->json(['Error' => $exception->getMessage()], 404);
        }
        return response()->json(['message' => 'Item not found'], 404);
      } elseif ($exception instanceof MethodNotAllowedHttpException) {
        return response()->json(['message' => 'Invalid request'], 405);
      } elseif ($exception instanceof QueryException) {
        if (getenv('APP_ENV') === 'local') {
          return response()->json(['Error' => $exception->getMessage()], 500);
        }
        return response()->json(['message' => 'Error while trying to handle request'], 500);
      } elseif ($exception instanceof ValidationException) {
        return $response;
      } else {

        /**
         * ! Report it if no other reporter
         */
        ErrLog::notifyAdminAndFail(SuperAdmin::first(), $exception, $exception->getMessage());

        if (getenv('APP_ENV') === 'local') {
          return response()->json(['Handler Error' => $exception->getMessage()], 500);
        }
        return response()->json(['message' => 'An error occured'], 500);
      }
    }
    // elseif (
    //   // (App::environment('production')) &&
    //   // $request->header('X-Inertia') &&
    //   in_array($response->status(), [500, 503, 404, 403, 405])
    // ) {

    //   if ($this->is404($exception)) {
    //     $this->log404($request);
    //   }

    //   try {
    //     Inertia::setRootView('publicpages::app');
    //     return Inertia::render('PublicPages,DisplayError', ['status' => $response->status()])
    //       ->toResponse($request)
    //       ->setStatusCode($response->status());
    //   } catch (\Throwable $th) {
    //     if (getenv('APP_ENV') === 'local') {
    //       return dd('Hanfler err', $th);
    //     }
    //   }
    // }
    elseif (in_array($response->status(), [419])) {
      return back()->withFlash(['error'=>'Your session has expired. Please try again']);
    }

    return $response;
  }

  /**
   * Convert an authentication exception into a response.
   *
   * ! This is important to vhange the default redirect named route from route('login') and avoid route('login') not defined
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Illuminate\Auth\AuthenticationException  $exception
   * @return \Symfony\Component\HttpFoundation\Response
   */
  protected function unauthenticated($request, AuthenticationException $exception)
  {
    return $request->expectsJson()
      ? response()->json(['message' => $exception->getMessage()], 401)
      : ($request->header('X-Inertia') ? Inertia::location(route('auth.login')) : redirect()->guest($exception->redirectTo() ?? route('auth.login')));
  }



  /**
   * Get the default context variables for logging.
   *
   * @return array
   */
  protected function context()
  {
    try {
      $context = array_filter([
        'url' => Request::fullUrl(),
        'input' => Request::except(['password', 'password_confirmation'])
      ]);
    } catch (Throwable $e) {
      $context = [];
    }

    return array_merge($context, parent::context());
  }

  private function is404($exception)
  {
    return $exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException
      || $exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
  }

  private function log404($request)
  {
    $error = [
      'url'    => Request::fullUrl(),
      'method' => $request->method(),
      'data'   => Request::except(['password', 'password_confirmation']),
    ];

    $message = '404: ' . $error['url'];

    // Log::debug($message, $error);
  }
}
