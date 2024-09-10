<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Medicine;
use App\Models\ActivityLog;
use Carbon\Carbon;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medicine = Medicine::all();

        if(count($medicine) > 0) {
            return response()->json(
                [
                    'message' => count($medicine) . ' medicines found.',
                    'status' => 1,
                    'data' => $medicine
                ], 200
            );
        }
        else {
            return response()->json(
                [
                    'message' => count($medicine) . ' medicines found.',
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
            'mg' => ['required'],
            'company' => ['required']
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(), 400);
        }

        $data = [
            'name' => $request->name,
            'mg' => $request->mg,
            'company' => $request->company
        ];

        DB::beginTransaction();

        try{

            $medicine = Medicine::create($data);
            DB::commit();
            
            return response()->json([
                'message' => 'Medicine Added Succesfully.'
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
        $medicine = Medicine::find($medicine_id);

        if(is_null($medicine)) {
            $response = [
                'message' => 'Medicines not found.',
                'status' => 0
            ];
        }
        else{
            $response = [
                'message' => 'Medicines found.',
                'status' => 1,
                'data' => $medicine
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
        $medicine = Medicine::find($id);
        if(is_null($medicine)) {
            $response = [
                'message' => 'medicine not found',
                'status' => 0
            ];

            $respCode = 404;
        }
        else{

            DB::beginTransaction();

            try {
                $medicine->name = $request['name'];
                $medicine->mg = $request['mg'];
                $medicine->company = $request['company'];
                $medicine->save();

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                $medicine = null;
                return response()->json([
                    'message' => $e->getMessage()
                ], 200);
                }
                
                if ($medicine != null) {
                $response = [
                    'message' => 'Medicine updated successfully',
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
        $medicine = Medicine::find($id);

        if(is_null($medicine) > 0) {
            $response = [
                'message' => 'Medicine not found',
                'status' => 0
            ];

            $respCode = 404;
        }
        else{
            DB::beginTransaction();

            try {
                $medicine -> delete();
                DB::commit();

                $response =[
                    'message' => 'Medicine deleted successfully.',
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
