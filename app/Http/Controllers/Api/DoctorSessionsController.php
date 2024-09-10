<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\DoctorSessions;
use App\Models\ActivityLog;
use Carbon\Carbon;

class DoctorSessionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctor_sessions = DoctorSessions::all();

        if(count($doctor_sessions) > 0) {
            return response()->json(
                [
                    'message' => count($doctor_sessions) . ' doctor sessions found.',
                    'status' => 1,
                    'data' => $doctor_sessions
                ], 200
            );
        }
        else {
            return response()->json(
                [
                    'message' => count($doctor_sessions) . ' doctor sessions found.',
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
        $validator= Validator::make($request->all(), [
            'doctor_id' => ['required'],
            'time_slot' => ['required'],
            'days' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['required']
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(), 400);
        }

        $data = [
            'doctor_id' => $request->doctor_id,
            'time_slot' => $request->time_slot,
            'days' => $request->days,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time
        ];

        DB::beginTransaction();

        try{

            $doctor_sessions = DoctorSessions::create($data);
            DB::commit();

            ///Activity work started
            $date = Carbon::now()->toDateString();
            $time = Carbon::now()->toTimeString();
            $last_id = $doctor_sessions->id;

            $activity_log = new ActivityLog();

            $activity_log->doctor_id = $request->doctor_id;
            $activity_log->remarks = 'doctor session updated';
            $activity_log->date = $date;
            $activity_log->time = $time;
            $activity_log->save();

            DB::commit();
            //Activity work ended
            
            return response()->json([
                'message' => 'Doctor Session Added Succesfully.'
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
        $doctor_sessions = DoctorSession::find($doctor_sessions_id);

        if(is_null($doctor_sessions)) {
            $response = [
                'message' => 'Doctor Sessions not found.',
                'status' => 0
            ];
        }
        else{
            $response = [
                'message' => 'Doctor Sessions found.',
                'status' => 1,
                'data' => $prescription
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
    public function update(Request $request, string $id)
    {
        $doctor_sessions = DoctorSessions::find($id);
        if(is_null($doctor_sessions)) {
            $response = [
                'message' => 'Doctor Sessions not found',
                'status' => 0
            ];

            $respCode = 404;
        }
        else{

            DB::beginTransaction();

            try {

                $doctor_sessions->doctor_id = $request['doctor_id'];
                $doctor_sessions->time_slot = $request['time_slot'];
                $doctor_sessions->days = $request['days'];
                $doctor_sessions->start_time = $request['start_time'];
                $doctor_sessions->end_time = $request['end_time'];
                $doctor_sessions->save();

                DB::commit();

                ///Activity work started
                $date = Carbon::now()->toDateString();
                $time = Carbon::now()->toTimeString();
                $last_id = $doctor_sessions->id;

                $activity_log = new ActivityLog();

                $activity_log->doctor_id = $request->doctor_id;
                $activity_log->remarks = 'doctor session updated';
                $activity_log->date = $date;
                $activity_log->time = $time;
                $activity_log->save();

                DB::commit();
                //Activity work ended

            } catch (\Exception $e) {
                DB::rollBack();
                $doctor_sessions = null;
                return response()->json([
                   'message' => $e->getMessage()
                ], 200);
                }
                
                if ($doctor_sessions != null) {
                $response = [
                    'message' => 'Doctor Session updated successfully',
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
    public function destroy(string $id)
    {
        $doctor_sessions = DoctorSessions::find($id);

        if(is_null($doctor_sessions) > 0) {
            $response = [
                'message' => 'Doctor Session not found',
                'status' => 0
            ];

            $respCode = 404;
        }
        else{
            DB::beginTransaction();

            try {
                $doctor_sessions -> delete();
                DB::commit();

                ///Activity work started
                $date = Carbon::now()->toDateString();
                $time = Carbon::now()->toTimeString();
                $last_id = $doctor_sessions->id;

                $activity_log = new ActivityLog();

                $activity_log->doctor_id = $request->doctor_id;
                $activity_log->remarks = 'doctor sessions updated';
                $activity_log->date = $date;
                $activity_log->time = $time;
                $activity_log->save();

                DB::commit();
                //Activity work ended

                $response =[
                    'message' => 'Doctor Session deleted successfully.',
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
