<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\PermissionsController;
use App\Http\Controllers\CustomerInfoController;


// Public Routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

// Protected Routes
Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/loggeduser', [UserController::class, 'logged_user']);
    Route::post('/changepassword', [UserController::class, 'change_password']);

    //List
    Route::get('/role_list', [UserController::class, 'role_list']);
    Route::get('/permission_list', [UserController::class, 'permission_list']);
    Route::get('/user_list', [UserController::class, 'user_list']);
    Route::get('/all_user_list', [UserController::class, 'all_user_list']);

    Route::get('/user_show/{id}', [UserController::class, 'show']);

    //User Update
    Route::get('/user_edit/{id}', [UserController::class, 'user_edit']);
    Route::put('/user_update/{id}', [UserController::class, 'user_update']);

    Route::get('/role_wise_user', [UserController::class, 'role_wise_user']);

    //User Update
    Route::put('/user_update/{id}', [UserController::class, 'user_update']);

    //User Delete
    Route::delete('/user_delete/{id}', [UserController::class, 'user_delete']);

    //Assign Permission
    Route::put('/assign_permission/{id}', [UserController::class, 'assign_permission']);

    //role & permission
    Route::resource('roles', RolesController::class);
    Route::resource('permissions', PermissionsController::class);

    Route::apiResource('customers', CustomerInfoController::class);

    Route::post('/customers/attachments', [CustomerInfoController::class, 'updateAttachments']);

    Route::post('/customers/attachments/remove', [CustomerInfoController::class, 'removeAttachment']);

    Route::get('customers/{CustomerInfoID}/attachments/download-all', [CustomerInfoController::class, 'getAttachmentsByCustomer']);


    Route::get('/business-wise-customer-code', [CustomerInfoController::class, 'business_wise_customer_code']);

    Route::get('/business-wise-territory-code', [CustomerInfoController::class, 'business_wise_territory_code']);

    Route::get('/customer-wise-territory-code', [CustomerInfoController::class, 'customer_wise_territory_code']);
    Route::get(
        '/customer-code-wise-allinfo-pdf',
        [CustomerInfoController::class, 'customer_code_wise_allinfo_pdf']
    );


    Route::get('/business-wise-customer-info', [CustomerInfoController::class, 'business_wise_customer_info']);

    Route::get('/customer-search', [CustomerInfoController::class, 'customer_search']);

    Route::get('/files-list', [CustomerInfoController::class, 'files_list']);

    Route::get('/business-wise-customer', [CustomerInfoController::class, 'business_wise_customer_show']);

    Route::get('/attachment_types', [CustomerInfoController::class, 'attachment_types']);





});





