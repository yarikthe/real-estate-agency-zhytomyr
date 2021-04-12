<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\RieltorController;
use App\Http\Controllers\HomeController;

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


// Home page
Route::get('/', function () {
    return view('welcome');
});

// Pages
Route::get('/contact', function () { return view('pages.contact'); });
Route::get('/about-us', function () { return view('pages.about'); });

Route::get('/blog/{slug}', function () { return view('pages.about'); });

Auth::routes();

// Auth access to Dashboard Rieltor
Route::group(['prefix'=>'manage/rieltor', 'namespace' => 'Admin'], function(){

    Route::get('/dashboard', [RieltorController::class, 'index'])->name('rieltor.home');
    Route::get('/my-note', [RieltorController::class, 'note'])->name('rieltor.note');
    Route::get('/my-real-estate', [RieltorController::class, 'index'])->name('rieltor.estate');
});

// Auth access to Dashboard Admin
Route::group(['prefix'=>'manage/admin', 'namespace' => 'Admin'], function(){

    Route::get('/dashboard', [AdminController::class, 'indexAdmin'])->name('admin.home')->middleware('is_admin');
    Route::get('/rieltors', [AdminController::class, 'indexRieltor'])->name('admin.rieltors')->middleware('is_admin');
    Route::get('/real-estate', [AdminController::class, 'estate'])->name('admin.estate')->middleware('is_admin');
    Route::get('/note', [AdminController::class, 'note'])->name('admin.note')->middleware('is_admin');
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings')->middleware('is_admin');

    Route::group(['prefix'=>'manage/admin/blog', 'namespace' => 'Admin'], function(){

        Route::get('/posts', [BlogController::class, 'index'])->name('admin.blog')->middleware('is_admin');
        Route::get('/new-post', [BlogController::class, 'newPost'])->name('admin.blog.new.post')->middleware('is_admin');

    });
});

