<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PersonalAccessTokenController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/personal-access-tokens', [PersonalAccessTokenController::class, 'store']);
Route::get('/users/{user:name}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/tags', [TagController::class, 'index']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [UserController::class, 'edit']);
    Route::delete('/personal-access-token', [PersonalAccessTokenController::class, 'destroy']);

    Route::post('/articles', [ArticleController::class, 'store']);
    Route::put('articles/{article:slug}', [ArticleController::class, 'update']);
    Route::delete('articles/{article:slug}', [ArticleController::class, 'destroy']);
    Route::post('articles/{article:slug}/comments', [CommentController::class, 'store']);
    Route::put('comments/{comment}', [CommentController::class, 'update']);
    Route::delete('comments/{comment}', [CommentController::class, 'destroy']);
});
Route::get('articles', [ArticleController::class, 'index']);
Route::get('articles/{article:slug}', [ArticleController::class, 'show']);

Route::get('articles/{article:slug}/comments', [CommentController::class, 'index']);
