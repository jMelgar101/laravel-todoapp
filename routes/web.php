<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\ListItemController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [ChecklistController::class, 'index']);

    Route::resource('/checklists', ChecklistController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    Route::resource('/listItems', ListItemController::class)
        ->only(['store', 'update', 'destroy']);
});
