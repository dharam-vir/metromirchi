<?php
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\WalletController;

Route::get('/token_error', function (Request $request) {
    return json_encode(['status' => 'Failure', 'message' => 'Please enter valid token!!']);
});
Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, "Login"]);
    Route::post('/singup', [AuthController::class, 'SingUp']);
    Route::get('/logout', [AuthController::class, "Logout"]);
    Route::get('/checkIfUserIsLoggedIn', [AuthController::class, "checkIfUserIsLoggedIn"]);

      //leads 
      Route::group(['prefix' => 'leads'], function () {
        Route::post('/create', [LeadController::class, "Create"]);
        Route::post('/showlead', [LeadController::class, "showLead"]);
        Route::post('/updatestatus/{id}', [LeadController::class, "updateStatus"]);
        Route::post('/updatefollowup/{id}', [LeadController::class, "updateFollowup"]);            
    });

    Route::group(['middleware' => 'CheckJWT'], function () {

        Route::group(['prefix' => 'wallet'], function () {
            Route::post('/add-money', [WalletController::class, 'addMoney']);
            Route::get('/transaction-history', [WalletController::class, 'showTransactionHistory']);
            Route::post('/spend-money', [WalletController::class, 'spendMoney']);
            Route::post('/spend-for-leads', [WalletController::class, 'spendForLeads']);
        });

        //User
        Route::group(['prefix' => 'user'], function () {
            Route::post('/show', [UserController::class, "Index"]);
            Route::post('/create', [UserController::class, "Create"]);
            Route::post('/update', [UserController::class, "Update"]);
        });

        //Services 
        Route::group(['prefix' => 'services'], function () {
            Route::get('/my-services', [ServiceController::class, "showServices"]);
            Route::post('/add-services', [ServiceController::class, "addServices"]);
            Route::post('/deactive-services', [ServiceController::class, "deactiveServices"]);
        });

        // Comment
        Route::get('/comments/{type}/{id}', [CommentController::class, "store"]);

    });
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');