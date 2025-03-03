<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mtyrestatustype;
use Illuminate\Support\Facades\Validator;

class MtstypeController extends Controller
{
    public function tstypelist()
    {
        $mtyrestatustypes = Mtyrestatustype::orderBy('id','desc')->get();

        $transTStype = [];
        foreach ($mtyrestatustypes as $mtstype) {
            $datatstype = (object)[];
            $datatstype->id = $mtstype->id;
            $datatstype->category_name = $mtstype->category_name;
            $datatstype->status = $mtstype->status;
            $datatstype->operatorid = $mtstype->operatorid;
            $datatstype->page_name = 'TyreStatusType';
            $transTStype[] = $datatstype;
        }
        return response()->json($transTStype);
    }

    public function inserttstype(Request $request)
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
        $mtyrestatustypes = new Mtyrestatustype();
        $mtyrestatustypes->category_name =  $request->category_name;
        $mtyrestatustypes->status =  $request->status;
        $mtyrestatustypes->operatorid = $request->operatorid;

        $existingTsType = Mtyrestatustype::where('category_name', $mtyrestatustypes->category_name)->first();
        if ($existingTsType) {
            // Handle duplicate Email Id
            $obj = ["Status" => false, "success" => 0, "errors" => 'The tyre status has already been taken.'];
            return response()->json($obj);
        }
        try {
            $mtyrestatustypes->save();
            return response()->json(['message' => 'Tyre Status Added successfully!']);
        } catch (\Exception $ex) {
            return $ex;
            $obj = ["Status" => false, "success" => 0, "msg" => "Tyre Status Not Added!!"];
            return response()->json($obj);
        }
    }

    public function updatetstype(Request $request, $id)
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
            $mtyrestatustypes = Mtyrestatustype::findOrFail($id);
            $mtyrestatustypes->update([
                'category_name'=>$request->category_name,
                'status'=>$request->status,
                'operatorid'=>$request->operatorid,
            ]);
            return response()->json(['message'=>'Tyre Status Updated SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "Tyre Status Not Updated!!"];
            return response()->json($err);
        }
    }

    public function deletetstype($id){
        // return "ok";die;
        try {
            $mtyrestatustypes = Mtyrestatustype::findOrFail($id);
            $mtyrestatustypes->delete();
            return response()->json(['message'=>'Tyre Status Deleted SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "Tyre Status Not Deleted!!"];
            return response()->json($err);
        }
    }

}
