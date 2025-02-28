<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tyreinformation;
use App\Models\Mtyresize;
use Illuminate\Support\Facades\Validator;

class TyreinfoController extends Controller
{
    public function tyreinfolist()
    {
        $tyreinformations = Tyreinformation::orderBy('id','desc')->get();

        $transTyreInfo = [];
        foreach ($tyreinformations as $tyreinfo) {
            $tyre_size = getval('mtyresizes','id',$tyreinfo->tyresize_id,'category_name');
            $dataTyreInfo = (object)[];
            $dataTyreInfo->tyresize_id = $tyre_size;
            $dataTyreInfo->make = $tyreinfo->make;
            $dataTyreInfo->tyre_no = $tyreinfo->tyre_no;
            $dataTyreInfo->curr_status = $tyreinfo->curr_status;
            $dataTyreInfo->otl = $tyreinfo->otl;
            $dataTyreInfo->otd = $tyreinfo->otd;
            $dataTyreInfo->status = $tyreinfo->status;
            $dataTyreInfo->operatorid = $tyreinfo->operatorid;
            $transTyreInfo[] = $dataTyreInfo;
        }
        return response()->json($transTyreInfo);
    }

    public function inserttyreinfo(Request $request)
    {
        // return "hello";die;
        $validator = Validator::make($request->all(), [
            'tyresize_id' => 'required',
            'make' => 'required',
            'tyre_no' => 'required',
            'curr_status' => 'required',
            'otl' => 'required',
            'otd' => 'required',
        ]);

        if ($validator->fails()) {
            $obj = ["Status" => false, "success" => 0, "errors" => $validator->errors()];
            return response()->json($obj);
        }

        // Create a new user
        $tyreinformations = new Tyreinformation();
        $tyreinformations->tyresize_id = $request->tyresize_id;
        $tyreinformations->make =  $request->make;
        $tyreinformations->tyre_no =  $request->tyre_no;
        $tyreinformations->curr_status =  $request->curr_status;
        $tyreinformations->otl =  $request->otl;
        $tyreinformations->otd =  $request->otd;
        $tyreinformations->status =  $request->status;
        $tyreinformations->operatorid = $request->operatorid;

        // $existingTyreSize = Tyreinformation::where('category_name', $tyreinformations->category_name)->first();
        // if ($existingTyreSize) {
        //     // Handle duplicate Email Id
        //     $obj = ["Status" => false, "success" => 0, "errors" => 'The tyre type has already been taken.'];
        //     return response()->json($obj);
        // }
        try {
            $tyreinformations->save();
            return response()->json(['message' => 'Tyre Info Added successfully!']);
        } catch (\Exception $ex) {
            return $ex;
            $obj = ["Status" => false, "success" => 0, "msg" => "Tyre Info Not Added!!"];
            return response()->json($obj);
        }
    }

    public function updatetyreinfo(Request $request, $id)
    {
        // return "ok";
        $validator = Validator::make($request->all(),[
            'tyresize_id' => 'required',
            'make' => 'required',
            'tyre_no' => 'required',
            'curr_status' => 'required',
            'otl' => 'required',
            'otd' => 'required',
        ]);
        if($validator->fails()){
            $val = ['Stauts'=> false, 'success'=> '0','errors'=>$validator->errors()];
            return response()->json($val);
        }
        try {
            $tyreinformations = Tyreinformation::findOrFail($id);
            $tyreinformations->update([
                'tyresize_id'=>$request->tyresize_id,
                'make'=>$request->make,
                'tyre_no'=>$request->tyre_no,
                'curr_status'=>$request->curr_status,
                'otl'=>$request->otl,
                'otd'=>$request->otd,
                'status'=>$request->status,
                'operatorid'=>$request->operatorid,
            ]);
            return response()->json(['message'=>'Tyre Info Updated SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "Tyre Info Not Updated!!"];
            return response()->json($err);
        }
    }

    public function deletetyreinfo($id){
        // return "ok";die;
        try {
            $tyreinformations = Tyreinformation::findOrFail($id);
            $tyreinformations->delete();
            return response()->json(['message'=>'Tyre Info Deleted SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "Tyre Info Not Deleted!!"];
            return response()->json($err);
        }
    }

    public function gettyresize(){
        // return "okk";die;
        $mtyresizes = Mtyresize::orderBy('id','desc')->get(); 

        // if ($mtruckmakes->isEmpty()) {
        //     return response()->json(['error' => 'No Truck Make Found.'], 404);
        // }

        $transTyreSize = [];
        foreach ($mtyresizes as $tyresize) {
            $dataTyreSize = (object)[];
            $dataTyreSize->id = $tyresize->id;
            $dataTyreSize->category_name = $tyresize->category_name;
            $transTyreSize[] = $dataTyreSize;
        }
        return response()->json($transTyreSize);
    }
}
