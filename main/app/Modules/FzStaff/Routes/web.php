<?php

use App\Modules\FzStaff\Http\Controllers\FzStaffController;
use App\Modules\FzStaff\Http\Controllers\Auth\LoginController;
use App\Modules\FzStaff\Http\Controllers\Auth\RegisterController;

LoginController::routes();
RegisterController::routes();
// ResetPasswordController::routes();
// ForgotPasswordController::routes();
// ConfirmPasswordController::routes();
// VerificationController::routes();

FzStaffController::routes();
