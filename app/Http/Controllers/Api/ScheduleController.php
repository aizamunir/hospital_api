<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Schedule;
use App\Models\ActivityLog;
use Carbon\Carbon;
use App\Http\Resources\SchedueleResource;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$schedules = Schedule::all();

        $schedule = Schedule::with('doctor')->get();

        if(count($schedules) > 0) {
            try {
                return response()->json(
                    [
                        'message' => count($schedules) . ' schedules found.',
                        'status' => 1,
                        //'data' => ScheduleResource::collection($schedule)
                        'data' => $schedules
                    ], 200
                );
            } catch (\Exception $e) {

                return response()->json([
                    'message' => $e->getMessage()
                ], 200);
                }
        }
        else {
            return response()->json(
                [
                    'message' => count($schedules) . ' schedules found.',
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
            'date' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'description' => ['required']

        ]);

        if($validator->fails()){
            return response()->json($validator->messages(), 400);
        }

        $data = [
            'doctor_id' => $request->doctor_id,
            'patient_id' => $request->patient_id,
            'date' => $request->date,
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time
        ];

        DB::beginTransaction();

        try{

            $schedule = Schedule::create($data);
            DB::commit();
            
                 
                ///Activity work started
                $date = Carbon::now()->toDateString();
                $time = Carbon::now()->toTimeString();
                $last_id = $schedule->id;

                $activity_log = new ActivityLog();

                $activity_log->patient_id = $request->patient_id;
                $activity_log->doctor_id = $request->doctor_id;
                $activity_log->remarks = 'schedule added';
                $activity_log->date = $date;
                $activity_log->time = $time;
                $activity_log->save();

                DB::commit();
                //Activity work ended

            return response()->json([
                'message' => 'Schedule Added Succesfully.'
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
        $schedule = Schedule::find($schedule_id);

        if(is_null($schedule)) {
            $response = [
                'message' => 'Schedules not found.',
                'status' => 0
            ];
        }
        else{
            $response = [
                'message' => 'Schedules found.',
                'status' => 1,
                'data' => $schedule
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
        $schedule = Schedule::find($id);
        if(is_null($schedule)) {
            $response = [
                'message' => 'Schedule not found',
                'status' => 0
            ];

            $respCode = 404;
        }
        else{

            DB::beginTransaction();

            try {

                $schedule->doctor_id = $request['doctor_id'];
                $schedule->patient_id = $request['patient_id'];
                $schedule->description = $request['description'];
                $schedule->start_time = $request['end_time'];
                $schedule->save();

                DB::commit();

                     
                ///Activity work started
                $date = Carbon::now()->toDateString();
                $time = Carbon::now()->toTimeString();
                $last_id = $schedule->id;

                $activity_log = new ActivityLog();

                $activity_log->patient_id = $request->patient_id;
                $activity_log->doctor_id = $request->doctor_id;
                $activity_log->remarks = 'schedule updated';
                $activity_log->date = $date;
                $activity_log->time = $time;
                $activity_log->save();

                DB::commit();
                //Activity work ended


            } catch (\Exception $e) {
                DB::rollBack();
                $schedule = null;
                return response()->json([
                   'message' => $e->getMessage()
                ], 200);
                }
                
                if ($schedule != null) {
                $response = [
                    'message' => 'Schedule updated successfully',
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
        $schedule = Schedule::find($id);

        if(is_null($schedule) > 0) {
            $response = [
                'message' => 'Schedule not found',
                'status' => 0
            ];

            $respCode = 404;
        }
        else{
            DB::beginTransaction();

            try {
                $schedule -> delete();
                DB::commit();

                     
                ///Activity work started
                $date = Carbon::now()->toDateString();
                $time = Carbon::now()->toTimeString();
                $last_id = $schedule->id;

                $activity_log = new ActivityLog();

                $activity_log->patient_id = $request->patient_id;
                $activity_log->doctor_id = $request->doctor_id;
                $activity_log->remarks = 'schedule deleted';
                $activity_log->date = $date;
                $activity_log->time = $time;
                $activity_log->save();

                DB::commit();
                //Activity work ended


                $response =[
                    'message' => 'Schedule deleted successfully.',
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
