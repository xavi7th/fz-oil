<?php

namespace App\Modules\FzStaff\Http\Controllers\Auth;

use App\User;
use Inertia\Inertia;
use Tymon\JWTAuth\JWTGuard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Auth\SessionGuard;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\ValidationException;
use App\Modules\SuperAdmin\Events\UserLoggedIn;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
  use AuthenticatesUsers;

  protected $redirectTo = RouteServiceProvider::HOME;
  private $apiToken;
  private $authSuccess = false;
  private $authGuard;


  public function __construct()
  {
    $this->middleware('guest:' . collect(config('auth.guards'))->keys()->implode(','))->except('logout');
  }

  static function routes()
  {
    Route::name('auth.')->group(function () {
      Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
      Route::post('login', [LoginController::class, 'login']);
      Route::post('initialize-login-account', [LoginController::class, 'adminSetNewPassword'])->name('password');
      Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    });
  }

  public function showLoginForm(Request $request)
  {
    return Inertia::render('FzStaff::Auth/Login')->withViewData([
      'isAuth' => true
    ]);
  }

  public function login(Request $request)
  {
    $this->validateLogin($request);

    if (
      method_exists($this, 'hasTooManyLoginAttempts') &&
      $this->hasTooManyLoginAttempts($request)
    ) {
      $this->fireLockoutEvent($request);

      return $this->sendLockoutResponse($request);
    }

    if ($this->attemptLogin($request)) {
      return $this->sendLoginResponse($request);
    }

    $this->incrementLoginAttempts($request);

    return $this->sendFailedLoginResponse($request);
  }

  public function adminSetNewPassword()
  {
    if (!request('password')) throw ValidationException::withMessages(['err' => "A new password is required for your account."])->status(Response::HTTP_UNPROCESSABLE_ENTITY);

    $user = User::findUserByEmail(request('email')) ?? back()->withFlash(['error'=>'Not Found']);

    if ($user && !$user->is_verified()) {

      $user->password = request('pw');
      $user->verified_at = now();
      $user->save();

      return back()->withFlash(['success' => "Password set successfully! Login using your new credentials."]);
    }
    return back()->withFlash(['error'=>'Unauthorised']);
  }

  public function logout(Request $request)
  {
    $this->authenticatedGuard()->logout();
    collect(config('auth.guards'))->each(function ($details, $guard) {
      try {
        auth($guard)->logout();
      } catch (\Throwable $th) {
      }
    });
    $request->session()->invalidate();

    return Inertia::location(route('auth.login'));
    return redirect()->route('auth.login')->withFlash(['success' => 'Account logged out successfully']);
  }

  protected function validateLogin(Request $request)
  {
      $request->validate([
          $this->username() => 'required|string',
          'password' => 'required|string',
      ]);
  }

  protected function attemptLogin()
  {
    collect(config('auth.guards'))->each(function ($details, $guard) {
      try {
        if ($this->attemptGuardLogin($guard)) {
          $this->authSuccess = true;
        }
      } catch (\Throwable $th) {
        logger($th);
        abort(500, 'Sorry there was an error logging you in.');
      }
    });
    return $this->authSuccess;
  }

  private function attemptGuardLogin(string $guard)
  {
    if (Auth::guard($guard)->attempt($this->credentials(request()), request()->filled('remember'))) {
      return true;
    }
  }

  protected function sendLoginResponse(Request $request)
  {
    $request->session()->regenerate();

    $this->clearLoginAttempts($request);
    if ($response = $this->authenticated($request, $this->authenticatedGuard()->user())) {
      return $response;
    }

    return redirect()->intended(route($this->authenticatedGuard()->user()->getDashboardRoute()));
  }

  protected function authenticated(Request $request, User $user)
  {
    event(new UserLoggedIn($user));

    if ($user->is_verified()) {
      return Inertia::location(route($user->getDashboardRoute()));
    } else {
      $this->logout($request);

      return redirect()->route('auth.login')->withFlash(['action_required'=>true]);
    }


  }

  public function username(): string
  {
    return 'user_name';
  }

  protected function guard()
  {
    return Auth::guard('fz_staff');
  }

  protected function apiGuard(): JWTGuard
  {
    return Auth::guard('api');
  }

  protected function authenticatedGuard(): ?SessionGuard
  {
    collect(config('auth.guards'))->each(function ($details, $guard) {
      if (Auth($guard)->check()) {
        $this->authGuard = Auth::guard($guard);
        return false;
      }
    });
    return $this->authGuard;
  }

}
