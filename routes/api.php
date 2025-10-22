<?php

use App\Http\Controllers\Api\CreateArticleController;
use App\Http\Controllers\Api\CreateCommentController;
use App\Http\Controllers\Api\CreatePersonalAccessTokenController;
use App\Http\Controllers\Api\CreateUserController;
use App\Http\Controllers\Api\DeleteArticleController;
use App\Http\Controllers\Api\DeleteCommentController;
use App\Http\Controllers\Api\DeletePersonalAccessTokenController;
use App\Http\Controllers\Api\FavoriteArticleController;
use App\Http\Controllers\Api\FollowUserController;
use App\Http\Controllers\Api\GetCurrentUserController;
use App\Http\Controllers\Api\ListArticleCommentsController;
use App\Http\Controllers\Api\ListArticlesController;
use App\Http\Controllers\Api\ListTagsController;
use App\Http\Controllers\Api\ShowArticleController;
use App\Http\Controllers\Api\ShowUserController;
use App\Http\Controllers\Api\UnfavoriteArticleController;
use App\Http\Controllers\Api\UnfollowUserController;
use App\Http\Controllers\Api\UpdateArticleController;
use App\Http\Controllers\Api\UpdateCommentController;
use App\Http\Controllers\Api\UpdateUserController;
use Illuminate\Support\Facades\Route;


Route::post('/personal-access-tokens', CreatePersonalAccessTokenController::class);
Route::get('/users/{user:name}', ShowUserController::class);
Route::post('/users', CreateUserController::class);
Route::get('/tags', ListTagsController::class);
Route::get('articles', ListArticlesController::class);
Route::get('articles/{article:slug}', ShowArticleController::class);
Route::get('articles/{article:slug}/comments', ListArticleCommentsController::class);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', GetCurrentUserController::class);
    Route::put('/user', UpdateUserController::class);
    Route::delete('/personal-access-token', DeletePersonalAccessTokenController::class);

    Route::post('/articles', CreateArticleController::class);
    Route::post('articles/{article:slug}/comments', CreateCommentController::class);
    Route::put('articles/{article:slug}', UpdateArticleController::class)
        ->can('update', 'article');
    Route::delete('articles/{article:slug}', DeleteArticleController::class)
        ->can('delete', 'article');
    Route::put('comments/{comment}', UpdateCommentController::class)
        ->can('update', 'comment');
    Route::delete('comments/{comment}', DeleteCommentController::class)
        ->can('delete', 'comment');

    Route::post('/users/{user:name}/followers', FollowUserController::class);
    Route::post('/users/{user:name}/unfollow', UnfollowUserController::class);

    Route::post('/articles/{article:slug}/favorites', FavoriteArticleController::class);
    Route::post('/articles/{article:slug}/unfavorite', UnfavoriteArticleController::class);
});
