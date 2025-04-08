<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\MembersController;
use App\Exports\PembelianExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Member;

Route::get('/', function () {
    return redirect('/login'); // Langsung redirect ke halaman login
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Perbaikan: Gunakan UserController::class
Route::resource('user', UserController::class)->middleware('auth');
Route::resource('produk', ProdukController::class)->middleware('auth');
Route::patch('/produk/{id}/updateStock', [ProdukController::class, 'updateStock'])->name('produk.updateStock');

Route::resource('pembelian', PembelianController::class)->middleware('auth');
Route::get('/export-pembelian', function () {
    return Excel::download(new PembelianExport, 'pembelian.xlsx');
});
Route::get('/export-pembelian-pdf/{id}', [PembelianController::class, 'exportPDF']);
Route::get('/add/produk', [PembelianController::class, 'add_produk'])->name('add_produk');
Route::get('/checkout', [PembelianController::class, 'checkout'])->name('checkout');
Route::post('/simpan-pembelian', [PembelianController::class, 'simpanDetail']);
Route::post('/pembelian/simpan', [PembelianController::class, 'simpanPembelian'])->name('pembelian.simpan');
Route::post('/checkout/cek-member', [PembelianController::class, 'cekMember'])->name('checkout.cekMember');
Route::post('/checkout/simpan-detail', [PembelianController::class, 'simpanDetail'])->name('checkout.simpanDetail');


Route::get('/members', [MembersController::class, 'index'])->name('members.index');
Route::post('/member/store', [MembersController::class, 'store'])->name('member.store');

Route::get('/struk', [MembersController::class, 'struk'])->name('struk');