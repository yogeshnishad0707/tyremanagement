<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tyreinformation;
use App\Models\Tyresiteinfos;
use App\Models\Tyrefitmanremovalinfo;
use App\Models\Tyreperformanceinfo;
use App\Models\Siteproject;
use App\Models\Mtyresize;
use App\Models\Mmake;
use App\Models\Mtruckmodel;
use App\Models\Mtyreposition;

use Illuminate\Support\Facades\Validator;

class TyreentryController extends Controller
{
    // get site tyreEntry List
    public function tyreEntryList()
    {
        $tyreinformations = Tyreinformation::join('tyresiteinfos', 'tyreinformations.id', '=', 'tyresiteinfos.tyre_info_id')
            ->join('tyrefitmanremovalinfos', 'tyresiteinfos.id', '=', 'tyrefitmanremovalinfos.tyre_site_id')
            ->join('tyreperformanceinfos', 'tyresiteinfos.id', '=', 'tyreperformanceinfos.tyre_site_id')
            ->select(
                'tyreinformations.id as id', 
                'tyreinformations.tyresize_id as tyresize_id',
                'tyreinformations.ponumber as ponumber', 
                'tyreinformations.tyre_no as tyre_no',
                'tyreinformations.current_status as current_status',
                'tyreinformations.otl as otl',
                'tyreinformations.otd as otd',
                'tyreinformations.make_id',
                'tyresiteinfos.tyre_info_id as tyre_info_id',
                'tyresiteinfos.project_id as project_id', // Another column from 'tyreinformations'
                'tyresiteinfos.truck_modal_id as truck_modal_id', // Specific column from 'tyresiteinfos'
                'tyresiteinfos.position_id as position_id', 
                'tyresiteinfos.truck_no as truck_no', 
                'tyresiteinfos.fitmandate as fitmandate', 
                'tyrefitmanremovalinfos.tyre_site_id', // Specific column from 'tyrefitmanremovalinfos'
                'tyrefitmanremovalinfos.remark',
                'tyrefitmanremovalinfos.service_date',
                'tyrefitmanremovalinfos.lbsr',
                'tyrefitmanremovalinfos.type',
                'tyreperformanceinfos.tfr_id',
                // 'tyreperformanceinfos.rtd_a',
                // 'tyreperformanceinfos.rtd_b',
                'tyreperformanceinfos.current_hmr',
                'tyreinformations.status',
                'tyreinformations.operatorid',
            )
            // ->where('tyreinformations.id','tyresiteinfos.tyre_info_id')
            // ->where('tyresiteinfos.id','tyrefitmanremovalinfos.tyre_site_id')
            // ->where('tyresiteinfos.id','tyreperformanceinfos.tyre_site_id')
            ->orderBy('tyreinformations.id', 'desc')
            ->get();
        // return $tyreinformations;die;

        $transTyreEntryList = [];
        foreach ($tyreinformations as $tyreinfo) {
            $tyretype_id = getval('mtyresizes','id',$tyreinfo->tyresize_id,'tyretype_id');
            $tyretype_name = getval('mtyretypes','id',$tyretype_id,'category_name');
            $tyresize_name = getval('mtyresizes', 'id', $tyreinfo->tyresize_id, 'category_name');
            $make_name = getval('mmakes', 'id', $tyreinfo->make_id, 'category_name');
            $tyre_info_name = getval('tyreinformations', 'id', $tyreinfo->tyre_info_id, 'tyre_no');
            $project_name = getval('siteprojects', 'id', $tyreinfo->project_id, 'project_name');
            $project_site_id = getval('siteprojects', 'id', $tyreinfo->project_id, 'site_id');
            $truck_make_id = getval('mtruckmodels', 'id', $tyreinfo->truck_modal_id, 'truckmake_id');
            $truck_make_name = getval('mtruckmakes', 'id', $truck_make_id, 'category_name');
            $truck_modal_name = getval('mtruckmodels', 'id', $tyreinfo->truck_modal_id, 'category_name');
            $position_name = getval('mtyrepositions', 'id', $tyreinfo->position_id, 'category_name');

            // $position_type_id = getval('mtyrepositions', 'id', $tyreinfo->position_id, 'type');
            // return $project_site_id;die;
            // $tyrefitman_name = getval('tyrefitmanremovalinfos','id',$tyreinfo->tfr_id,'name');

            $dataTyreEntry = (object)[];
            $dataTyreEntry->id = $tyreinfo->id;
            $dataTyreEntry->tyretype_id = $tyretype_id;
            $dataTyreEntry->tyretype_name = $tyretype_name;
            $dataTyreEntry->tyresize_id = $tyreinfo->tyresize_id;
            $dataTyreEntry->tyresize_name = $tyresize_name;
            $dataTyreEntry->ponumber = $tyreinfo->ponumber;
            $dataTyreEntry->make_id = $tyreinfo->make_id;
            $dataTyreEntry->make_name = $make_name;
            $dataTyreEntry->tyre_no = $tyreinfo->tyre_no;
            $dataTyreEntry->current_status = $tyreinfo->current_status;
            $dataTyreEntry->otl = $tyreinfo->otl;
            $dataTyreEntry->otd = $tyreinfo->otd;
            $dataTyreEntry->tyre_info_id = $tyreinfo->tyre_info_id;
            $dataTyreEntry->tyre_info_name = $tyre_info_name;
            $dataTyreEntry->project_site_id = $project_site_id;
            $dataTyreEntry->project_id = $tyreinfo->project_id;
            $dataTyreEntry->project_name = $project_name;
            $dataTyreEntry->truck_make_id = $truck_make_id;
            $dataTyreEntry->truck_make_name = $truck_make_name;
            $dataTyreEntry->truck_modal_id = $tyreinfo->truck_modal_id;
            $dataTyreEntry->truck_modal_name = $truck_modal_name;
            $dataTyreEntry->position_id = $tyreinfo->position_id;
            $dataTyreEntry->position_type_id = $tyreinfo->position_id;
            $dataTyreEntry->position_name  = $position_name;
            $dataTyreEntry->truck_no = $tyreinfo->truck_no;
            $dataTyreEntry->fitmandate = $tyreinfo->fitmandate;
            $dataTyreEntry->remark = $tyreinfo->remark;
            $dataTyreEntry->tyre_site_id = $tyreinfo->tyre_site_id;
            $dataTyreEntry->type = $tyreinfo->type;
            $dataTyreEntry->service_date = $tyreinfo->service_date;
            $dataTyreEntry->lbsr = $tyreinfo->lbsr;
            $dataTyreEntry->tfr_id = $tyreinfo->tfr_id;
            // $dataTyreEntry->rtd_a = $tyreinfo->rtd_a;
            // $dataTyreEntry->rtd_b = $tyreinfo->rtd_b;
            $dataTyreEntry->current_hmr = $tyreinfo->current_hmr;
            $dataTyreEntry->status = $tyreinfo->status;
            $dataTyreEntry->operatorid = $tyreinfo->operatorid;
            $transTyreEntryList[] = $dataTyreEntry;
        }
        return response()->json($transTyreEntryList);
    }

    // get site Tyre Entry Insert
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
        $tyreinformations->ponumber = $request->ponumber;
        $tyreinformations->make_id =  $request->make_id;
        $tyreinformations->tyre_no =  strtoupper($request->tyre_no);
        $tyreinformations->current_status = 'running';
        $tyreinformations->otl =  $request->otl;
        $tyreinformations->otd =  $request->otd;
        $tyreinformations->status =  $request->status;
        $tyreinformations->operatorid = $request->operatorid;

        $existingTyre_no = Tyreinformation::where('tyre_no', $tyreinformations->tyre_no)->first();
        if ($existingTyre_no) {
            // Handle duplicate Email Id
            $obj = ["Status" => false, "success" => 0, "errors" => 'Tyre Sr. Number has already been taken.'];
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
            $tyresiteinfos->truck_no = $request->truck_no;
            $tyresiteinfos->otl = $request->otl;
            $tyresiteinfos->fitmandate = $request->service_date;
            $tyresiteinfos->removaldate = $request->removaldate;
            $tyresiteinfos->replacedate = $request->replacedate;
            $tyresiteinfos->front_life = $request->front_life ?? '0';
            $tyresiteinfos->rear_life = $request->rear_life ?? '0';
            $tyresiteinfos->repair_life = $request->repair_life ?? '0';
            $tyresiteinfos->current_status = 'running';
            $tyresiteinfos->remark = '1';
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
            $tyrefitmanremovalinfos->tyre_site_id = $tyresite_id;
            $tyrefitmanremovalinfos->type =  'fitman';
            $tyrefitmanremovalinfos->service_date =  $request->service_date;
            $tyrefitmanremovalinfos->lbsr =  $request->lbsr ?? ($request->current_hmr ?? 0);
            $tyrefitmanremovalinfos->remark = '1';

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
            $tyreperformanceinfos->hcicm =  $request->hcicm ?? '0';
            $tyreperformanceinfos->service_date =  $request->service_date;
            $tyreperformanceinfos->fl =  $request->fl ?? '0';
            $tyreperformanceinfos->rl =  $request->rl ?? '0';
            $tyreperformanceinfos->repaire_life =  $request->repaire_life ?? '0';
            $tyreperformanceinfos->remark =  '1';
            $tyreperformanceinfos->operatorid =  $request->operatorid;

            $tyreperformanceinfos->save();
            return response()->json(['message' => 'Tyre Tyre Entry Added successfully!']);
        } catch (\Exception $ex) {
            return $ex;
            $obj = ["Status" => false, "success" => 0, "msg" => "Tyre Tyre Entry Not Added!!"];
            return response()->json($obj);
        }
    }

    // get site Tyre  Entry Update
    public function updateTyreEntry(Request $request)
    {
        // return "ook";die;
        // Validate incoming request
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
        // Find the existing tyre information by its ID
        $tyreinformations = Tyreinformation::find($request->id);

        if (!$tyreinformations) {
            // Handle error if the tyre entry doesn't exist
            $obj = ["Status" => false, "success" => 0, "msg" => "Tyre entry not found!"];
            return response()->json($obj);
        }

        // Update tyre information
        $tyreinformations->update([
            'tyresize_id' => $request->tyresize_id,
            'ponumber' => $request->ponumber,
            'make_id' => $request->make_id,
            'tyre_no' => $request->tyre_no,
            'current_status' => 'running',
            'otl' => $request->otl,
            'otd' => $request->otd,
            'status' => $request->status,
            'operatorid' => $request->operatorid,
        ]);

        // Check for duplicate tyre_no in case the tyre number was changed
        if ($tyreinformations->tyre_no !== $request->tyre_no) {
            $existingTyre_no = Tyreinformation::where('tyre_no', $request->tyre_no)->first();
            if ($existingTyre_no) {
                $obj = ["Status" => false, "success" => 0, "errors" => 'Tyre Number has already been taken.'];
                return response()->json($obj);
            }
        }

        try {
            $tyreinformations->save();

            // Find the corresponding tyre site information and update it
            $tyresiteinfos = Tyresiteinfos::where('tyre_info_id', $request->id)->first();
            if (!$tyresiteinfos) {
                $obj = ["Status" => false, "success" => 0, "msg" => "Tyre site info not found!"];
                return response()->json($obj);
            }
            // Update site information
            $tyresiteinfos->update([
                'project_id' => $request->project_id,
                'truck_modal_id' => $request->truck_modal_id,
                'position_id' => $request->position_id,
                'truck_no' => $request->truck_no,
                'otl' => $request->otl,
                'fitmandate' => $request->service_date,
                'removaldate' => $request->removaldate,
                'replacedate' => $request->replacedate,
                'front_life' => $request->front_life ?? '0',
                'rear_life' => $request->rear_life ?? '0',
                'repair_life' => $request->repair_life ?? '0',
                'current_status' => 'running',
                'remark' => '1',
                'status' => $request->status,
                'operatorid' => $request->operatorid,
            ]);
            $tyresiteinfos->save();

            // Update tyre fitment removal information
            $tyrefitmanremovalinfos = Tyrefitmanremovalinfo::where('tyre_site_id', $tyresiteinfos->id)->first();
            if (!$tyrefitmanremovalinfos) {
                $obj = ["Status" => false, "success" => 0, "msg" => "Tyre fitment removal info not found!"];
                return response()->json($obj);
            }
            $tyrefitmanremovalinfos->update([
                'type' => 'fitman',
                'service_date' => $request->service_date,
                'lbsr' => $request->lbsr ?? ($request->current_hmr ?? 0),
                'remark' => '1',
            ]);
            $tyrefitmanremovalinfos->save();

            // Update tyre performance information
            $tyreperformanceinfos = Tyreperformanceinfo::where('tyre_site_id', $tyresiteinfos->id)->first();
            if (!$tyreperformanceinfos) {
                $obj = ["Status" => false, "success" => 0, "msg" => "Tyre performance info not found!"];
                return response()->json($obj);
            }
            $tyreperformanceinfos->update([
                'tfr_id' => $tyrefitmanremovalinfos->id,
                'rtd_a' => $request->rtd_a ?? '0',
                'rtd_b' => $request->rtd_b ?? '0',
                'current_hmr' => $request->current_hmr ?? ($request->lbsr ?? 0),
                'lbsr' => $request->lbsr ?? ($request->current_hmr ?? 0),
                'hcicm' => $request->hcicm ?? '0',
                'service_date' => $request->service_date,
                'fl' => $request->fl ?? '0',
                'rl' => $request->rl ?? '0',
                'repaire_life' => $request->repaire_life ?? '0',
                'remark' => '1',
                'operatorid' => $request->operatorid,
            ]);
            $tyreperformanceinfos->save();

            return response()->json(['message' => 'Tyre entry updated successfully!']);
        } catch (\Exception $ex) {
            return response()->json(["Status" => false, "success" => 0, "msg" => "Error updating tyre entry!", "error" => $ex->getMessage()]);
        }
    }

    // get site Tyre  Entry Delete
    public function deletetyreentry(Request $request)
    {
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

    // get site project
    public function getsiteProjectFor_SiteAdmin(Request $request)
    {
        // return "okk";die;
        $siteprojects = Siteproject::where('site_id', $request->userid)
            ->where('status', '1')->get();

        if ($siteprojects->isEmpty()) {
            return response()->json(['Status' => false, 'Success' => '0', 'msg' => 'Site Project Not Found!!']);
        }

        $transSiteProject = [];
        foreach ($siteprojects as $project) {
            $dataSiteProject = (object)[];
            $dataSiteProject->id = $project->id;
            $dataSiteProject->project_name = $project->project_name;
            $transSiteProject[] = $dataSiteProject;
        }
        return response()->json($transSiteProject);
    }

    // get Tyre Type And Tyre Size
    public function getTyreTypeTyreSize(Request $request)
    {
        // return "okk";die;
        $mtyresizes = Mtyresize::where('tyretype_id', $request->tyretype_id)
            ->where('status', '1')->get();
        // return  $mtyresizes ;
        if ($mtyresizes->isEmpty()) {
            return response()->json(['Status' => false, 'Success' => '0', 'msg' => 'Tyre Type And Tyre Size Not Found!!']);
        }

        $transTyreSize = [];
        foreach ($mtyresizes as $tyresize) {
            $datatyreSize = (object)[];
            $datatyreSize->id = $tyresize->id;
            $datatyreSize->tyretype_id = $tyresize->tyretype_id;
            $datatyreSize->category_name = $tyresize->category_name;
            $transTyreSize[] = $datatyreSize;
        }
        return response()->json($transTyreSize);
    }

    // get Make
    public function getMake()
    {
        // return "okk";die;
        $mmakes = Mmake::orderBy('id', 'asc')
            ->where('status', '1')->get();
        // return  $mtyresizes ;
        if ($mmakes->isEmpty()) {
            return response()->json(['Status' => false, 'Success' => '0', 'msg' => 'Make Not Found!!']);
        }

        $transMake = [];
        foreach ($mmakes as $make) {
            $dataMake = (object)[];
            $dataMake->id = $make->id;
            $dataMake->category_name = $make->category_name;
            $transMake[] = $dataMake;
        }
        return response()->json($transMake);
    }

    // get Truck Make And Truck Model
    public function getTruckMakeTruckModel(Request $request)
    {
        // return "okk";die;
        $mtruckmodels = Mtruckmodel::where('truckmake_id', $request->truckmakeid)
            ->where('status', '1')->get();
        // return  $mtruckmodels ;
        if ($mtruckmodels->isEmpty()) {
            return response()->json(['Status' => false, 'Success' => '0', 'msg' => 'Truck Make Truck Model Not Found!!']);
        }
        $transTruckModel = [];
        foreach ($mtruckmodels as $truckModel) {
            $dataTruckModel = (object)[];
            $dataTruckModel->id = $truckModel->id;
            $dataTruckModel->truckmake_id = $truckModel->truckmake_id;
            $dataTruckModel->category_name = $truckModel->category_name;
            $transTruckModel[] = $dataTruckModel;
        }
        return response()->json($transTruckModel);
    }

    // get Position Type 
    public function getType()
    {
        // return "okk";die;
        $mtyrepositions = Mtyreposition::orderBy('id', 'desc')
            ->where('status', '1')->get();
        // return  $mtruckmodels ;
        if ($mtyrepositions->isEmpty()) {
            return response()->json(['Status' => false, 'Success' => '0', 'msg' => 'Type Position Not Found!!']);
        }
        $transTyrePosition = [];
        foreach ($mtyrepositions as $tyrepositon) {
            $dataTyrePosition = (object)[];
            $dataTyrePosition->id = $tyrepositon->id;
            $dataTyrePosition->type = $tyrepositon->type;
            // $dataTyrePosition->category_name = $tyrepositon->category_name;
            $transTyrePosition[] = $dataTyrePosition;
        }
        return response()->json($transTyrePosition);
    }

    // get Position Type And Position
    public function getPositionType(Request $request)
    {
        // return "okk";die;
        $mtyrepositions = Mtyreposition::where('id', $request->type)
            ->where('status', '1')->get();
        // return  $mtruckmodels ;
        if ($mtyrepositions->isEmpty()) {
            return response()->json(['Status' => false, 'Success' => '0', 'msg' => 'Type Position Not Found!!']);
        }
        $transTyrePosition = [];
        foreach ($mtyrepositions as $tyrepositon) {
            $dataTyrePosition = (object)[];
            $dataTyrePosition->id = $tyrepositon->id;
            $dataTyrePosition->type = $tyrepositon->type;
            $dataTyrePosition->category_name = $tyrepositon->category_name;
            $transTyrePosition[] = $dataTyrePosition;
        }
        return response()->json($transTyrePosition);
    }

        // get Tyre Information tyre_no 
        public function getUniqueTyreNo()
        {
            // return "okk";die;
            $tyreinformations = Tyreinformation::orderBy('id', 'desc')->get();
            // return  $tyreinformations ;
            if ($tyreinformations->isEmpty()) {
                return response()->json(['Status' => false, 'Success' => '0', 'msg' => 'Type Number Not Found!!']);
            }
            $transTyreInformation = [];
            foreach ($tyreinformations as $tyreNumber) {
                $dataTyreNumber = (object)[];
                $dataTyreNumber->id = $tyreNumber->id;
                $dataTyreNumber->tyre_no = $tyreNumber->tyre_no;
                // $dataTyreNumber->category_name = $tyrepositon->category_name;
                $transTyreInformation[] = $dataTyreNumber;
            }
            return response()->json($transTyreInformation);
        }
}
