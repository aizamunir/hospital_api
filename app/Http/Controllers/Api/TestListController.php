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

                $test_list = TestList::create($data);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
