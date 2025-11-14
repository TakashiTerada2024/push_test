<?php

use App\Http\Controllers\Apply as Apply;
use App\Http\Controllers\Attachment as Attachment;
use App\Http\Controllers\Auth as Auth;
use App\Http\Controllers\Message as Message;
use App\Http\Controllers\Pdf as Pdf;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

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

Route::get(
    '/',
    function () {
        return view('welcome');
    }
);

//ログイン後の画面
Route::middleware(['auth:sanctum', 'verified'])->get(
    '/dashboard',
    function () {
        return view('dashboard');
    }
)->name('dashboard');

Route::group(['middleware'=>['web','auth:'.config('fortify.guard')]],function(){
    //ログイン後に遷移する画面
    Route::get('/welcome', \App\Http\Controllers\Common\WelcomeController::class)->name('welcome');
});

//添付ファイルに関するルーティング
Route::group(['middleware'=>['web','auth:'.config('fortify.guard')]],function(){
    Route::get('/attachment/download/{id}', Attachment\DownloadController::class)
        ->name('attachment.download');
    Route::get('/attachment/apply/show/{applyId}', Attachment\Apply\ShowController::class)
        ->name('attachment.apply.show');
    //{id} はattachment_id
    Route::get('/attachment/apply/delete/{applyId}/{id}', Attachment\Apply\DeleteController::class)
        ->name('attachment.apply.delete');
    //{id} はapply_id
    Route::post('/attachment/apply/add/{id}', Attachment\Apply\AddController::class)
        ->name('attachment.apply.add');
    Route::post('/attachment/apply/choose_type/{applyId}', Attachment\Apply\ChooseTypeController::class)
        ->name('attachment.apply.choose_type');
    Route::get('/attachment/apply/submit/{applyId}/{id}', Attachment\Apply\SubmitController::class)
        ->name('attachment.apply.submit');
    Route::get('/attachment/apply/cancel/{applyId}/{id}', Attachment\Apply\CancelController::class)
        ->name('attachment.apply.cancel');
    Route::get('/attachment/apply/approve/{applyId}/{id}', Attachment\Apply\ApproveController::class)
        ->name('attachment.apply.approve');
    Route::get('/attachment/apply/disapprove/{applyId}/{id}', Attachment\Apply\DisapproveController::class)
        ->name('attachment.apply.disapprove');

    Route::get('/attachment/apply/secretariat/show/{applyId}', Attachment\Apply\Secretariat\ShowController::class)
        ->name('attachment.apply.secretariat.show');
    Route::post('/attachment/apply/secretariat/add/{id}', Attachment\Apply\Secretariat\AddController::class)
        ->name('attachment.apply.secretariat.add');
});

//メッセージングに関するルーティング
Route::group(['middleware'=>['web','auth:'.config('fortify.guard')]],function(){
    Route::get('/message/apply/show/{applyId}', Message\Apply\ShowController::class)
        ->name('message.apply.show');
    Route::post('/message/apply/send/{applyId}', Message\Apply\SendController::class,'__invoke')
        ->name('message.apply.send');
    Route::post('/message/apply/senddelete/{notificationId}', Message\Apply\SendForEditController::class,'__invoke')
       ->name('message.apply.send.delete');
    Route::get('/message/apply/sendedit/{notificationId}', Message\Apply\SendForEditController::class,'__invoke')
        ->name('message.apply.send.edit');
});

//PDF出力に関するルーティング
Route::group(['middleware'=>['web','auth:'.config('fortify.guard')]],function(){
    Route::get('/pdf/apply/download/{applyId}', Pdf\Apply\DownloadController::class)
        ->name('pdf.apply.download');
});

//申請に関するルーティング
Route::group(['middleware'=>['web','auth:'.config('fortify.guard')]],function(){
    Route::get('/apply/start/{applyId?}',Apply\Start\DisplayController::class)->name('apply.start');
    Route::post('/apply/start/{applyId?}',Apply\Start\SaveController::class);
    //一時保存機能を追加
    Route::post('/apply/tmp_save/{applyId?}',Apply\Start\TmpSaveController::class)->name('apply.tmp_save');

    //申し出を一覧表示する画面のルーティング
    Route::get('/apply/lists/search', Apply\Lists\SearchController::class)->name('apply.lists.search');
    //申請者用、申請一覧
    Route::get('/apply/lists/my_list',Apply\Lists\MyListController::class)->name('apply.lists.my_list');

    //窓口組織用、事前相談一覧
    Route::get('/apply/lists/prior_consultation',Apply\Lists\PriorConsultationController::class)
        ->name('apply.lists.prior_consultation');
    //窓口組織用、文書作成～確認 リンケージ
    Route::get('/apply/lists/creating_linkage',Apply\Lists\CreatingLinkageController::class)
        ->name('apply.lists.creating_linkage');
    //窓口組織用、文書作成～確認 集計統計
    Route::get('/apply/lists/creating_statistics',Apply\Lists\CreatingStatisticsController::class)
        ->name('apply.lists.creating_statistics');
    Route::get('/apply/lists/submitting',Apply\Lists\SubmittingController::class)
        ->name('apply.lists.submitting');
    Route::get('/apply/lists/accepted', Apply\Lists\AcceptedController::class)
        ->name('apply.lists.accepted');

    //一覧から、個別の申請に対する操作を行う類のルーティング
    //申出種別の変更
    Route::post('/apply/change_type/{applyId}',Apply\ChangeTypeController::class)
        ->name('apply.change_type');
    //申出文書作成開始
    Route::post('/apply/start_creating_document/{applyId}',Apply\StartCreatingDocumentController::class)
        ->name('apply.start_creating_document');
    //申請中止
    Route::post('/apply/cancel/{applyId}',Apply\CancelController::class)
        ->name('apply.cancel');
    //応諾
    Route::post('/apply/accept/{applyId}',Apply\AcceptController::class)
        ->name('apply.accept');

    //承認依頼
    Route::post('/apply/start_checking_document/{applyId}',Apply\StartCheckingDocumentController::class)
        ->name('apply.start_checking_document');

    Route::post('/apply/start_submitting_document/{applyId}',Apply\StartSubmittingDocumentController::class)
        ->name('apply.start_submitting_document');
    Route::post('/apply/remand_checking_document/{applyId}',Apply\RemandCheckingDocumentController::class)
        ->name('apply.remand_checking_document');



    //申請の詳細に関するルーティング
    Route::get('/apply/detail/overview/{applyId}',Apply\Detail\OverviewShowController::class)
        ->name('apply.detail.overview');

    Route::get('/apply/detail/section1/{applyId}',Apply\Detail\Section01ShowController::class)
        ->name('apply.detail.section1');
    Route::post('/apply/detail/section1/{applyId}',Apply\Detail\SaveSection01Controller::class);

    Route::get('/apply/detail/section2/{applyId}',Apply\Detail\Section02ShowController::class)
        ->name('apply.detail.section2');
    Route::post('/apply/detail/section2/{applyId}',Apply\Detail\SaveSection02Controller::class);


    Route::get('/apply/detail/section3/{applyId}',Apply\Detail\Section03ShowController::class)
        ->name('apply.detail.section3');
    Route::post('/apply/detail/section3/{applyId}',Apply\Detail\SaveSection03Controller::class);

    Route::get('/apply/detail/section4/{applyId}',Apply\Detail\Section04ShowController::class)
        ->name('apply.detail.section4');
    Route::post('/apply/detail/section4/{applyId}',Apply\Detail\SaveSection04Controller::class);

    Route::get('/apply/detail/section5/{applyId}',Apply\Detail\Section05ShowController::class)
        ->name('apply.detail.section5');
    Route::post('/apply/detail/section5/{applyId}',Apply\Detail\SaveSection05Controller::class);

    Route::get('/apply/detail/section6/{applyId}',Apply\Detail\Section06ShowController::class)
        ->name('apply.detail.section6');
    Route::post('/apply/detail/section6/{applyId}',Apply\Detail\SaveSection06Controller::class);

    Route::get('/apply/detail/section7/{applyId}',Apply\Detail\Section07ShowController::class)
        ->name('apply.detail.section7');
    Route::post('/apply/detail/section7/{applyId}',Apply\Detail\SaveSection07Controller::class);

    Route::get('/apply/detail/section8/{applyId}',Apply\Detail\Section08ShowController::class)
        ->name('apply.detail.section8');
    Route::post('/apply/detail/section8/{applyId}',Apply\Detail\SaveSection08Controller::class);

    Route::get('/apply/detail/section9/{applyId}',Apply\Detail\Section09ShowController::class)
        ->name('apply.detail.section9');
    Route::post('/apply/detail/section9/{applyId}',Apply\Detail\SaveSection09Controller::class);

    Route::get('/apply/detail/section10/{applyId}',Apply\Detail\Section10ShowController::class)
        ->name('apply.detail.section10');
    Route::post('/apply/detail/section10/{applyId}',Apply\Detail\SaveSection10Controller::class);

    Route::get('/apply/detail/basic-info/{applyId}',Apply\Detail\SectionBasicInfoShowController::class)
        ->name('apply.detail.basic.info');
    Route::post('/apply/detail/basic-info/{applyId}',Apply\Detail\SaveSectionBasicInfoController::class);

    // ロック管理画面
    Route::get('/lock/management/{applyId}', App\Http\Controllers\Apply\Lock\ManagementController::class)
        ->name('lock.management');
    Route::post('/lock/management/{applyId}', App\Http\Controllers\Apply\Lock\SaveScreenLocksController::class)
        ->name('lock.management.save');
    Route::post('/lock/management/attachment/{applyId}', App\Http\Controllers\Apply\Lock\SaveAttachmentLocksController::class)
        ->name('lock.management.attachment.save');

});

// 申出スキップURLアクセス用ルート
Route::get('/apply/{ulid}', [App\Http\Controllers\Apply\SkipPreliminaryController::class, 'handle'])
    ->name('apply.skip');

// 最低限必要な情報登録画面用ルート
Route::middleware(['auth:'.config('fortify.guard')])->group(function () {
    Route::get('/apply/minimum-info/create', [App\Http\Controllers\Apply\MinimumInfo\DisplayController::class, '__invoke'])
        ->middleware('check.skip.preliminary')
        ->name('apply.minimum-info.create');
    
    Route::post('/apply/minimum-info/save', [App\Http\Controllers\Apply\MinimumInfo\SaveController::class, '__invoke'])
        ->middleware('check.skip.preliminary')
        ->name('apply.minimum-info.save');
});

// 申出スキップURL発行（認証済みユーザー向け）
Route::post('/api/generate-skip-url', [App\Http\Controllers\Api\SkipUrlController::class, 'generateUrl'])
    ->middleware('auth');

/**
 * Fortify関連のルーティングをカスタマイズするため
 * vendor/laravel/fortify/routes/routes.php および
 * Laravel\Fortify\FortifyServiceProvider::configureRoutes()
 * の実装内容を参考にしながら以下に転記し修正を加えた（主にコントローラーの差し替え）
 * @see Laravel\Fortify\FortifyServiceProvider::configureRoutes()
 */
Route::group(
    [
        'domain' => config('fortify.domain', null),
        'prefix' => config('fortify.prefix'),
    ],
    function () {
        Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {
            $enableViews = config('fortify.views', true);

            // Authentication...
            if ($enableViews) {
                Route::get('/login', Auth\DisplayLoginForm::class)
                    ->middleware(['guest:'.config('fortify.guard')])
                    ->name('login');
            }

            $limiter = config('fortify.limiters.login');
            $twoFactorLimiter = config('fortify.limiters.two-factor');
            $verificationLimiter = config('fortify.limiters.verification', '6,1');

            Route::post('/login', Auth\Login::class)
                ->middleware(array_filter([
                                              'guest:'.config('fortify.guard'),
                                              $limiter ? 'throttle:'.$limiter : null,
                                          ]));

            Route::post('/logout', Auth\Logout::class)
                ->name('logout');

            // Password Reset...
            if (Features::enabled(Features::resetPasswords())) {
                if ($enableViews) {
                    Route::get('/forgot-password', Auth\DisplayForgotPasswordForm::class)
                        ->middleware(['guest:'.config('fortify.guard')])
                        ->name('password.request');

                    Route::get('/reset-password/{token}', Auth\DisplayPasswordResetForm::class)
                        ->middleware(['guest:'.config('fortify.guard')])
                        ->name('password.reset');
                }

                Route::post('/forgot-password', Auth\SendResetLink::class)
                    ->middleware(['guest:'.config('fortify.guard')])
                    ->name('password.email');

                Route::post('/reset-password', Auth\PasswordReset::class)
                    ->middleware(['guest:'.config('fortify.guard')])
                    ->name('password.update');
            }

            // Registration...
            if (Features::enabled(Features::registration())) {
                if ($enableViews) {
                    Route::get('/register', Auth\DisplayRegisterForm::class)
                        ->middleware(['guest:'.config('fortify.guard')])
                        ->name('register');
                }

                Route::post('/register', Auth\Register::class)
                    ->middleware(['guest:'.config('fortify.guard')]);
            }

            // Email Verification...
            if (Features::enabled(Features::emailVerification())) {
                if ($enableViews) {
                    Route::get('/email/verify', Auth\Email\DisplayVerificationPrompt::class)
                        ->middleware(['auth:'.config('fortify.guard')])
                        ->name('verification.notice');
                }

                Route::get('/email/verify/{id}/{hash}', Auth\Email\Verify::class)
                    ->middleware(['auth:'.config('fortify.guard'), 'signed', 'throttle:'.$verificationLimiter])
                    ->name('verification.verify');

                Route::post('/email/verification-notification', Auth\Email\Notify::class)
                    ->middleware(['auth:'.config('fortify.guard'), 'throttle:'.$verificationLimiter])
                    ->name('verification.send');
            }

            // Profile Information...
            if (Features::enabled(Features::updateProfileInformation())) {
                Route::put('/user/profile-information', Auth\User\UpdateProfile::class)
                    ->middleware(['auth:'.config('fortify.guard')])
                    ->name('user-profile-information.update');
            }

            // Passwords...
            if (Features::enabled(Features::updatePasswords())) {
                Route::put('/user/password', Auth\User\UpdatePassword::class)
                    ->middleware(['auth:'.config('fortify.guard')])
                    ->name('user-password.update');
            }

            // Password Confirmation...
            if ($enableViews) {
                Route::get('/user/confirm-password', Auth\User\DisplayPasswordConfirmForm::class)
                    ->middleware(['auth:'.config('fortify.guard')])
                    ->name('password.confirm');
            }

            Route::get('/user/confirmed-password-status', Auth\User\DisplayConfirmedPasswordStatus::class)
                ->middleware(['auth:'.config('fortify.guard')])
                ->name('password.confirmation');

            Route::post('/user/confirm-password', Auth\User\ConfirmPassword::class)
                ->middleware(['auth:'.config('fortify.guard')]);

            // Two Factor Authentication...
            if (Features::enabled(Features::twoFactorAuthentication())) {
                if ($enableViews) {
                    Route::get('/two-factor-challenge', Auth\DisplayTwoFactorChallengeForm::class)
                        ->middleware(['guest:'.config('fortify.guard')])
                        ->name('two-factor.login');
                }

                Route::post('/two-factor-challenge', Auth\TwoFactorChallenge::class)
                    ->middleware(array_filter([
                                                  'guest:'.config('fortify.guard'),
                                                  $twoFactorLimiter ? 'throttle:'.$twoFactorLimiter : null,
                                              ]));

                $twoFactorMiddleware = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')
                    ? ['auth:'.config('fortify.guard'), 'password.confirm']
                    : ['auth:'.config('fortify.guard')];

                Route::post('/user/two-factor-authentication', Auth\User\EnableTwoFactorAuth::class)
                    ->middleware($twoFactorMiddleware)
                    ->name('two-factor.enable');

                Route::delete('/user/two-factor-authentication', Auth\User\DisableTwoFactorAuth::class)
                    ->middleware($twoFactorMiddleware)
                    ->name('two-factor.disable');

                Route::get('/user/two-factor-qr-code', Auth\User\DisplayTwoFactorQrCode::class)
                    ->middleware($twoFactorMiddleware)
                    ->name('two-factor.qr-code');

                Route::get('/user/two-factor-recovery-codes', Auth\User\DisplayRecoveryCode::class)
                    ->middleware($twoFactorMiddleware)
                    ->name('two-factor.recovery-codes');

                Route::post('/user/two-factor-recovery-codes', Auth\User\RefreshRecoveryCode::class)
                    ->middleware($twoFactorMiddleware);
            }
        });
    }
);
//Fortify関連 ココまで
