<?php
Route::group(['middleware' => 'web'], function () {
  Route::redirect('/', '/login', 303)->name('app.home');
});
