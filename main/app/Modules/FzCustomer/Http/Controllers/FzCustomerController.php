<?php

namespace App\Modules\FzCustomer\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\FzCustomer\Models\FzCustomer;

class FzCustomerController extends Controller
{

  static function routes()
  {
    Route::middleware('web')->prefix(FzCustomer::DASHBOARD_ROUTE_PREFIX)->name(FzCustomer::ROUTE_NAME_PREFIX)->group(function () {
      Route::get('', [self::class, 'index'])->name('index');
      Route::get('credit-repayment', [self::class, 'manageCustomerCredit'])->name('credit');
    });
  }
    public function index()
    {
      return Inertia::render('FzCustomer::ManageCustomers')->withViewData([
        'title' => 'Hello theEects',
        'metaDesc' => ' This page is ...'
      ]);
    }

    public function create()
    {
        return view('fzcustomer::create');
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        return view('fzcustomer::show');
    }


    public function edit($id)
    {
        return view('fzcustomer::edit');
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function manageCustomerCredit(Request $request)
    {
      return Inertia::render('FzCustomer::ManageCustomerCredit')->withViewData([
        'title' => 'Hello theEects',
        'metaDesc' => ' This page is ...'
      ]);
    }
}
