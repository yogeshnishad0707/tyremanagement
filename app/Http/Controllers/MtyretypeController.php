<?php

namespace App\Http\Controllers;

use App\Models\Mtyretype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MtyretypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mtyretypes = Mtyretype::orderBy('id', 'desc')->get();

        $transTyreType = [];
        foreach ($mtyretypes as $mtyretype) {
            $datatyretype = (object)[];
            $datatyretype->id = $mtyretype->id;
            $datatyretype->category_name = $mtyretype->category_name;
            $datatyretype->status = $mtyretype->status;  // No need for the if-else logic
            $datatyretype->operatorid = $mtyretype->operatorid;
            $datatyretype->page_name = 'TyreType';
            $transTyreType[] = $datatyretype;
            }
        return response()->json($transTyreType);
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
        // return $request;
        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
        ]);

        if ($validator->fails()) {
            $obj = ["Status" => false, "success" => 0, "errors" => $validator->errors()];
            return response()->json($obj);
        }

        // Create a new user
        $mtyretypes = new Mtyretype();
        $mtyretypes->category_name =  $request->category_name;
        $mtyretypes->status =  $request->status;
        $mtyretypes->operatorid = $request->operatorid;

        $existingTyreType = Mtyretype::where('category_name', $mtyretypes->category_name)->first();
        if ($existingTyreType) {
            // Handle duplicate Email Id
            $obj = ["Status" => false, "success" => 0, "errors" => 'The tyre type has already been taken.'];
            return response()->json($obj);
        }
        try {
            $mtyretypes->save();
            return response()->json(['message' => 'Tyre Type Added successfully!']);
        } catch (\Exception $ex) {
            return $ex;
            $obj = ["Status" => false, "success" => 0, "msg" => "Tyre Type Not Added!!"];
            return response()->json($obj);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Mtyretype $mtyretype)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mtyretype $mtyretype)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mtyretype $mtyretype)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mtyretype $mtyretype)
    {
    }

    public function getByid(Request $request)
    {
        // return "okk";die;
        try {
            $mtyretypes = Mtyretype::where('id',$request->id)->select('id', 'category_name')->get();
            $obj = ["Status" => true, "success" => 1, 'Tyre Type For Update' => $mtyretypes];
            return response()->json($obj);
        } catch (\Exception $ex) {
            return $ex;
            $obj = ["Status" => false, "success" => 0, "msg" => "Tyre Type For Update Not Found!"];
            return response()->json($obj);
        }
    }

    public function updatetyretype(Request $request, $id)
    {
        // return "ok";
        $validator = Validator::make($request->all(),[
            'category_name'=>'required',
        ]);
        if($validator->fails()){
            $val = ['Stauts'=> false, 'success'=> '0','errors'=>$validator->errors()];
            return response()->json($val);
        }
        try {
            $mtyretypes = Mtyretype::findOrFail($id);
            $mtyretypes->update([
                'category_name'=>$request->category_name,
                'status'=>$request->status,
                'operatorid'=>$request->operatorid,
            ]);
            return response()->json(['message'=>'Tyre Type Updated SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "Tyre Type Not Updated!!"];
            return response()->json($err);
        }
    }

    public function deletetyretype($id){
        // return "ok";die;
        try {
            $mtyretypes = Mtyretype::findOrFail($id);
            $mtyretypes->delete();
            return response()->json(['message'=>'Tyre Type Deleted SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "Tyre Type Not Deleted!!"];
            return response()->json($err);
        }
    }


//     public function checkstatus(Request $request) {
//     // if (!$request->has('id') || !$request->has('status')) {
//     //     return response()->json(['Status' => false, 'success' => 0, 'msg' => 'ID and Status are required!']);
//     // }

//     // try {
//     //     $checkstatus = Mtyretype::findOrFail($request->id);

//     //     // Update the status based on the passed value
//     //     $checkstatus->update([
//     //         'status' => $request->status,  // Dynamically set status from the request
//     //     ]);

//     //     return response()->json(['message' => 'Status Updated Successfully!']);
//     // } catch (\Exception $ex) {
//     //     return response()->json(['Status' => false, 'success' => 0, 'msg' => 'Status Not Updated!', 'error' => $ex->getMessage()]);
//     // }

// }


}

