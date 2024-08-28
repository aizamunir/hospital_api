<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\DiagnosticTest;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activitylog = ActivityLog::all();

        if(count($activitylog) > 0) {
            return response()->json(
                [
                    'message' => count($activitylog) . ' activity logs found.',
                    'status' => 1,
                    'data' => $activitylog
                ], 200
            );
        }
        else {
            return response()->json(
                [
                    'message' => count($activitylog) . ' activity logs found.',
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
            'patient_id' => ['required'],
            'doctor_id' => ['required'],
            'date' => ['required'],
            'time' => ['required']
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(), 400);
        }

        $data = [
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'remarks' => $request->remarks,
            'date' => $request->date,
            'time' => $request->time,
        ];
 
        DB::beginTransaction();

        try{

            $activitylog = ActivityLog::create($data);
            DB::commit();

            return response()->json([
                'message' => 'Activity Logs Added Succesfully.'
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
        $activitylog = ActivityLog::find($activity_log_id);

        if(is_null($activitylog)) {
            $response = [
                'message' => 'Activity Log not found.',
                'status' => 0
            ];
        }
        else{
            $response = [
                'message' => 'Activity Log found.',
                'status' => 1,
                'data' => $activitylog
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
        $activitylog = ActivityLog::find($id);
        if(is_null($activitylog)) {
            

            return response()->json([
                'message' => 'Activity Log not found',
                'status' => 0
            ], 404);
            
        }
        else{

            DB::beginTransaction();

            try{
                $diagnostictest->patient_id = $request['patient_id'];
                $diagnostictest->doctor_id = $request['doctor_id'];
                $diagnostictest->remarks = $request['remarks'];
                $diagnostictest->date = $request['date'];
                $diagnostictest->time = $request['time'];
                $diagnostictest->save();

                DB::commit();

                return response()->json([
                    'message' => 'ACtivity Log updated Successfully.',
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
        $activity_log = ActivityLog::find($id);

        if(is_null($activity_log) > 0) {
            $response = [
                'message' => 'Activity Log not found',
                'status' => 0
            ];

            $respCode = 404;
        }
        else{
            DB::beginTransaction();

            try {
                $diagnostictest -> delete();
                DB::commit();

                $response =[
                    'message' => 'Activity Log deleted successfully.',
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
    
}
