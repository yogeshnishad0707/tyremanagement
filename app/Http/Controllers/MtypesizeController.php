<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mtyresize;
use App\Models\Mtyretype;
use Illuminate\Support\Facades\Validator;

class MtypesizeController extends Controller
{
    public function tyresizelist()
    {
        $mtyresizes = Mtyresize::orderBy('id','desc')->get();

        $transTyreSize = [];
        foreach ($mtyresizes as $mtyresize) {
            $tyretypename = getval('mtyretypes','id',$mtyresize->tyretype_id,'category_name');
            $datatyresize = (object)[];
            $datatyresize->id = $mtyresize->id;
            $datatyresize->tyretype_id = $tyretypename;
            $datatyresize->category_name = $mtyresize->category_name;
            $datatyresize->status = $mtyresize->status;
            $datatyresize->operatorid = $mtyresize->operatorid;
            $datatyresize->page_name = 'TyreTypeSize';
            $transTyreSize[] = $datatyresize;
        }
        return response()->json($transTyreSize);
    }

    public function inserttyresize(Request $request)
    {
        // return "hello";die;
        $validator = Validator::make($request->all(), [
            'tyretype_id' => 'required',
            'category_name' => 'required',
        ]);

        if ($validator->fails()) {
            $obj = ["Status" => false, "success" => 0, "errors" => $validator->errors()];
            return response()->json($obj);
        }

        // Create a new user
        $mtyresizes = new Mtyresize();
        $mtyresizes->tyretype_id = $request->tyretype_id;
        $mtyresizes->category_name =  $request->category_name;
        $mtyresizes->status =  $request->status;
        $mtyresizes->operatorid = $request->operatorid;

        // $existingTyreSize = Mtyresize::where('category_name', $mtyresizes->category_name)->first();
        // if ($existingTyreSize) {
        //     // Handle duplicate Email Id
        //     $obj = ["Status" => false, "success" => 0, "errors" => 'The tyre type has already been taken.'];
        //     return response()->json($obj);
        // }
        try {
            $mtyresizes->save();
            return response()->json(['message' => 'Tyre Size Added successfully!']);
        } catch (\Exception $ex) {
            return $ex;
            $obj = ["Status" => false, "success" => 0, "msg" => "Tyre Size Not Added!!"];
            return response()->json($obj);
        }
    }

    public function updatetyresize(Request $request, $id)
    {
        // return "ok";
        $validator = Validator::make($request->all(),[
            'tyretype_id'=>'required',
            'category_name'=>'required',
        ]);
        if($validator->fails()){
            $val = ['Stauts'=> false, 'success'=> '0','errors'=>$validator->errors()];
            return response()->json($val);
        }
        try {
            $mtyresizes = Mtyresize::findOrFail($id);
            $mtyresizes->update([
                'tyretype_id'=>$request->tyretype_id,
                'category_name'=>$request->category_name,
                'status'=>$request->status,
                'operatorid'=>$request->operatorid,
            ]);
            return response()->json(['message'=>'Tyre Size Updated SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "Tyre Size Not Updated!!"];
            return response()->json($err);
        }
    }

    public function deletetyresize($id){
        // return "ok";die;
        try {
            $mtyresizes = Mtyresize::findOrFail($id);
            $mtyresizes->delete();
            return response()->json(['message'=>'Tyre Size Deleted SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "Tyre Size Not Deleted!!"];
            return response()->json($err);
        }
    }

    public function gettyretype(){
        // return "okk";die;
        $mtyretypes = Mtyretype::where('status','1')->orderBy('id','desc')->get(); 

        if ($mtyretypes->isEmpty()) {
            return response()->json(['error' => 'No Tyre Type found.'], 404);
        }

        $transTyreType = [];
        foreach ($mtyretypes as $mtyretype) {
            $datatyretype = (object)[];
            $datatyretype->id = $mtyretype->id;
            $datatyretype->category_name = $mtyretype->category_name;
            $transTyreType[] = $datatyretype;
        }
        return response()->json($transTyreType);
    }
}
