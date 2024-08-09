<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Doctor;

class TestCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $test_category = TestCategory::all();

        if(count($test_category) > 0) {
            return response()->json(
                [
                    'message' => count($test_category) . ' test categorys found.',
                    'status' => 1,
                    'data' => $test_category
                ], 200
            );
        }
        else {
            return response()->json(
                [
                    'message' => count($test_category) . ' test categorys found.',
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
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(), 400);
        }
        else{
            $data = [
                'name' => $request->name
            ];

            DB::beginTransaction();

            try {

                $test_category = TestCategory::create($data);
                DB::commit();
            } catch(\Exception $e) {
                DB::rollBack();
                $test_category = null;
                return response()->json([
                    'message' => $e->getMessage()
                ], 200);
            }

            if($doctor!= null) {
                return response()->json([
                    'message' => 'Test Category Registered Successfully.'
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
    public function show(string $id)
    {
        $test_category = TestCategory::find($test_category_id);

        if(is_null($test_category)) {
            $response = [
                'message' => 'Test Categorys not found.',
                'status' => 0
            ];
        }
        else{
            $response = [
                'message' => 'Test Categorys found.',
                'status' => 1,
                'data' => $test_category
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
        $test_category = TestCategory::find($id);
        if(is_null($test_category)) {
            $response = [
                'message' => 'Test Category not found',
                'status' => 0
            ];

            $respCode = 404;
        }
        else{

            DB::beginTransaction();

            try {
                $test_category->name = $request['name'];
                $test_category->save();

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                $test_category = null;
                return response()->json([
                    'message' => $e->getMessage()
                ], 200);
                }
                
                if ($test_category != null) {
                $response = [
                    'message' => 'Test Category updated successfully',
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
        $test_category = TestCategory::find($test_category_id);

        if(is_null($test_category) > 0) {
            $response = [
                'message' => 'Test Category not found',
                'status' => 0
            ];

            $respCode = 404;
        }
        else{
            DB::beginTransaction();

            try {
                $test_category -> delete();
                DB::commit();

                $response =[
                    'message' => 'Test Category deleted successfully.',
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
