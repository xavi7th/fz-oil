<?php

use App\Modules\AppUser\Http\Controllers\AppUserController;
use App\Modules\AppUser\Http\Controllers\Auth\LoginController;
use App\Modules\AppUser\Http\Controllers\Auth\RegisterController;

LoginController::routes();
RegisterController::routes();
// ResetPasswordController::routes();
// ForgotPasswordController::routes();
// ConfirmPasswordController::routes();
// VerificationController::routes();

AppUserController::routes();
