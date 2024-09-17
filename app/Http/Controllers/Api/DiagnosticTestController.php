<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\DiagnosticTest;
use App\Models\ActivityLog;
use Carbon\Carbon;
use App\Http\Resources\DiagnosticTestResource;

class DiagnosticTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $diagnostictests = DiagnosticTest::all();

        if(count($diagnostictests) > 0) {
            return response()->json(
                [
                    'message' => count($diagnostictests) . ' diagnostic tests found.',
                    'status' => 1,
                    'data' => $diagnostictests
                ], 200
            );
        }
        else {
            return response()->json(
                [
                    'message' => count($diagnostictests) . ' diagnostic tests found.',
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
            'doctor_id' => ['required'],
            'patient_id' => ['required'],
            'tests' => ['required'],
            'result' => ['required']
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(), 400);
        }

        $data = [
            'doctor_id' => $request->doctor_id,
            'patient_id' => $request->patient_id,
            'tests' => $request->tests,
            'result' => $request->result,
            'description' => $request->description,
        ];

        DB::beginTransaction();

        try{

            $diagnostictest = DiagnosticTest::create($data);
            DB::commit();

            //Activity work started
            $date = Carbon::now()->toDateString();
            $time = Carbon::now()->toTimeString();
            $last_id = $diagnostictest->id;

            $activity_log = new ActivityLog();

            $activity_log->patient_id = $request->patient_id;
            $activity_log->doctor_id = $request->doctor_id;
            $activity_log->remarks = 'diagnostic test added';
            $activity_log->date = $date;
            $activity_log->time = $time;
            $activity_log->save();

            DB::commit();
            //Activity work ended

            return response()->json([
                'message' => 'Diagnostic Tests Added Succesfully.'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Internal Servor Error.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $diagnostictest = DiagnosticTest::find($diagnostictest_id);

        if(is_null($diagnostictest)) {
            $response = [
                'message' => 'Diagnostic Tests not found.',
                'status' => 0
            ];
        }
        else{
            $response = [
                'message' => 'Diagnostic Tests found.',
                'status' => 1,
                'data' => $diagnostictest
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
    public function update(Request $request, string $id)
    {
        $diagnostictest = DiagnosticTest::find($id);
        if(is_null($diagnostictest)) {
            

            return response()->json([
                'message' => 'Diagnostic Test not found',
                'status' => 0
            ], 404);
            
        }
        else{

            DB::beginTransaction();

            try{
                $diagnostictest->doctor_id = $request['doctor_id'];
                $diagnostictest->patient_id = $request['patient_id'];
                $diagnostictest->tests = $request['tests'];
                $diagnostictest->result = $request['result'];
                $diagnostictest->description = $request['description'];
                $diagnostictest->save();

                DB::commit();

                //Activity work started
                $date = Carbon::now()->toDateString();
                $time = Carbon::now()->toTimeString();
                $last_id = $diagnostictest->id;

                $activity_log = new ActivityLog();

                $activity_log->patient_id = $request->patient_id;
                $activity_log->doctor_id = $request->doctor_id;
                $activity_log->remarks = 'diagnostic test added';
                $activity_log->date = $date;
                $activity_log->time = $time;
                $activity_log->save();

                DB::commit();
                //Activity work ended

                return response()->json([
                    'message' => 'Diagnostic Test updated Successfully.',
                    'status' => 1
                ], 200);
                
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json([
                    'message' => 'Internal server Error.',
                    'status' => 0
                ], 500);

                return response()->json([
                    'message' => $e->getMessage()
                ], 200);
                }
                
        }

        return response()->json($response, $respCode);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $diagnostictest = DiagnosticTest::find($id);

        if(is_null($diagnostictest) > 0) {
            $response = [
                'message' => 'Diagnostic Test not found',
                'status' => 0
            ];

            $respCode = 404;
        }
        else{
            DB::beginTransaction();

            try {
                $diagnostictest -> delete();
                DB::commit();

                //Activity work started
                $date = Carbon::now()->toDateString();
                $time = Carbon::now()->toTimeString();
                $last_id = $diagnostictest->id;

                $activity_log = new ActivityLog();

                $activity_log->patient_id = $request->patient_id;
                $activity_log->doctor_id = $request->doctor_id;
                $activity_log->remarks = 'diagnostic test added';
                $activity_log->date = $date;
                $activity_log->time = $time;
                $activity_log->save();

                DB::commit();
                //Activity work ended

                $response =[
                    'message' => 'Diagnostic Test deleted successfully.',
                    'status' => 1
                ];

                $respCode = 200;

            } catch (\Exception $e) {
                DB::rollBack();

                $response = [
                    'message' => 'Internal Server Error',
                    'status' => 0,
                    'message' => $e->getMessage()

                ];

                $respCode = 404;
            }

            $respCode = 500;
        }

        return response()->json($response, $respCode);
    }
        
    public function getPatientDiagnosticTest(string $patient_id)
    {
        $diagnostictest = DiagnosticTest::where('patient_id', $patient_id)->with(['patient', 'doctor'])->get();

        if(is_null($diagnostictest)) {
            $response = [
                'message' => 'Diagnostic Test not found.',
                'status' => 0
            ];
        }
        else{
            $response = [
                'message' => 'Diagnostic Test found.',
                'status' => 1,
                'data' => DiagnosticTestResource::collection($diagnostictest)
            ];
        }

        return response()->json($response, 200);
    }
    
}
