<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

// any 설정하기 : 어떠한 url이 와도 welcome으로 이동
Route::get('/{any}', function() {
    return view('welcome');
})->where('any', '^(?!api).*$'); // 조건 : api로 시작하는 것 빼고 정규식에 걸림

// 로그인
Route::post('/api/login', [UserController::class, 'login']);

// 로그아웃
Route::middleware('auth')->post('/api/logout', [UserController::class, 'logout']); 
// middleware('auth')를 먼저 실행후 post 처리
// 'auth' 는 Authenticate.php 를 실행(인증된 사용자 판별)

// 게시글 관련
Route::middleware('auth')->get('/api/board', [BoardController::class, 'index']);
Route::middleware('auth')->get('/api/board/{id}', [BoardController::class, 'moreIndex']);
Route::middleware('auth')->post('/api/create', [BoardController::class, 'boardCreate']);
Route::middleware('auth')->post('/api/board/like/{boardID}', [BoardController::class, 'likeBtn']);
Route::middleware('auth')->delete('/api/delete/{id}', [BoardController::class, 'boardDelete']);
Route::get('/api/accountIndex/{account}', [BoardController::class, 'accountIndex']);

// 회원 가입 관련
// 회원가입은 인증 할 필요 없어서 middleware 사용 안함
Route::post('/api/registration', [UserController::class, 'registration']);