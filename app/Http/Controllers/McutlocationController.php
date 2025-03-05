<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mcutlocation;
use Illuminate\Support\Facades\Validator;

class McutlocationController extends Controller
{
    public function cutlocationlist()
    {
        $mcutlocations = Mcutlocation::orderBy('id','desc')->get();

        $transCutLocation = [];
        foreach ($mcutlocations as $mcutlocation) {
            $datacutlocation = (object)[];
            $datacutlocation->id = $mcutlocation->id;
            $datacutlocation->category_name = $mcutlocation->category_name;
            $datacutlocation->status = $mcutlocation->status;
            $datacutlocation->operatorid = $mcutlocation->operatorid;
            $datacutlocation->page_name = 'CutLocation';
            $transCutLocation[] = $datacutlocation;
        }
        return response()->json($transCutLocation);
    }

    public function insertcutlocation(Request $request)
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
        $mcutlocations = new Mcutlocation();
        $mcutlocations->category_name =  $request->category_name;
        $mcutlocations->status =  $request->status;
        $mcutlocations->operatorid = $request->operatorid;

        $existingCutLocation = Mcutlocation::where('category_name', $mcutlocations->category_name)->first();
        if ($existingCutLocation) {
            // Handle duplicate Email Id
            $obj = ["Status" => false, "success" => 0, "errors" => 'The Cut Location has already been taken.'];
            return response()->json($obj);
        }
        try {
            $mcutlocations->save();
            return response()->json(['message' => 'Cut Location Added successfully!']);
        } catch (\Exception $ex) {
            return $ex;
            $obj = ["Status" => false, "success" => 0, "msg" => "Cut Location Not Added!!"];
            return response()->json($obj);
        }
    }

    public function getCutLocationByid(Request $request)
    {
        // return "okk";die;
        try {
            $mcutlocations = Mcutlocation::where('id',$request->id)->select('id','category_name')->get();
            $obj = ["Status" => true, "success" => 1, 'Cut Location For Update' => $mcutlocations];
            return response()->json($obj);
        } catch (\Exception $ex) {
            return $ex;
            $obj = ["Status" => false, "success" => 0, "msg" => "Cut Location For Update Not Found!"];
            return response()->json($obj);
        }
    }

    public function updatecutlocation(Request $request, $id)
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
            $mcutlocations = Mcutlocation::findOrFail($id);
            $mcutlocations->update([
                'category_name'=>$request->category_name,
                'status'=>$request->status,
                'operatorid'=>$request->operatorid,
            ]);
            return response()->json(['message'=>'Cut Location Updated SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "Cut Location Not Updated!!"];
            return response()->json($err);
        }
    }

    public function deletecutlocation($id){
        // return "ok";die;
        try {
            $mcutlocations = Mcutlocation::findOrFail($id);
            $mcutlocations->delete();
            return response()->json(['message'=>'Cut Location Deleted SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "Cut Location Not Deleted!!"];
            return response()->json($err);
        }
    }
}
