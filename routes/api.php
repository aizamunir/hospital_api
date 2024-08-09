<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\PrescriptionController;
use App\Http\Controllers\Api\DiagnosticTestController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\DoctorSessionsController;
use App\Http\Controllers\Api\MedicineController;
use App\Http\Controllers\Api\TestCategoryController;


//PATIENTS

Route::post('patient/store', [PatientController::class,'store']);

Route::get('patients', [PatientController::class,'index']);

Route::delete('patient/delete/{patient_id}', [PatientController::class,'destroy']);

Route::put('patient/update/{patient_id}', [PatientController::class,'update']);


//DOCTORS

Route::post('doctor/store', [DoctorController::class, 'store']);

Route::get('doctors', [DoctorController::class, 'index']);

Route::put('doctor/update/{doctor_id}', [DoctorController::class, 'update']);

Route::delete('doctor/delete/{doctor_id}', [DoctorController::class, 'destroy']);


//PRESCRIPTION

Route::post('prescription/store', [PrescriptionController::class,'store']);

Route::get('prescriptions', [PrescriptionController::class, 'index']);

Route::put('prescription/update/{prescription_id}', [PrescriptionController::class,'update']);

Route::delete('prescription/delete/{prescription_id}', [PrescriptionController::class,'destroy']);


//DIAGNOSTIC TEST LIST

Route::post('diagnostictest/store', [DiagnosticTestController::class,'store']);

Route::get('diagnostictests', [DiagnosticTestController::class, 'index']);

Route::put('diagnostictest/update/{diagnostictest_id}', [DiagnosticTestController::class,'update']);

Route::delete('diagnostictest/delete/{diagnostictest_id}', [DiagnosticTestController::class,'destroy']);

//SCHEDULE

Route::post('schedule/store', [ScheduleController::class,'store']);

Route::get('schedules', [ScheduleController::class, 'index']);

Route::put('schedule/update/{schedule_id}', [ScheduleController::class,'update']);

Route::delete('schedule/delete/{schedule_id}', [ScheduleController::class,'destroy']);

//SERVICE

Route::post('service/store', [ServiceController::class,'store']);

Route::get('services', [ServiceController::class, 'index']);

Route::put('service/update/{services_id}', [ServiceController::class,'update']);

Route::delete('service/delete/{service_id}', [ServiceController::class,'destroy']);

//DOCTOR SESSON

Route::post('doctor_sessions/store', [DoctorSessionsController::class,'store']);

Route::get('doctor_sessions', [DoctorSessionsController::class, 'index']);

Route::put('doctor_sessions/update/{doctor_sessions_id}', [DoctorSessionsController::class,'update']);

Route::delete('doctor_sessions/delete/{doctor_sessions_id}', [DoctorSessionsController::class,'destroy']);

//MEDICINE

Route::post('medicine/store', [MedicineController::class,'store']);

Route::get('medicines', [MedicineController::class, 'index']);

Route::put('medicine/update/{medicine_id}', [MedicineController::class,'update']);

Route::delete('medicine/delete/{medicine_id}', [MedicineController::class,'destroy']);


//TEST CATEGORY 

Route::post('test_category/store', [TestCategoryController::class,'store']);

Route::get('test_categorys', [TestCategoryController::class, 'index']);

Route::put('test_category/update/{test_category_id}', [TestCategoryController::class,'update']);

Route::delete('test_category/delete/{test_category_id}', [TestCategoryController::class,'destroy']);
