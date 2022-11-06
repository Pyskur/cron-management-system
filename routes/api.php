<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CronController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\CodeCheckController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\ResetPasswordController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\LimitController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\KeyController;
use App\Http\Controllers\Api\KeysCodeController;
use App\Http\Controllers\Api\KeysRefController;
use App\Http\Controllers\Api\KeysSmsController;
use App\Http\Controllers\Api\AutoRenewController;
use App\Http\Controllers\Api\EpdController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\HttpSettingController;
use App\Http\Controllers\Api\SmsController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\UsersCompaniesController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\UsersGroupsController;
use App\Http\Controllers\Api\UsersNotificationsController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\VideoPlayerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum', 'throttle:auth'])->group(function () {

    Route::middleware('throttle:auth')->group(function () {
        Route::get('/auth/user', function (Request $request) {
            return $request->user();
        
        });
        Route::delete('auth/logout', [AuthController::class,'logout']);
        
        
        Route::apiResource('sms', SmsController::class);
        
        Route::get('report/daily_report', [ReportController::class, 'daily_report']);
        Route::get('report/monthly_report', [ReportController::class, 'monthly_report']);
        Route::get('report/weekly_report', [ReportController::class, 'weekly_report']);
        
        Route::get('users/{user}/companies', [UserController::class, 'getCompanies']);
        Route::get('companies/{company}/users', [CompanyController::class, 'getUsers']);
        Route::post('companies/add-user', [CompanyController::class, 'addUsertoCompany']);
        Route::delete('companies/delete-user', [CompanyController::class, 'deleteUserfromCompany']);
        Route::post('companies/add-group', [CompanyController::class, 'addGrouptoCompany']);
        
        Route::get('users/{user}/groups', [UserController::class, 'getGroups']);
        Route::get('groups/{group}/users', [GroupController::class, 'getUsers']);
        Route::post('groups/add-user', [GroupController::class, 'addUsertoGroup']);
        Route::delete('groups/delete-user', [GroupController::class, 'deleteUserfromGroup']);

        Route::post('users_notifications/getNotifications', [UsersNotificationsController::class, 'getNotifications']);
        Route::post('users_notifications/getUsers', [UsersNotificationsController::class, 'getUsers']);
        Route::post('users_notifications/addUsertoNotification', [UsersNotificationsController::class, 'addUsertoNotification']);
        Route::delete('users_notifications/deleteUserfromNotification', [UsersNotificationsController::class, 'deleteUserfromNotification']);
        Route::get('payments/auto_renew_user_payment/{id}', [PaymentController::class, 'auto_renew_user_payment']);
        Route::get('payments/ipn', [PaymentController::class, 'ipn']);
        Route::post('payments/addPaymentMethod', [PaymentController::class, 'addPaymentMethod']);
        Route::post('payments/getMyStripeProfile', [PaymentController::class, 'getMyStripeProfile']);
        Route::post('payments/getMyStripePaymentMethods', [PaymentController::class, 'getMyStripePaymentMethods']);
        
        Route::post('sms/sendMessage', [SmsController::class, 'sendMessage']);
        Route::post('sms/sendUserVerificationMessage', [SmsController::class, 'sendUserVerificationMessage']);
    
        Route::apiResource('users', UserController::class);
        Route::apiResource('notifications', NotificationController::class);
        Route::apiResource('keys', KeyController::class);
        Route::apiResource('keys_codes', KeysCodeController::class);
        Route::apiResource('keys_refs', KeysRefController::class);
        Route::apiResource('keys_smses', KeysSmsController::class);
        Route::apiResource('crons', CronController::class);
        Route::apiResource('auto_renew', AutoRenewController::class);
        Route::apiResource('companies', CompanyController::class);
        Route::apiResource('epds', EpdController::class);
        Route::apiResource('groups', GroupController::class);
        Route::apiResource('http_settings', HttpSettingController::class);
        Route::apiResource('limits', LimitController::class);
        Route::apiResource('orders', OrderController::class);
        Route::apiResource('countries', CountryController::class);

        //Video
        Route::post('videos/upload', [VideoController::class, 'uploadVideo']);
        Route::post('videos/test', [VideoController::class, 'test']);
        Route::post('videos/init-table', [VideoController::class, 'initTable']);
        Route::get('videos/status/{video}', [VideoController::class, 'getStatus']);
        Route::get('videos/thumbnails/{video}', [VideoController::class, 'getThumbnailsList']);
        Route::get('videos/by-path', [VideoController::class, 'getVideosByPath']);
        Route::get('videos/admin/by-path', [VideoController::class, 'getVideosByPathAdmin']);
        Route::apiResource('videos', VideoController::class);
    
        
        Route::apiResource('video_players', VideoPlayerController::class)->except([
            'show'
        ]);
    });

    Route::middleware('throttle:security')->group(function () {
        //Place route here
    });
    
});
Route::post('videos/test-noauth', [VideoController::class, 'test']);

    
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('login_required', [AuthController::class, 'login_required'])->name('login');
    Route::post('email_verification', [AuthController::class, 'email_verification']);
	Route::post('token', [AuthController::class, 'loginWithApiKey']);
    Route::post('password/email', ForgotPasswordController::class);
    Route::post('password/code/check', CodeCheckController::class);
    Route::post('password/reset', ResetPasswordController::class); 
});    

Route::post('videos/hook-receive', [VideoController::class, 'hookVideoUploaded']);
Route::get('videos/playback-url/{video}', [VideoController::class, 'getPlaybackUrl']);
Route::apiResource('video_players', VideoPlayerController::class)->only([
    'show'
]);