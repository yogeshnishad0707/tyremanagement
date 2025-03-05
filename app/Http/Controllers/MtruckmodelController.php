<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mtruckmodel;
use App\Models\Mtruckmake;
use Illuminate\Support\Facades\Validator;

class MtruckmodelController extends Controller
{
    public function truckmodellist()
    {
        $mtruckmodels = Mtruckmodel::orderBy('id','desc')->get();

        $transTruckModel = [];
        foreach ($mtruckmodels as $mtruckmodel) {
            $mtruckmakename = getval('mtruckmakes','id',$mtruckmodel->truckmake_id,'category_name');
            $datatruckmodel = (object)[];
            $datatruckmodel->id = $mtruckmodel->id;
            $datatruckmodel->truckmake_id = $mtruckmodel->truckmake_id;
            $datatruckmodel->truckmake_name = $mtruckmakename;
            $datatruckmodel->category_name = $mtruckmodel->category_name;
            $datatruckmodel->status = $mtruckmodel->status;
            $datatruckmodel->operatorid = $mtruckmodel->operatorid;
            $datatruckmodel->page_name = 'TruckModel';
            $transTruckModel[] = $datatruckmodel;
        }
        return response()->json($transTruckModel);
    }

    public function insertruckmodel(Request $request)
    {
        // return "hello";die;
        $validator = Validator::make($request->all(), [
            'truckmake_id' => 'required',
            'category_name' => 'required',
        ]);

        if ($validator->fails()) {
            $obj = ["Status" => false, "success" => 0, "errors" => $validator->errors()];
            return response()->json($obj);
        }

        // Create a new user
        $mtruckmodels = new Mtruckmodel();
        $mtruckmodels->truckmake_id = $request->truckmake_id;
        $mtruckmodels->category_name =  $request->category_name;
        $mtruckmodels->status =  $request->status;
        $mtruckmodels->operatorid = $request->operatorid;

        // $existingTyreSize = Mtruckmodel::where('category_name', $mtruckmodels->category_name)->first();
        // if ($existingTyreSize) {
        //     // Handle duplicate Email Id
        //     $obj = ["Status" => false, "success" => 0, "errors" => 'The tyre type has already been taken.'];
        //     return response()->json($obj);
        // }
        try {
            $mtruckmodels->save();
            return response()->json(['message' => 'Truck Model Added successfully!']);
        } catch (\Exception $ex) {
            return $ex;
            $obj = ["Status" => false, "success" => 0, "msg" => "Truck Model Not Added!!"];
            return response()->json($obj);
        }
    }

    public function getTruckModelByid(Request $request)
    {
        // return "okk";die;
        try {
            $mtruckmodels = Mtruckmodel::where('id',$request->id)->first();

            if(!$mtruckmodels){
                return response()->json(["Status" => false, "success" => 0, "msg" => "Truck Model Not Found!"]);
            }

            $truckmake = getval('mtruckmakes','id',$mtruckmodels->truckmake_id,'category_name');
            $mtruckmodels =[
                'id' =>$mtruckmodels->id,
                'truckmake_id' =>$mtruckmodels->truckmake_id,
                'truckmake' =>$truckmake,
                'category_name' =>$mtruckmodels->category_name,
            ];
            $obj = ["Status" => true, "success" => 1, 'Truck Model For Update' => $mtruckmodels];
            return response()->json($obj);
        } catch (\Exception $ex) {
            $obj = ["Status" => false, "success" => 0, "msg" => "Truck Model For Update Not Found!"];
            return response()->json($obj);
        }
    }

    public function updatetruckmodel(Request $request, $id)
    {
        // return "ok";
        $validator = Validator::make($request->all(),[
            'truckmake_id'=>'required',
            'category_name'=>'required',
        ]);
        if($validator->fails()){
            $val = ['Stauts'=> false, 'success'=> '0','errors'=>$validator->errors()];
            return response()->json($val);
        }
        try {
            $mtruckmodels = Mtruckmodel::findOrFail($id);
            $mtruckmodels->update([
                'truckmake_id'=>$request->truckmake_id,
                'category_name'=>$request->category_name,
                'status'=>$request->status,
                'operatorid'=>$request->operatorid,
            ]);
            return response()->json(['message'=>'Truck Model Updated SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "Truck Model Not Updated!!"];
            return response()->json($err);
        }
    }

    public function deletetruckmodel($id){
        // return "ok";die;
        try {
            $mtruckmodels = Mtruckmodel::findOrFail($id);
            $mtruckmodels->delete();
            return response()->json(['message'=>'Truck Model Deleted SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "Truck Model Not Deleted!!"];
            return response()->json($err);
        }
    }

    public function gettruckmake(){
        // return "okk";die;
        $mtruckmakes = Mtruckmake::where('status','1')->orderBy('id','desc')->get(); 

        if ($mtruckmakes->isEmpty()) {
            return response()->json(['error' => 'No Truck Make Found.'], 404);
        }

        $transTruckMake = [];
        foreach ($mtruckmakes as $mtruckmake) {
            $datatruckmake = (object)[];
            $datatruckmake->id = $mtruckmake->id;
            $datatruckmake->category_name = $mtruckmake->category_name;
            // $datatruckmake->operatorid = $mtruckmake->operatorid;
            $transTruckMake[] = $datatruckmake;
        }
        return response()->json($transTruckMake);
    }
}
