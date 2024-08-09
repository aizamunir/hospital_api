<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Service;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $service = Service::all();

        if(count($service) > 0) {
            return response()->json(
                [
                    'message' => count($service) . ' service found.',
                    'status' => 1,
                    'data' => $service
                ], 200
            );
        }
        else {
            return response()->json(
                [
                    'message' => count($service) . ' service not found.',
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
            'name' => ['required'],
            'fees' => ['required'],
            'doctor_id' => ['required'],
            'duration' => ['required'],
            'status' => ['required'],
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(), 400);
        }

        $data = [
            'name' => $request->name,
            'fees' => $request->fees,
            'doctor_id' => $request->doctor_id,
            'duration' => $request->duration,
            'status' => $request->status
        ];

        DB::beginTransaction();

        try{

            $service = Service::create($data);
            DB::commit();
            
            return response()->json([
                'message' => 'Service Added Succesfully.'
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
        $service = Service::find($service_id);

        if(is_null($service)) {
            $response = [
                'message' => 'Services not found.',
                'status' => 0
            ];
        }
        else{
            $response = [
                'message' => 'Services found.',
                'status' => 1,
                'data' => $service
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
        $service = Service::find($id);
        if(is_null($service)) {
            $response = [
                'message' => 'Service not found',
                'status' => 0
            ];

            $respCode = 404;
        }
        else{

            DB::beginTransaction();

            try {

                $service->name = $request['name'];
                $service->fees = $request['fees'];
                $service->doctor_id = $request['doctor_id'];
                $service->duration = $request['duration'];
                $service->status = $request['status'];
                $service->save();

                DB::commit();

                return response()->json([
                    'message' => 'Service updated successfully',
                    'status' => 1
                 ], 200);

            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                   'message' => $e->getMessage(),
                   'status' => 0
                ], 500);
            }
                
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service = Service::find($id);

        if(is_null($service) > 0) {
            $response = [
                'message' => 'Service not found',
                'status' => 0
            ];

            $respCode = 404;
        }
        else{
            DB::beginTransaction();

            try {
                $service -> delete();
                DB::commit();

                $response =[
                    'message' => 'Service deleted successfully.',
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
