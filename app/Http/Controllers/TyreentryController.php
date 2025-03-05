<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tyreinformation;
use App\Models\Tyresiteinfos;
use App\Models\Tyrefitmanremovalinfo;
use App\Models\Tyreperformanceinfo;
use App\Models\Siteproject;
use App\Models\Mtruckmodel;
use App\Models\Mtyreposition;
use App\Models\Mtyresize;
use Illuminate\Support\Facades\Validator;

class TyreentryController extends Controller
{
    public function insertTyreEntry(Request $request)
    {
        // return "hello";die;
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'tyresize_id' => 'required',
            'make_id' => 'required',
            'truck_modal_id' => 'required',
            'tyre_no' => 'required',
            'truck_no' => 'required',
            'otd' => 'required',
            'position_id' => 'required',            
        ]);

        if ($validator->fails()) {
            $obj = ["Status" => false, "success" => 0, "errors" => $validator->errors()];
            return response()->json($obj);
        }
        //create tyre information
        $tyreinformations = new Tyreinformation();
        // $tyreinformations->tyretype_id = $request->tyretype_id;
        $tyreinformations->tyresize_id = $request->tyresize_id;
        $tyreinformations->make_id =  $request->make_id;
        $tyreinformations->tyre_no =  $request->tyre_no;
        $tyreinformations->current_status = 'running';
        $tyreinformations->otl =  $request->otl;
        $tyreinformations->otd =  $request->otd;
        $tyreinformations->status =  $request->status;
        $tyreinformations->operatorid = $request->operatorid;

        $existingTyre_no = Tyreinformation::where('tyre_no', $tyreinformations->tyre_no)->first();
        if ($existingTyre_no) {
            // Handle duplicate Email Id
            $obj = ["Status" => false, "success" => 0, "errors" => 'Tyre Number Number has already been taken.'];
            return response()->json($obj);
        }
        try {
            // $tyreinfo = $tyreinformations->save();
            // $tyre_infoid =$tyreinfo[0]->id; //last insertid
            $tyreinfo = $tyreinformations->save();
            if ($tyreinfo) {
                // Save successful, get the last insert ID
                $tyre_infoid = $tyreinformations->id; // Use the ID directly
            } else {
                // Handle error if save fails
                $obj = ["Status" => false, "success" => 0, "msg" => "Tyre Entry Not Added!!"];
                return response()->json($obj);
            }

            // create a new tyre site information
            $tyresiteinfos = new Tyresiteinfos();
            $tyresiteinfos->project_id = $request->project_id;
            $tyresiteinfos->truck_modal_id = $request->truck_modal_id;
            $tyresiteinfos->tyre_info_id = $tyre_infoid;
            $tyresiteinfos->position_id = $request->position_id;
            $tyresiteinfos->ponumber = $request->ponumber;
            $tyresiteinfos->truck_no = $request->truck_no;
            $tyresiteinfos->otl = $request->otl;
            $tyresiteinfos->fitmandate = $request->service_date;
            $tyresiteinfos->removaldate = $request->removaldate;
            $tyresiteinfos->replacedate = $request->replacedate;
            $tyresiteinfos->front_life = $request->front_life ?? '0';
            $tyresiteinfos->rear_life = $request->rear_life ?? '0';
            $tyresiteinfos->repair_life = $request->repair_life ?? '0';
            $tyresiteinfos->current_status = 'running';
            $tyresiteinfos->remark = '1st fitman';
            $tyresiteinfos->status = $request->status;
            $tyresiteinfos->operatorid = $request->operatorid;

            // $tyresite = $tyresiteinfos->save();
            // $tyresite_id = $tyresite[0]->id;//last insertid
            $tyresite = $tyresiteinfos->save();
            if ($tyresite) {
                // Save successful, get the last insert ID
                $tyresite_id = $tyresiteinfos->id; // Use the ID directly
            } else {
                // Handle error if save fails
                $obj = ["Status" => false, "success" => 0, "msg" => "Tyre Entry Not Added!!"];
                return response()->json($obj);
            }

            //create tyre fitment removal information
            $tyrefitmanremovalinfos = new Tyrefitmanremovalinfo();
            $tyrefitmanremovalinfos->tyre_site_id =$tyresite_id;
            $tyrefitmanremovalinfos->type =  $request->type;
            $tyrefitmanremovalinfos->service_date =  $request->service_date;
            $tyrefitmanremovalinfos->lbsr =  $request->lbsr ?? ($request->current_hmr ?? 0);
            $tyrefitmanremovalinfos->remark = '1st fitman';

            // $fitmaninfo = $tyrefitmanremovalinfos->save();
            // $tyrefitmanid = $fitmaninfo[0]->id;//last insertid
            $fitmaninfo = $tyrefitmanremovalinfos->save();
            if ($fitmaninfo) {
                // Save successful, get the last insert ID
                $tyrefitmanid = $tyrefitmanremovalinfos->id; // Use the ID directly
            } else {
                // Handle error if save fails
                $obj = ["Status" => false, "success" => 0, "msg" => "Tyre Entry Not Added!!"];
                return response()->json($obj);
            }

            //create tyre performance information
            $tyreperformanceinfos = new Tyreperformanceinfo();
            $tyreperformanceinfos->tyre_site_id = $tyresite_id;
            $tyreperformanceinfos->tfr_id =  $tyrefitmanid;
            $tyreperformanceinfos->rtd_a =  $request->rtd_a ?? '0';
            $tyreperformanceinfos->rtd_b =  $request->rtd_b ?? '0';
            $tyreperformanceinfos->current_hmr = $request->current_hmr ?? ($request->lbsr ?? 0);
            $tyreperformanceinfos->lbsr =  $request->lbsr ?? ($request->current_hmr ?? 0);
            $tyreperformanceinfos->hcicm =  $request->hcicm;
            $tyreperformanceinfos->service_date =  $request->service_date;
            $tyreperformanceinfos->fl =  $request->fl ?? '0';
            $tyreperformanceinfos->rl =  $request->rl ?? '0';
            $tyreperformanceinfos->repaire_life =  $request->repaire_life ?? '0';
            $tyreperformanceinfos->remark =  '1st fitman';
            $tyreperformanceinfos->operatorid =  $request->operatorid;

            $tyreperformanceinfos->save();
            return response()->json(['message' => 'Tyre Tyre Entry Added successfully!']);
        } catch (\Exception $ex) {
            return $ex;
            $obj = ["Status" => false, "success" => 0, "msg" => "Tyre Tyre Entry Not Added!!"];
            return response()->json($obj);
        }
    }

    public function deleteTyreEntry(Request $request){
        // return "okk";die;
        try {
            // Fetch the tyre information record
            $tyreinformations = Tyreinformation::find($request->id);
            
            if (!$tyreinformations) {
                return response()->json(['message' => 'Tyre Entry not found!'], 404);
            }
    
            // Get the related tyresiteinfos record
            $tyresiteinfos = Tyresiteinfos::where('tyre_info_id', $tyreinformations->id)->first();
    
            if ($tyresiteinfos) {
                // Delete related records from Tyrefitmanremovalinfo and Tyreperformanceinfo
                Tyrefitmanremovalinfo::where('tyre_site_id', $tyresiteinfos->id)->delete();
                Tyreperformanceinfo::where('tyre_site_id', $tyresiteinfos->id)->delete();
    
                // Finally, delete the tyresiteinfos record
                $tyresiteinfos->delete();
            }
    
            // Delete the tyre information record
            $tyreinformations->delete();
    
            return response()->json(['message' => 'Tyre Entry Deleted Successfully!']);
        } catch (\Exception $ex) {
            // Handle the exception and return the error message
            return response()->json(['Status' => false, 'success' => 0, 'msg' => 'Tyre Entry Not Deleted!!', 'error' => $ex->getMessage()]);
        }
    }
    
}
