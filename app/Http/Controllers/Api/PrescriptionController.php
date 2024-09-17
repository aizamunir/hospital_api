<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Prescription;
use App\Models\ActivityLog;
use Carbon\Carbon;
use App\Http\Resources\PrescriptionResource;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prescription = Prescription::all();

        if(count($prescription) > 0) {
            return response()->json(
                [
                    'message' => count($prescription) . ' prescriptions found.',
                    'status' => 1,
                    'data' => $prescription
                ], 200
            );
        }
        else {
            return response()->json(
                [
                    'message' => count($prescription) . ' prescriptions found.',
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
            'patient_id' => ['required'],
            'medicines' => ['required']
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(), 400);
        }

        $data = [
            'doctor_id' => $request->doctor_id,
            'patient_id' => $request->patient_id,
            'medicines' => $request->medicines,
            'description' => $request->description,
            'next_visit' => $request->next_visit
        ];

        DB::beginTransaction();

        try{

            $prescription = Prescription::create($data);
            DB::commit();

            //Activity work started
            $date = Carbon::now()->toDateString();
            $time = Carbon::now()->toTimeString();
            $last_id = $prescription->id;

            $activity_log = new ActivityLog();

            $activity_log->patient_id = $request->patient_id;
            $activity_log->doctor_id = $request->doctor_id;
            $activity_log->remarks = 'precription added';
            $activity_log->date = $date;
            $activity_log->time = $time;
            $activity_log->save();

            DB::commit();
            //Activity work ended
            
            return response()->json([
                'message' => 'Prescription Added Succesfully.'
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
        $prescription = Prescription::find($prescription_id);

        if(is_null($prescription)) {
            $response = [
                'message' => 'Prescriptions not found.',
                'status' => 0
            ];
        }
        else{
            $response = [
                'message' => 'Precriptions found.',
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
    public function update(Request $request, $id)
    {
        $prescription = Prescription::find($id);
        if(is_null($prescription)) {
            $response = [
                'message' => 'Prescription not found',
                'status' => 0
            ];

            $respCode = 404;
        }
        else{

            DB::beginTransaction();

            try {

                $prescription->doctor_id = $request['doctor_id'];
                $prescription->patient_id = $request['patient_id'];
                $prescription->medicines = $request['medicines'];
                $prescription->description = $request['description'];
                $prescription->next_visit = $request['next_visit'];
                $prescription->save();

                DB::commit();

                ///Activity work started
                $date = Carbon::now()->toDateString();
                $time = Carbon::now()->toTimeString();
                $last_id = $prescription->id;

                $activity_log = new ActivityLog();

                $activity_log->patient_id = $request->patient_id;
                $activity_log->doctor_id = $request->doctor_id;
                $activity_log->remarks = 'precription updated';
                $activity_log->date = $date;
                $activity_log->time = $time;
                $activity_log->save();

                DB::commit();
                //Activity work ended

            } catch (\Exception $e) {
                DB::rollBack();
                $prescription = null;
                return response()->json([
                   'message' => $e->getMessage()
                ], 200);
                }
                
                if ($prescription != null) {
                $response = [
                    'message' => 'Prescription updated successfully',
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
    public function destroy($id)
    {
        $prescription = Prescription::find($id);

        if(is_null($prescription) > 0) {
            $response = [
                'message' => 'Prescription not found',
                'status' => 0
            ];

            $respCode = 404;
        }
        else{
            DB::beginTransaction();

            try {
                $prescription -> delete();
                DB::commit();

                
                ///Activity work started
                $date = Carbon::now()->toDateString();
                $time = Carbon::now()->toTimeString();
                $last_id = $prescription->id;

                $activity_log = new ActivityLog();

                $activity_log->patient_id = $request->patient_id;
                $activity_log->doctor_id = $request->doctor_id;
                $activity_log->remarks = 'precription deleted';
                $activity_log->date = $date;
                $activity_log->time = $time;
                $activity_log->save();

                DB::commit();
                //Activity work ended


                $response =[
                    'message' => 'Prescription deleted successfully.',
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

    public function getPatientPrescription(string $patient_id)
    {
        $prescription = Prescription::where('patient_id', $patient_id)->with(['patient', 'doctor'])->get();

        if(is_null($prescription)) {
            $response = [
                'message' => 'Prescription not found.',
                'status' => 0
            ];
        }
        else{
            $response = [
                'message' => 'Prescription found.',
                'status' => 1,
                'data' => PrescriptionResource::collection($prescription)
            ];
        }

        return response()->json($response, 200);
    }
}
