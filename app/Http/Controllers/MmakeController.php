<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mmake;
use Illuminate\Support\Facades\Validator;

class MmakeController extends Controller
{
    public function makelist()
    {
        $mmakes = Mmake::orderBy('id','desc')->get();

        $transMake = [];
        foreach ($mmakes as $make) {
            $dataMake = (object)[];
            $dataMake->id = $make->id;
            $dataMake->category_name = $make->category_name;
            $dataMake->status = $make->status;
            $dataMake->operatorid = $make->operatorid;
            $dataMake->page_name = 'Make';
            $transMake[] = $dataMake;
        }
        return response()->json($transMake);
    }

    public function insertMake(Request $request)
    {
        // return "hello";die;
        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
        ]);

        if ($validator->fails()) {
            $obj = ["Status" => false, "success" => 0, "errors" => $validator->errors()];
            return response()->json($obj);
        }

        // Create a new user
        $mmakes = new Mmake();
        $mmakes->category_name =  $request->category_name;
        $mmakes->status =  $request->status;
        $mmakes->operatorid = $request->operatorid;

        $existingMake = Mmake::where('category_name', $mmakes->category_name)->first();
        if ($existingMake) {
            // Handle duplicate Email Id
            $obj = ["Status" => false, "success" => 0, "errors" => 'The Make has already been taken.'];
            return response()->json($obj);
        }
        try {
            $mmakes->save();
            return response()->json(['message' => 'Make Added successfully!']);
        } catch (\Exception $ex) {
            return $ex;
            $obj = ["Status" => false, "success" => 0, "msg" => "Make Not Added!!"];
            return response()->json($obj);
        }
    }

    public function getMakeByid(Request $request)
    {
        // return "okk";die;
        try {
            $mmakes = Mmake::where('id',$request->id)->select('id','category_name')->get();
            $obj = ["Status" => true, "success" => 1, 'Make Type For Update' => $mmakes];
            return response()->json($obj);
        } catch (\Exception $ex) {
            return $ex;
            $obj = ["Status" => false, "success" => 0, "msg" => "Make Type For Update Not Found!"];
            return response()->json($obj);
        }
    }

    public function updateMake(Request $request, $id)
    {
        // return "ok";die;
        $validator = Validator::make($request->all(),[
            'category_name'=>'required',
        ]);
        if($validator->fails()){
            $val = ['Stauts'=> false, 'success'=> '0','errors'=>$validator->errors()];
            return response()->json($val);
        }
        try {
            $mmakes = Mmake::findOrFail($id);
            $mmakes->update([
                'category_name'=>$request->category_name,
                'status'=>$request->status,
                'operatorid'=>$request->operatorid,
            ]);
            return response()->json(['message'=>'Make Updated SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "Make Not Updated!!"];
            return response()->json($err);
        }
    }

    public function deleteMake($id){
        // return "ok";die;
        try {
            $mmakes = Mmake::findOrFail($id);
            $mmakes->delete();
            return response()->json(['message'=>'Make Deleted SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "Make Not Deleted!!"];
            return response()->json($err);
        }
    }
}
