<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maccuratelocation;
use Illuminate\Support\Facades\Validator;

class MacculocationController extends Controller
{
    public function accuratelocationlist()
    {
        $maccuratelocations = Maccuratelocation::orderBy('id','desc')->get();

        $transAccurateLocation = [];
        foreach ($maccuratelocations as $maccuratelocation) {
            $dataAccuLocation = (object)[];
            $dataAccuLocation->category_name = $maccuratelocation->category_name;
            $dataAccuLocation->status = $maccuratelocation->status;
            $dataAccuLocation->operatorid = $maccuratelocation->operatorid;
            $transAccurateLocation[] = $dataAccuLocation;
        }
        return response()->json($transAccurateLocation);
    }

    public function insertaccuratelocation(Request $request)
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
        $maccuratelocations = new Maccuratelocation();
        $maccuratelocations->category_name =  $request->category_name;
        $maccuratelocations->status =  $request->status;
        $maccuratelocations->operatorid = $request->operatorid;

        $existingAccuLocation = Maccuratelocation::where('category_name', $maccuratelocations->category_name)->first();
        if ($existingAccuLocation) {
            // Handle duplicate Email Id
            $obj = ["Status" => false, "success" => 0, "errors" => 'The accurate location has already been taken.'];
            return response()->json($obj);
        }
        try {
            $maccuratelocations->save();
            return response()->json(['message' => 'Accurate Location Added successfully!']);
        } catch (\Exception $ex) {
            return $ex;
            $obj = ["Status" => false, "success" => 0, "msg" => "Accurate Location Not Added!!"];
            return response()->json($obj);
        }
    }

    public function updateaccuratelocation(Request $request, $id)
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
            $maccuratelocations = Maccuratelocation::findOrFail($id);
            $maccuratelocations->update([
                'category_name'=>$request->category_name,
            ]);
            return response()->json(['message'=>'Accurate Location Updated SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "Accurate Location Not Updated!!"];
            return response()->json($err);
        }
    }

    public function deleteaccuratelocation($id){
        // return "ok";die;
        try {
            $maccuratelocations = Maccuratelocation::findOrFail($id);
            $maccuratelocations->delete();
            return response()->json(['message'=>'Accurate Location Deleted SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "Accurate Location Not Deleted!!"];
            return response()->json($err);
        }
    }
}
