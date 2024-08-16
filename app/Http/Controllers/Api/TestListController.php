<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\TestList;
use App\Models\TestCategory;

class TestListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $test_list = TestList::all();

        if(count($test_list) > 0) {
            return response()->json(
                [
                    'message' => count($test_list) . ' test list found.',
                    'status' => 1,
                    'data' => $test_list
                ], 200
            );
        }
        else {
            return response()->json(
                [
                    'message' => count($test_list) . ' test list found.',
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
            'test_category_id' => ['required']
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(), 400);
        }
        else{
            $data = [
                'name' => $request->name,
                'test_category_id' => $request->test_category_id
            ];

            DB::beginTransaction();

            try {

                $test_lists = TestList::create($data);
                DB::commit();
            } catch(\Exception $e) {
                DB::rollBack();
                $test_list = null;
                return response()->json([
                    'message' => $e->getMessage()
                ], 200);
            }

            if($test_list != null) {
                return response()->json([
                    'message' => 'Test List Registered Successfully.'
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
        $test_list = TestList::find($test_list);

        if(is_null($test_list)) {
            $response = [
                'message' => 'Test List not found.',
                'status' => 0
            ];
        }
        else{
            $response = [
                'message' => 'Test Lists found.',
                'status' => 1,
                'data' => $test_list
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
        $test_list = TestList::find($id);
        if(is_null($test_lists)) {
            $response = [
                'message' => 'Test List not found',
                'status' => 0
            ];

            $respCode = 404;
        }
        else{

            DB::beginTransaction();

            try {
                $test_list->name = $request['name'];
                $test_list->test_category_id = $request['test_category_id'];
                $test_list->save();

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                $test_list = null;
                return response()->json([
                    'message' => $e->getMessage()
                ], 200);
                }
                
                if ($test_list != null) {
                $response = [
                    'message' => 'Test List updated successfully',
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
        $test_list = TestList::find($test_list_id);

        if(is_null($test_list) > 0) {
            $response = [
                'message' => 'Test List not found',
                'status' => 0
            ];

            $respCode = 404;
        }
        else{
            DB::beginTransaction();

            try {
                $test_list -> delete();
                DB::commit();

                $response =[
                    'message' => 'Test List deleted successfully.',
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
