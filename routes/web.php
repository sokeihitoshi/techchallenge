<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebCrawlerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

if (App::environment('production')) {  
    URL::forceScheme('https');  
}  

Route::get('/start', [WebCrawlerController::class, 'start'])->name('start');
Route::post('/crawl', [WebCrawlerController::class, 'crawl'])->name('crawl');