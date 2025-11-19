<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CompanySupervisorController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.dashboard');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home.dashboard');
});


Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    // User Management
    Route::get('/add_user', [AdminController::class, 'add_user'])->name('admin.add_user');
    Route::get('/view_user', [AdminController::class, 'view_users'])->name('admin.view_users');
    Route::post('/upload_user', [AdminController::class, 'upload_user'])->name('admin.upload_user');
    Route::get('/delete_user/{id}', [AdminController::class, 'delete_user'])->name('admin.delete_user');
    Route::get('/edit_user/{id}', [AdminController::class, 'edit_user'])->name('admin.edit_user');
    Route::post('/update_user/{id}', [AdminController::class, 'update_user'])->name('admin.update_user');
    // Student Management
    Route::get('/add_student', [AdminController::class, 'add_student'])->name('admin.add_student');
    Route::post('/upload_student', [AdminController::class, 'upload_student'])->name('admin.upload_student');
    Route::get('/view_students', [AdminController::class, 'view_students'])->name('admin.view_students');
    Route::get('/edit_student/{id}', [AdminController::class, 'edit_student'])->name('admin.edit_student');
    Route::post('/update_student/{id}', [AdminController::class, 'update_student'])->name('admin.update_student');
    Route::get('/delete_student/{id}', [AdminController::class, 'delete_student'])->name('admin.delete_student');
    // Lecturer Management
    Route::get('/add_lecturer', [AdminController::class, 'add_lecturer'])->name('admin.add_lecturer');
    Route::get('/view_lecturers', [AdminController::class, 'view_lecturers'])->name('admin.view_lecturers');
    Route::post('/upload_lecturer', [AdminController::class, 'upload_lecturer'])->name('admin.upload_lecturer');
    Route::get('/edit_lecturer/{id}', [AdminController::class, 'edit_lecturer'])->name('admin.edit_lecturer');
    Route::post('/update_lecturer/{id}', [AdminController::class, 'update_lecturer'])->name('admin.update_lecturer');
    Route::get('/delete_lecturer/{id}', [AdminController::class, 'delete_lecturer'])->name('admin.delete_lecturer');

    // Company Management
    Route::get('/add_company', [AdminController::class, 'add_company'])->name('admin.add_company');
    Route::get('/view_companies', [AdminController::class, 'view_companies'])->name('admin.view_companies');
    Route::post('/upload_company', [AdminController::class, 'upload_company'])->name('admin.upload_company');
    Route::get('/edit_company/{id}', [AdminController::class, 'edit_company'])->name('admin.edit_company');
    Route::post('/update_company/{id}', [AdminController::class, 'update_company'])->name('admin.update_company');
    Route::get('/delete_company/{id}', [AdminController::class, 'delete_company'])->name('admin.delete_company');

    // Company Supervisor Management
    Route::get('/add_supervisor', [AdminController::class, 'add_supervisor'])->name('admin.add_supervisor');
    Route::get('/view_supervisors', [AdminController::class, 'view_supervisors'])->name('admin.view_supervisors');
    Route::post('/upload_supervisor', [AdminController::class, 'upload_supervisor'])->name('admin.upload_supervisor');
    Route::get('/edit_supervisor/{id}', [AdminController::class, 'edit_supervisor'])->name('admin.edit_supervisor');
    Route::post('/update_supervisor/{id}', [AdminController::class, 'update_supervisor'])->name('admin.update_supervisor');
    Route::get('/delete_supervisor/{id}', [AdminController::class, 'delete_supervisor'])->name('admin.delete_supervisor');

    // Department Management
    Route::get('/add_department', [AdminController::class, 'add_department'])->name('admin.add_department');
    Route::get('/view_departments', [AdminController::class, 'view_departments'])->name('admin.view_departments');
    Route::post('/upload_department', [AdminController::class, 'upload_department'])->name('admin.upload_department');
    Route::get('/edit_department/{id}', [AdminController::class, 'edit_department'])->name('admin.edit_department');
    Route::post('/update_department/{id}', [AdminController::class, 'update_department'])->name('admin.update_department');
    Route::get('/delete_department/{id}', [AdminController::class, 'delete_department'])->name('admin.delete_department');

    // Course Management
    Route::get('/add_course', [AdminController::class, 'add_course'])->name('admin.add_course');
    Route::get('/view_courses', [AdminController::class, 'view_courses'])->name('admin.view_courses');
    Route::post('/upload_course', [AdminController::class, 'upload_course'])->name('admin.upload_course');
    Route::get('/edit_course/{id}', [AdminController::class, 'edit_course'])->name('admin.edit_course');
    Route::post('/update_course/{id}', [AdminController::class, 'update_course'])->name('admin.update_course');
    Route::get('/delete_course/{id}', [AdminController::class, 'delete_course'])->name('admin.delete_course');

    // Activity Management
    Route::get('/add_activity', [AdminController::class, 'add_activity'])->name('admin.add_activity');
    Route::get('/view_activities', [AdminController::class, 'view_activities'])->name('admin.view_activities');
    Route::post('/upload_activity', [AdminController::class, 'upload_activity'])->name('admin.upload_activity');
    Route::get('/edit_activity/{id}', [AdminController::class, 'edit_activity'])->name('admin.edit_activity');
    Route::post('/update_activity/{id}', [AdminController::class, 'update_activity'])->name('admin.update_activity');
    Route::get('/delete_activity/{id}', [AdminController::class, 'delete_activity'])->name('admin.delete_activity');

});


// Route::prefix('lecturer')->middleware(['auth', 'lecturer'])->group(function () {
//     Route::get('/dashboard', [LecturerController::class, 'index'])->name('lecturer.dashboard');

// });

// Lecturer Routes
Route::prefix('lecturer')->middleware(['auth', 'lecturer'])->group(function () {
    Route::get('/dashboard', [LecturerController::class, 'index'])->name('lecturer.dashboard');

    // Students
    Route::get('/students', [LecturerController::class, 'view_students'])->name('lecturer.students');
    Route::get('/student/{id}', [LecturerController::class, 'student_details'])->name('lecturer.student.details');

    // Activities
    Route::get('/activities', [LecturerController::class, 'view_activities'])->name('lecturer.activities');
    Route::get('/activity/{id}', [LecturerController::class, 'activity_details'])->name('lecturer.activity.details');
    Route::post('/activity/{id}/approve', [LecturerController::class, 'approve_activity'])->name('lecturer.activity.approve');
    Route::post('/activity/{id}/reject', [LecturerController::class, 'reject_activity'])->name('lecturer.activity.reject');

    // Comments
    Route::post('/activity/{activity_id}/comment', [LecturerController::class, 'add_comment'])->name('lecturer.comment.add');
    Route::delete('/comment/{id}', [LecturerController::class, 'delete_comment'])->name('lecturer.comment.delete');

    // Reports
    Route::get('/reports', [LecturerController::class, 'view_reports'])->name('lecturer.reports');
    Route::get('/report/{student_id}', [LecturerController::class, 'view_report'])->name('lecturer.report.view');
    Route::get('/report/{student_id}/download', [LecturerController::class, 'download_report'])->name('lecturer.report.download');

    // Grading
    Route::get('/grade-students', [LecturerController::class, 'grade_students'])->name('lecturer.grade.students');
    Route::post('/grade/{student_id}', [LecturerController::class, 'submit_grade'])->name('lecturer.grade.submit');
    Route::get('/grade/{student_id}/edit', [LecturerController::class, 'edit_grade'])->name('lecturer.grade.edit');
    Route::put('/grade/{student_id}', [LecturerController::class, 'update_grade'])->name('lecturer.grade.update');
});





// Company Supervisor Routes
Route::prefix('supervisor')->middleware(['auth', 'supervisor'])->group(function () {
    Route::get('/dashboard', [CompanySupervisorController::class, 'index'])->name('supervisor.dashboard');

    // Students
    Route::get('/students', [CompanySupervisorController::class, 'view_students'])->name('supervisor.students');
    Route::get('/student/{id}', [CompanySupervisorController::class, 'student_details'])->name('supervisor.student.details');
    Route::get('/student/{student_id}/progress', [CompanySupervisorController::class, 'student_progress'])->name('supervisor.student.progress');

    // Activities
    Route::get('/activities', [CompanySupervisorController::class, 'view_activities'])->name('supervisor.activities');
    Route::get('/activity/{id}', [CompanySupervisorController::class, 'activity_details'])->name('supervisor.activity.details');

    // Comments
    Route::post('/activity/{activity_id}/comment', [CompanySupervisorController::class, 'add_comment'])->name('supervisor.comment.add');
    Route::get('/comment/{id}/edit', [CompanySupervisorController::class, 'edit_comment'])->name('supervisor.comment.edit');
    Route::put('/comment/{id}', [CompanySupervisorController::class, 'update_comment'])->name('supervisor.comment.update');
    Route::delete('/comment/{id}', [CompanySupervisorController::class, 'delete_comment'])->name('supervisor.comment.delete');
    Route::get('/my-comments', [CompanySupervisorController::class, 'my_comments'])->name('supervisor.comments');

    // Evaluation
    Route::post('/student/{student_id}/evaluate', [CompanySupervisorController::class, 'submit_evaluation'])->name('supervisor.student.evaluate');
});
// Route::prefix('student')->middleware(['auth'])->group(function () {
//     Route::get('/dashboard', [StudentController::class, 'index'])->name('student.dashboard');
// });

Route::middleware(['auth', 'student'])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'index'])->name('student.dashboard');
    Route::get('/student/profile', [StudentController::class, 'profile'])->name('student.profile');
    Route::get('/student/activities', [StudentController::class, 'activities'])->name('student.activities');
    Route::get('/student/add-activity', [StudentController::class, 'addActivity'])->name('student.add-activity');
    Route::post('/student/store-activity', [StudentController::class, 'storeActivity'])->name('student.store-activity');
    Route::get('/student/edit-activity/{id}', [StudentController::class, 'editActivity'])->name('student.edit-activity');
    Route::post('/student/update-activity/{id}', [StudentController::class, 'updateActivity'])->name('student.update-activity');
    Route::get('/student/delete-activity/{id}', [StudentController::class, 'deleteActivity'])->name('student.delete-activity');
    Route::get('/student/reports', [StudentController::class, 'reports'])->name('student.reports');
    Route::post('/student/upload-report', [StudentController::class, 'uploadReport'])->name('student.upload-report');
});
require __DIR__.'/auth.php';
