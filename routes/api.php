<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CombinedApiController;

// Grouping the routes with the 'api' prefix
// Admin Authentication Routes
Route::post('/admin/signup', [CombinedApiController::class, 'adminSignUp']);
Route::post('/admin/signin', [CombinedApiController::class, 'adminSignIn']);
Route::post('/admin/signout', [CombinedApiController::class, 'adminSignOut']);

// Approval Routes
Route::get('/approvals', [CombinedApiController::class, 'approvalIndex']);
Route::post('/approvals', [CombinedApiController::class, 'approvalStore']);

// Contact Routes
Route::post('/contact', [CombinedApiController::class, 'contactSubmit']);

// Message Routes
Route::post('/messages/{freelancerId}', [CombinedApiController::class, 'messageStore']);
Route::get('/messages', [CombinedApiController::class, 'viewMessages']);

// Review Routes
Route::get('/reviews', [CombinedApiController::class, 'reviewIndex']);
Route::post('/reviews', [CombinedApiController::class, 'reviewStore']);

// Service Routes
Route::get('/services/add', [CombinedApiController::class, 'addService']);
Route::post('/services', [CombinedApiController::class, 'serviceStore']);
Route::get('/services', [CombinedApiController::class, 'viewService']);

// User Management Routes
Route::get('/users', [CombinedApiController::class, 'userIndex']);
Route::post('/users', [CombinedApiController::class, 'userStore']);
Route::get('/users/{id}/edit', [CombinedApiController::class, 'userEdit']);
Route::put('/users/{id}', [CombinedApiController::class, 'userUpdate']);
Route::delete('/users/{id}', [CombinedApiController::class, 'userDestroy']);
