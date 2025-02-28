<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mtruckmake;
use Illuminate\Support\Facades\Validator;

class MtruckmakeController extends Controller
{
    public function truckmakelist()
    {
        $mtruckmakes = Mtruckmake::orderBy('id','desc')->get();

        $transTruckMake = [];
        foreach ($mtruckmakes as $mtruckmake) {
            $datatruckmake = (object)[];
            $datatruckmake->id = $mtruckmake->id;
            $datatruckmake->category_name = $mtruckmake->category_name;
            $datatruckmake->status = $mtruckmake->status;
            $datatruckmake->operatorid = $mtruckmake->operatorid;
            $transTruckMake[] = $datatruckmake;
        }
        return response()->json($transTruckMake);
    }

    public function inserttruckmake(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
        ]);

        if ($validator->fails()) {
            $obj = ["Status" => false, "success" => 0, "errors" => $validator->errors()];
            return response()->json($obj);
        }

        // Create a new user
        $mtruckmakes = new Mtruckmake();
        $mtruckmakes->category_name =  $request->category_name;
        $mtruckmakes->status =  $request->status;
        $mtruckmakes->operatorid = $request->operatorid;

        $existingTruckMake = Mtruckmake::where('category_name', $mtruckmakes->category_name)->first();
        if ($existingTruckMake) {
            // Handle duplicate Email Id
            $obj = ["Status" => false, "success" => 0, "errors" => 'The truck make has already been taken.'];
            return response()->json($obj);
        }
        try {
            $mtruckmakes->save();
            return response()->json(['message' => 'Truck Make Added successfully!']);
        } catch (\Exception $ex) {
            return $ex;
            $obj = ["Status" => false, "success" => 0, "msg" => "Truck Make Not Added!!"];
            return response()->json($obj);
        }
    }

    public function updatetruckmake(Request $request, $id)
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
            $mtruckmakes = Mtruckmake::findOrFail($id);
            $mtruckmakes->update([
                'category_name'=>$request->category_name,
                'status'=>$request->status,
                'operatorid'=>$request->operatorid,
            ]);
            return response()->json(['message'=>'Truck Make Updated SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "Truck Make Not Updated!!"];
            return response()->json($err);
        }
    }

    public function deletetruckmake($id){
        // return "ok";die;
        try {
            $mtruckmakes = Mtruckmake::findOrFail($id);
            $mtruckmakes->delete();
            return response()->json(['message'=>'Truck Make Deleted SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "Truck Make Not Deleted!!"];
            return response()->json($err);
        }
    }
}
