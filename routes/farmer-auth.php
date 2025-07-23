<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Farmer\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Farmer\Auth\RegisteredUserController;
use App\Http\Controllers\Farmer\FarmerDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Farmer\PDFController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\Farmer\Auth\ProfilesController;
use App\Http\Controllers\Farmer\ImageRecognitionController;
use App\Http\Controllers\Farmer\ImageAnalysisController;
use App\Http\Controllers\Farmer\RequestController;
// use App\Http\Controllers\Farmer\Auth\CaptchaController;
use App\Http\Controllers\Farmer\FarmerAuthController;
use App\Http\Controllers\Farmer\NotificationControllerfarmer;
use App\Http\Controllers\Farmer\FarmerHelpController;


Route::middleware('guest:farmer')->prefix('farmer')->name('farmer.')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/reload-captcha', [RegisteredUserController::class, 'reloadCaptcha']);

    Route::get('/forgot-password', [FarmerAuthController::class, 'showForgotPasswordForm'])->name('farmer.forgot-password');
    Route::post('/verify-fullname', [FarmerAuthController::class, 'verifyFullName'])->name('farmer.verify.fullname');
    Route::get('/reset-password/{id}', [FarmerAuthController::class, 'showResetPasswordForm'])->name('farmer.reset-password');
    Route::post('/update-password/{id}', [FarmerAuthController::class, 'updatePassword'])->name('farmer.update.password');
});


Route::middleware('auth:farmer')->prefix('farmer')->name('farmer.')->group(function () {
    Route::get('/dashboard', [FarmerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/supplies', [FarmerDashboardController::class, 'supplies'])->name('supplies');
    Route::get('/dashboard/send-request', [FarmerDashboardController::class, 'showRequestForm'])->name('show-request-form'); // Corrected route name
    Route::post('/dashboard/send-request', [FarmerDashboardController::class, 'sendRequest'])->name('send-request'); // Corrected route name
    Route::get('/equipment', [FarmerDashboardController::class, 'equipment'])->name('equipment');
    Route::get('/borrow', [FarmerDashboardController::class, 'showBorrowForm'])->name('borrow.form');
    Route::post('/borrow', [FarmerDashboardController::class, 'store'])->name('borrow.store');
    Route::get('/update', [PdfController::class, 'showUploadForm'])->name('farmer.update');
   // Route::post('/update', [PdfController::class, 'showUploadForm'])->name('farmer.update');
    //Route::get('/PdfConvert', [PdfController::class, 'convertToPdf'])->name('farmer.PdfConvert');

    Route::post('/generate-pdf-report', [PDFController::class, 'generateReport'])->name('generate.pdf.report');

   Route::get('/image-analysis', [ImageAnalysisController::class, 'showForm'])->name('image.analysis.form');
   Route::post('/image-analysis', [ImageAnalysisController::class, 'analyze'])->name('image.analysis.analyze');
    
   Route::get('/farmer/update', [ImageRecognitionController::class, 'showUpdateForm'])->name('farmer.update.form');
   Route::post('/farmer/update', [ImageRecognitionController::class, 'recognize'])->name('farmer.update.recognize');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('farmer/profile', [ProfilesController::class, 'show'])->name('farmer.profile.show');
    Route::get('farmer/profile/edit', [ProfilesController::class, 'edit'])->name('farmer.profile.edit');
    Route::put('farmer/profile/update', [ProfilesController::class, 'update'])->name('farmer.profile.update');

    Route::get('/farmer/requests', [RequestController::class, 'index'])->name('farmer.request');

    Route::delete('/delete-supply-request/{id}', [RequestController::class, 'deleteSupplyRequest'])->name('delete.supply.request');
    Route::get('/undo-delete-supply/{id}', [RequestController::class, 'undoDeleteSupplyRequest'])->name('undo.delete.supply');
    Route::get('/permanently-delete-supply/{id}', [RequestController::class, 'permanentlyDeleteSupplyRequest'])->name('permanently.delete.supply');
    
    Route::delete('/farmer/request/borrow/{id}', [RequestController::class, 'deleteBorrowRequest'])->name('farmer.farmer.deleteBorrowRequest');
    
    Route::delete('/notifications/{id}', [NotificationControllerfarmer::class, 'destroy'])->name('notifications.destroy');

    Route::get('/farmer/help', [FarmerHelpController::class, 'index'])->name('farmer.help');

});

