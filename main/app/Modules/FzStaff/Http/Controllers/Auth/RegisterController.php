<?php

namespace App\Modules\FzStaff\Http\Controllers\Auth;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Registered;
use App\Modules\FzStaff\Models\FzStaff;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
  use RegistersUsers;

  /**
   * Where to redirect users after registration.
   *
   * @var string
   */
  // protected $redirectTo = route('fzstaff.dashboard');
  protected function redirectTo()
  {
    return route('fzstaff.dashboard');
  }

  public function __construct()
  {
    $this->middleware('guest');
  }

  static function routes()
  {
    Route::group(['middleware' => 'guest'], function () {
      Route::get('register', [self::class, 'showRegistrationForm'])->name('auth.register')->defaults('nav_skip', true);
      Route::post('register', [self::class, 'register']);
    });
  }

  public function showRegistrationForm()
  {
    return Inertia::render('FzStaff,auth/Register');
  }

  public function register(Request $request)
  {
    DB::beginTransaction();
    event(new Registered($user = $this->create($request)));

    $this->guard()->login($user);

    $this->apiToken = $this->apiGuard()->login($user);

    return $this->registered($request, $user)
      ?: redirect($this->redirectPath());
  }

  protected function create(Request $request): FzStaff
  {
    return FzStaff::create($request->validated());
  }

  protected function registered(Request $request, $user)
  {
    DB::commit();
    return redirect()->route('auth.login')->withFlash(['success'=>'Account Created']);
  }

}
