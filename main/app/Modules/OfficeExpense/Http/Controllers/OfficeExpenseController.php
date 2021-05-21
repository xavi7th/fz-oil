<?php

namespace App\Modules\OfficeExpense\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\OfficeExpense\Models\OfficeExpense;

class OfficeExpenseController extends Controller
{
  static function routes()
  {
    Route::middleware('web')->prefix(OfficeExpense::DASHBOARD_ROUTE_PREFIX)->name(OfficeExpense::ROUTE_NAME_PREFIX)->group(function () {
      Route::get('', [self::class, 'index'])->name('index');
    });
  }

  /**
   * Display a listing of the resource.
   * @return Renderable
   */
  public function index()
  {
    return Inertia::render('OfficeExpense::ManageOfficeExpenses')->withViewData([
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
    return view('officeexpense::create');
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
    return view('officeexpense::show');
  }

  /**
   * Show the form for editing the specified resource.
   * @param int $id
   * @return Renderable
   */
  public function edit($id)
  {
    return view('officeexpense::edit');
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
