<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\SupplyController;
use App\Http\Controllers\Admin\EquipmentController;
use App\Http\Controllers\Admin\InsightController;
use App\Http\Controllers\Admin\ExistingFarmerController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminApprovalController;
use App\Http\Controllers\Admin\AdminEquipmentApprovalController;
use App\Http\Controllers\Admin\Auth\VerificationController;
use App\Http\Controllers\Admin\AdminForgotPasswordController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\AdminHelpController;


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;




Route::middleware('guest:admin')->prefix('admin')->name('admin.')->group(function () {


    Route::get('forgot-password', [AdminForgotPasswordController::class, 'showForgotPasswordForm'])->name('admin.forgot-password.form');
    Route::post('forgot-password', [AdminForgotPasswordController::class, 'sendResetLink'])->name('admin.forgot-password');
    Route::get('admin/reset-password/{token}', [AdminForgotPasswordController::class, 'showResetPasswordForm'])->name('admin.reset.password.form');
    Route::post('admin/reset-password', [AdminForgotPasswordController::class, 'resetPassword'])->name('admin.reset.password');
    
    // Registration and Login Routes
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('email/verify-notice', function () {
        return view('admin.auth.verify-notice');
    })->middleware('guest:admin')->name('verification.notice');
    // Email Verification Routes
    Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware(['auth:admin', 'signed']) // Use the 'signed' middleware
        ->name('verification.verify');

    // Resend Verification Email Route
    Route::post('email/resend', [VerificationController::class, 'resend'])
        ->middleware('auth:admin') // Ensure the user is authenticated before accessing
        ->name('verification.resend');
});

Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/admin/insights/reports', [InsightController::class, 'listReports'])->name('admin.insight.list_reports');
    Route::post('/admin/insight/reports/delete/{fileName}', [InsightController::class, 'deleteReport'])->name('reports.delete');

    Route::get('/admin/farmers', [AdminController::class, 'index'])->name('admin.farmers');
    Route::delete('/admin/farmers/{id}', [AdminController::class, 'deleteFarmer'])->name('farmers.delete');

    // Route for viewing reports
    Route::get('/admin/farmers/{id}/report', [AdminController::class, 'viewReport'])->name('admin.report');
    // Route for downloading PDFs
    Route::get('/admin/farmers/{id}/download-pdf', [AdminController::class, 'downloadPDF'])->name('admin.download-pdf');
    
    Route::resource('supplies', SupplyController::class);
    Route::resource('equipment', EquipmentController::class);
    Route::resource('insight', InsightController::class);
    Route::resource('existingFarmers', ExistingFarmerController::class);
    Route::resource('insight', InsightController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin/profile', [AdminProfileController::class, 'show'])->name('admin.profile.show');
    Route::get('/admin/profile/edit', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::post('/admin/profile/update', [AdminProfileController::class, 'update'])->name('admin.profile.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
                Route::prefix('admin')->namespace('Admin')->group(function () {
                    Route::get('/insights', [InsightController::class, 'index'])->name('admin.insights.index');
                    Route::get('/insights/getManagementData', [InsightController::class, 'getManagementData']);
                    Route::get('/insights/getFarmerData', [InsightController::class, 'getFarmerData']);
                    Route::get('/insights/getPdfReportsData', [InsightController::class, 'getPdfReportsData']);
                });
    Route::get('/admin/insight/report', [InsightController::class, 'createReport'])->name('admin.insight.report');

 
    Route::get('/admin/supply-requests', [AdminApprovalController::class, 'index'])->name('admin.approval.index');
    Route::post('/admin/supply-requests/{id}/approve', [AdminApprovalController::class, 'approveRequest'])->name('admin.approval.approve');
    Route::post('/admin/supply-requests/{id}/reject', [AdminApprovalController::class, 'rejectRequest'])->name('admin.approval.reject');
    Route::post('/admin/approval/release/{id}', [AdminApprovalController::class, 'markAsReleased'])->name('admin.approval.release');
    Route::get('/admin/history-records', [AdminApprovalController::class, 'historyRecords'])->name('admin.history-records');

    Route::get('/admin/borrow-requests', [AdminEquipmentApprovalController::class, 'index'])->name('admin.equipment.approval.index');
    Route::post('/admin/borrow-requests/{id}/approve', [AdminEquipmentApprovalController::class, 'approveRequest'])->name('admin.equipment.approval.approve');
    Route::post('/admin/borrow-requests/{id}/reject', [AdminEquipmentApprovalController::class, 'rejectRequest'])->name('admin.equipment.approval.reject');
    Route::post('/admin/borrow-requests/{id}/release', [AdminEquipmentApprovalController::class, 'markAsReleased'])->name('admin.borrow.release');
    Route::post('/admin/equipment/approval/return/{id}', [AdminEquipmentApprovalController::class, 'returnEquipment'])->name('admin.equipment.approval.return');
    Route::get('admin/history-records-borrowed', [AdminEquipmentApprovalController::class, 'historyRecords'])->name('admin.history-records-borrowed');

    Route::get('pdfs', [AdminController::class, 'listGeneratedReports'])->name('listReports');
    Route::get('list-reports', [AdminController::class, 'listGeneratedReports'])->name('listGeneratedReports');
    Route::get('admin/download/{fileName}', [AdminController::class, 'downloadReport'])->name('downloadReport');
    Route::delete('/admin/reports/delete/{fileName}', [AdminController::class, 'deleteReport'])->name('admin.deleteReport');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications/clear-all', [NotificationController::class, 'clearAll'])->name('notifications.clearAll');
    Route::delete('/notifications/{id}/delete', [NotificationController::class, 'deleteNotification'])->name('notifications.delete');

    Route::get('/admin/help', [AdminHelpController::class, 'index'])->name('admin.help');

    Route::post('/admin/equipment/restore', [EquipmentController::class, 'restore'])->name('equipment.restore');
    Route::delete('/admin/equipment/{id}/force-delete', [EquipmentController::class, 'forceDelete'])
    ->name('admin.equipment.forceDelete');

    Route::post('/admin/supplies/restore', [SupplyController::class, 'restore'])->name('supplies.restore');
    Route::delete('/admin/supplies/{supply}/force', [SupplyController::class, 'forceDelete'])
    ->name('admin.supplies.forceDelete');


});
 
