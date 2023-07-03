<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController,
    App\Http\Controllers\Auth\LoginController,
    App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpendController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleDetailController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;

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


// Login
Auth::routes();

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth'], function () {
    // Home
    Route::resource('/dashboard', DashboardController::class)->except('create', 'show');

    Route::group(['middleware' => 'level:admin'], function () {
        // Route Category
        Route::resource('/category', CategoryController::class)->except('create', 'show');

        // Route Product
        Route::resource('/product', ProductController::class)->except('create', 'show');
        Route::get('/product/select', [ProductController::class, 'select']);
        Route::get('/product/list-product', [ProductController::class, 'getProduct']);
        Route::post('/product/delete-selected', [ProductController::class, 'checkBoxDelete'])->name('product.delete_selected');

        // Route Member
        Route::resource('/member', MemberController::class)->except('create', 'show');
        Route::post('/member/cetak-member', [MemberController::class, 'cetakMember'])->name('member.cetak-member');

        // Route User
        Route::resource('/user', UserController::class)->except('create', 'show');

        // Route Report
        Route::get('/report', [ReportController::class, 'index'])->name('report.index');
        Route::get('/report/data/{awal}/{akhir}', [ReportController::class, 'data'])->name('report.data');
        Route::get('/report/pdf/{awal}/{akhir}', [ReportController::class, 'exportPDF'])->name('report.export_pdf');

        // Route Sale
        Route::get('/sale/data', [SaleController::class, 'data'])->name('sale.data');
        Route::get('/sale', [SaleController::class, 'index'])->name('sale.index');
        Route::get('/sale/{id}', [SaleController::class, 'show'])->name('sale.show');
        Route::delete('/sale/{id}', [SaleController::class, 'destroy'])->name('sale.destroy');

        // Route Expend
        Route::resource('/expend', ExpendController::class)->except('create', 'show');
        Route::put('/expend/archive/{id}', [ExpendController::class, 'archive'])->name('archive.expend');

        // Route Setting
        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::get('/setting/first', [SettingController::class, 'show'])->name('setting.show');
        Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');

        // Route Archive Expend
        Route::get('/archive-expend', [ExpendController::class, 'indexExpend'])->name('expend-archive');
        Route::put('/archive-expend/pulih/{id}', [ExpendController::class, 'pulihExpend']);
    });

    Route::group(['middleware' => 'level:admin,kasir'], function () {
        // Route Transaksi
        Route::get('/transaction/new', [SaleController::class, 'create'])->name('transaction.new');
        Route::post('/transaksi/save', [SaleController::class, 'store'])->name('transaction.save');
        Route::get('/transaction/end', [SaleController::class, 'end'])->name('transaction.end');
        Route::get('/transaction/small-note', [SaleController::class, 'smallNote'])->name('transaction.small-note');
        Route::get('/transaction/big-note', [SaleController::class, 'bigNote'])->name('transaction.big-note');
        Route::get('/transaction/{id}/data', [SaleDetailController::class, 'data'])->name('transaction.data');
        Route::get('/transaction/loadform/{diskon}/{total}/{diterima}', [SaleDetailController::class, 'loadForm'])->name('transaction.load_form');
        Route::resource('/transaction', SaleDetailController::class)->except('create', 'show', 'edit');
    });
});
