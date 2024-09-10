<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Doctor;
use App\Models\Patients;
use App\Models\ActivityLog;
use Carbon\Carbon;


class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::all();

        if(count($doctors) > 0) {
            return response()->json(
                [
                    'message' => count($doctors) . ' doctors found.',
                    'status' => 1,
                    'data' => $doctors
                ], 200
            );
        }
        else {
            return response()->json(
                [
                    'message' => count($doctors) . ' doctors found.',
                    'status' => 0,
                ], 404
            );
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'phn_num' => ['required', 'regex:/^03\d{9,}$/'],
            'speciality' => ['required'],
            'gender'=>['required']
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(), 400);
        }
        else{
            $data = [
                'name' => $request->name,
                'age' => $request->age,
                'phn_num' => $request->phn_num,
                'speciality' => $request->speciality,
                'gender' => $request->gender,
                'salary' => $request->salary
            ];

            DB::beginTransaction();

            try {

                $doctor = Doctor::create($data);
                DB::commit();

                //Activity work started
                $date = Carbon::now()->toDateString();
                $time = Carbon::now()->toTimeString();
                $last_id = $doctor->id;

                $activity_log = new ActivityLog();

                //$activity_log->patient_id = $id;
                $activity_log->doctor_id = $last_id;
                $activity_log->remarks = 'doctor added';
                $activity_log->date = $date;
                $activity_log->time = $time;
                $activity_log->save();

                DB::commit();
                //Activity work ended

            } catch(\Exception $e) {
                DB::rollBack();
                $doctor = null;
                return response()->json([
                    'message' => $e->getMessage()
                ], 200);
            }

            if($doctor!= null) {
                return response()->json([
                    'message' => 'Doctor Registered Successfully.'
                ], 200);
            }
            else{
                return response()->json([
                    'message' => 'Internal Servor Error'
                ],500);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($doctor_id)
    {
        $doctor = Doctor::find($doctor_id);

        if(is_null($doctor)) {
            $response = [
                'message' => 'Doctors not found.',
                'status' => 0
            ];
        }
        else{
            $response = [
                'message' => 'Doctors found.',
                'status' => 1,
                'data' => $doctor
            ];
        }

        return response()->json(response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $doctor = Doctor::find($id);
        if(is_null($doctor)) {
            $response = [
                'message' => 'doctor not found',
                'status' => 0
            ];

            $respCode = 404;
        }
        else{

            DB::beginTransaction();

            try {
                $doctor->name = $request['name'];
                $doctor->age = $request['age'];
                $doctor->phn_num = $request['phn_num'];
                $doctor->speciality = $request['speciality'];
                $doctor->gender = $request['gender'];
                $doctor->salary = $request['salary'];
                $doctor->save();

                DB::commit();

                //Activity work started
                $date = Carbon::now()->toDateString();
                $time = Carbon::now()->toTimeString();
                $last_id = $doctor->id;

                $activity_log = new ActivityLog();

                //$activity_log->patient_id = $id;
                $activity_log->doctor_id = $last_id;
                $activity_log->remarks = 'doctor updated';
                $activity_log->date = $date;
                $activity_log->time = $time;
                $activity_log->save();

                DB::commit();
                //Activity work ended

            } catch (\Exception $e) {
                DB::rollBack();
                $doctor = null;
                return response()->json([
                    'message' => $e->getMessage()
                ], 200);
                }
                
                if ($doctor != null) {
                $response = [
                    'message' => 'Doctor updated successfully',
                    'status' => 1
                    ];

                $respCode = 200;
               }
               else{
                $response =[
                    'message' => 'Internal Server Error',
                    'status' => 0
                ];

                $respCode = 500;

               }
        }

        return response()->json($response, $respCode);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($doctor_id)
    {
        $doctor = Doctor::find($doctor_id);

        if(is_null($doctor) > 0) {
            $response = [
                'message' => 'Doctor not found',
                'status' => 0
            ];

            $respCode = 404;
        }
        else{
            DB::beginTransaction();

            try {
                $doctor -> delete();
                DB::commit();

                //Activity work started
                $date = Carbon::now()->toDateString();
                $time = Carbon::now()->toTimeString();
                $last_id = $doctor->id;

                $activity_log = new ActivityLog();

                //$activity_log->patient_id = $id;
                $activity_log->doctor_id = $last_id;
                $activity_log->remarks = 'doctor deleted';
                $activity_log->date = $date;
                $activity_log->time = $time;
                $activity_log->save();

                DB::commit();
                //Activity work ended

                $response =[
                    'message' => 'Doctor deleted successfully.',
                    'status' => 1
                ];

                $respCode = 200;

            } catch (\Exception $e) {
                DB::rollBack();

                $response = [
                    'message' => 'Internal Server Error',
                    'status' => 0
                ];

                $respCode = 404;
            }

            $respCode = 500;
        }

        return response()->json($response, $respCode);
    }
}
