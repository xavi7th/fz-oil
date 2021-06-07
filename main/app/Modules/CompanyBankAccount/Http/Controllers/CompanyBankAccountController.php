<?php

namespace App\Modules\CompanyBankAccount\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\SuperAdmin\Traits\AccessibleToAllStaff;
use App\Modules\CompanyBankAccount\Models\CompanyBankAccount;

class CompanyBankAccountController extends Controller
{
  use AccessibleToAllStaff;

  static function routes()
  {
    Route::prefix(CompanyBankAccount::DASHBOARD_ROUTE_PREFIX)->name(CompanyBankAccount::ROUTE_NAME_PREFIX)->group(function () {
      Route::get('', [self::class, 'index'])->name('index')->defaults('menu', __e('Bank Accounts', 'viewAny,' . CompanyBankAccount::class, 'box', false));
    });
  }
  /**
   * Display a listing of the resource.
   * @return Renderable
   */
  public function index(Request $request)
  {
    $this->authorize('viewAny', CompanyBankAccount::class);
    return Inertia::render('CompanyBankAccount::ManageAccounts')->withViewData([
      'title' => 'Hello theEects',
      'metaDesc' => ' This page is ...'
    ]);
  }

  /**
   * Show the form for creating a new resource.
   * @return Renderable
   */
  public function create()
  {
    return view('companybankaccount::create');
  }

  /**
   * Store a newly created resource in storage.
   * @param Request $request
   * @return Renderable
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Show the specified resource.
   * @param int $id
   * @return Renderable
   */
  public function show($id)
  {
    return view('companybankaccount::show');
  }

  /**
   * Show the form for editing the specified resource.
   * @param int $id
   * @return Renderable
   */
  public function edit($id)
  {
    return view('companybankaccount::edit');
  }

  /**
   * Update the specified resource in storage.
   * @param Request $request
   * @param int $id
   * @return Renderable
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   * @param int $id
   * @return Renderable
   */
  public function destroy($id)
  {
    //
  }
}
