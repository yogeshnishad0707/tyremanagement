<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tyresiteinfos;
use App\Models\Siteproject;
use App\Models\Mtruckmodel;
use App\Models\Tyreinformation;
use App\Models\Mtyreposition;
use App\Models\Mtyresize;
use Illuminate\Support\Facades\Validator;

class TyreentryController extends Controller
{
    public function insertTyreEntry(Request $request)
    {
        // return "hello";die;
        $validator = Validator::make($request->all(), [
            'tyresize_id' => 'required',
            'make_id' => 'required',
            'tyre_no' => 'required',
            'otd' => 'required',
            'project_id' => 'required',
            'truck_modal_id' => 'required',
            'tyre_info_id' => 'required',
            'position_id' => 'required',
            'ponumber' => 'required',
            'truck_no' => 'required',
        ]);

        if ($validator->fails()) {
            $obj = ["Status" => false, "success" => 0, "errors" => $validator->errors()];
            return response()->json($obj);
        }
        //create tyre information
        $tyreinformations = new Tyreinformation();
        $tyreinformations->tyresize_id = $request->tyresize_id;
        $tyreinformations->make_id =  $request->make_id;
        $tyreinformations->tyre_no =  $request->tyre_no;
        $tyreinformations->curr_status =  $request->curr_status;
        $tyreinformations->otl =  $request->otl;
        $tyreinformations->otd =  $request->otd;
        $tyreinformations->status =  $request->status;
        $tyreinformations->operatorid = $request->operatorid;
        // create a new tyre site information
        $tyresiteinfos = new Tyresiteinfos();
        $tyresiteinfos->project_id = $request->project_id;
        $tyresiteinfos->truck_modal_id = $request->truck_modal_id;
        $tyresiteinfos->tyre_info_id = $request->tyre_info_id;
        $tyresiteinfos->position_id = $request->position_id;
        $tyresiteinfos->ponumber = $request->ponumber;
        $tyresiteinfos->truck_no = $request->truck_no;
        $tyresiteinfos->otl = $request->otl;
        $tyresiteinfos->fitmandate = $request->fitmandate;
        $tyresiteinfos->removaldate = $request->removaldate;
        $tyresiteinfos->replacedate = $request->replacedate;
        $tyresiteinfos->front_life = $request->front_life;
        $tyresiteinfos->rear_life = $request->rear_life;
        $tyresiteinfos->repair_life = $request->repair_life;
        $tyresiteinfos->curr_status = $request->curr_status;
        $tyresiteinfos->remark = $request->remark;
        $tyresiteinfos->status = $request->status;
        $tyresiteinfos->operatorid = $request->operatorid;

        $existingTyreSiteiInfo = Tyresiteinfos::where('ponumber', $tyresiteinfos->ponumber)->first();
        if ($existingTyreSiteiInfo) {
            // Handle duplicate Email Id
            $obj = ["Status" => false, "success" => 0, "errors" => 'The P.O. Number has already been taken.'];
            return response()->json($obj);
        }
        try {
            $tyresiteinfos->save();
            return response()->json(['message' => 'Tyre Site Info Added successfully!']);
        } catch (\Exception $ex) {
            return $ex;
            $obj = ["Status" => false, "success" => 0, "msg" => "Tyre Site Info Not Added!!"];
            return response()->json($obj);
        }
    }
}
