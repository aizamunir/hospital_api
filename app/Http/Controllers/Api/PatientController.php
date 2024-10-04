<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Patient;
use App\Http\Resources\PatientResource;
use App\Models\ActivityLog;
use Carbon\Carbon;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$patients = Patient::all();
        $patients = Patient::with('doctor')->get();

        if(count($patients) > 0) {
            $response = [
                'message' => count($patients) . " patients found.",
                'status' => 1,
                'data' => PatientResource::collection($patients)
            ];
        }
        else {
            $response = [
                'message' => count($patients) . ' patients found.',
                'status' => 0
            ];
        }

        return response()->json($response, 200);
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
        
        $validator= Validator::make($request->all(), [
            'name' => ['required'],
            'age' => ['required'],
            'phn_num' => ['required'], ['regex:/^03\d{9,}$/'],
            'disease' => ['required'],
            'gender' => ['required'],
            'height' => ['required'],
            'weight' => ['required'],
            'attendee' => ['required'],
            'doctor_id' => ['required'],
            'status' => ['required']
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(), 400);
        }

        $data = [
            'name' => $request->name,
            'age' => $request->age,
            'phn_num' => $request->phn_num,
            'disease' => $request->disease,
            'gender' => $request->gender,
            'height' => $request->height,
            'weight' => $request->weight,
            'attendee' => $request->attendee,
            'doctor_id' => $request->doctor_id,
            'status' => $request->status
        ];

        DB::beginTransaction();

        try{

            $patient = Patient::create($data);
            DB::commit();
            
            //Activity work started
            $date = Carbon::now()->toDateString();
            $time = Carbon::now()->toTimeString();
            $last_id = $patient->id;

            $activity_log = new ActivityLog();

            $activity_log->patient_id = '1';
            //$activity_log->doctor_id = '1';
            $activity_log->remarks = 'patient admitted';
            $activity_log->date = $date;
            $activity_log->time = $time;
            $activity_log->save();

            DB::commit();
            //Activity work ended

            return response()->json([
                'message' => 'Patient Registered Succesfully.'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                //'message' => 'Internal Servor Error.'
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $patient = Patient::find($id);

        if(is_null($patient)) {
            $response = [
                'message' => 'Patients not found.',
                'status' => 0
            ];
        }
        else{
            $response = [
                'message' => 'Patient found.',
                'status' => 1,
                'data' => $patient
            ];
        }

        return response()->json($response, 200);
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
        $patient = Patient::find($id);
        if(is_null($patient) > 0) {
            return response()->json(
                [
                    'message' => 'Patient not found.',
                    'status' => 0
                ], 404
            );

        }
        else{

            DB::beginTransaction();

            try{
                $patient -> name = $request['name'];
                $patient -> age = $request['age'];
                $patient -> phn_num = $request['phn_num'];
                $patient -> disease = $request['disease'];
                $patient -> gender = $request['gender'];
                $patient -> gender = $request['height'];
                $patient -> gender = $request['weight'];
                $patient -> gender = $request['attendee'];
                $patient -> doctor_id = $request['doctor_id'];
                $patient -> status = $request['status'];

                $patient->save();
                
                DB::commit();

                //Activity work started
                $date = Carbon::now()->toDateString();
                $time = Carbon::now()->toTimeString();
                $last_id = $patient->id;

                $activity_log = new ActivityLog();

                $activity_log->patient_id = $id;
                //$activity_log->doctor_id = '1';
                $activity_log->remarks = 'patient updated';
                $activity_log->date = $date;
                $activity_log->time = $time;
                $activity_log->save();

                DB::commit();
                //Activity work ended

                return response()->json(
                    [
                        'message' => 'Patient updated succesfully',
                        'status' => 1   
                    ], 200 
                );

        
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json(
                    [
                        'message' => 'Internal Servor Error',
                        'error' => $e->getMessage()
                    ], 500
                );

            }

        }

        return response()->json($response, $respcode);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $patient = Patient::find($id);

        if(is_null($patient) > 0) {
            $response = [
                'message' => 'Patient not found',
                'status' => 0
            ];

            $respCode = 404;
        }
        else{
            DB::beginTransaction();

            try {
                $patient -> delete();
                DB::commit();

                //Activity work started
                $date = Carbon::now()->toDateString();
                $time = Carbon::now()->toTimeString();
                $last_id = $patient->id;

                $activity_log = new ActivityLog();

                $activity_log->patient_id = $id;
                //$activity_log->doctor_id = '1';
                $activity_log->remarks = 'patient deleted';
                $activity_log->date = $date;
                $activity_log->time = $time;
                $activity_log->save();

                DB::commit();
                //Activity work ended

                $response =[
                    'message' => 'Patient deleted successfully.',
                    'status' => 1
                ];

                $respCode = 200;

            } catch (\Exception $e) {
                DB::rollback;

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
