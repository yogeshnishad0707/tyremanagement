<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mntccut;
use Illuminate\Support\Facades\Validator;

class MntccutController extends Controller
{
    public function ntccutlist()
    {
        $mntccuts = Mntccut::orderBy('id','desc')->get();

        $transNtcCut = [];
        foreach ($mntccuts as $mntccut) {
            $datantccut = (object)[];
            $datantccut->id = $mntccut->id;
            $datantccut->category_name = $mntccut->category_name;
            $datantccut->status = $mntccut->status;
            $datantccut->operatorid = $mntccut->operatorid;
            $transNtcCut[] = $datantccut;
        }
        return response()->json($transNtcCut);
    }

    public function insertntccut(Request $request)
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
        $mntccuts = new Mntccut();
        $mntccuts->category_name =  $request->category_name;
        $mntccuts->status =  $request->status;
        $mntccuts->operatorid = $request->operatorid;

        $existingNtcCut = Mntccut::where('category_name', $mntccuts->category_name)->first();
        if ($existingNtcCut) {
            // Handle duplicate Email Id
            $obj = ["Status" => false, "success" => 0, "errors" => 'The ntc & tc cut has already been taken.'];
            return response()->json($obj);
        }
        try {
            $mntccuts->save();
            return response()->json(['message' => 'NTC & TC Cut Added successfully!']);
        } catch (\Exception $ex) {
            return $ex;
            $obj = ["Status" => false, "success" => 0, "msg" => "NTC & TC Cut Not Added!!"];
            return response()->json($obj);
        }
    }

    public function updatentccut(Request $request, $id)
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
            $mntccuts = Mntccut::findOrFail($id);
            $mntccuts->update([
                'category_name'=>$request->category_name,
                'status'=>$request->status,
                'operatorid'=>$request->operatorid,
            ]);
            return response()->json(['message'=>'NTC & TC Cut Updated SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "NTC & TC Cut Not Updated!!"];
            return response()->json($err);
        }
    }

    public function deletentccut($id){
        // return "ok";die;
        try {
            $mntccuts = Mntccut::findOrFail($id);
            $mntccuts->delete();
            return response()->json(['message'=>'NTC & TC Cut Deleted SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "NTC & TC Cut Not Deleted!!"];
            return response()->json($err);
        }
    }
}
