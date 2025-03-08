<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Tyreinformation;
use App\Models\Tyresiteinfos;
use App\Models\Tyrefitmanremovalinfo;
use App\Models\Tyreperformanceinfo;
use App\Models\Mtyrestatustype;
use App\Models\Mtyresize;
use App\Models\Mmake;
use App\Models\Mtruckmodel;
use App\Models\Mtyreposition;

class TyreremovalController extends Controller
{
    // get tyre removal list
    public function tyreRemovallist(){

        $tyrefitmanremovalinfos = Tyrefitmanremovalinfo::join('tyreperformanceinfos', 'tyrefitmanremovalinfos.id', '=', 'tyreperformanceinfos.tfr_id')
            ->join('tyresiteinfos', 'tyreperformanceinfos.tyre_site_id', '=', 'tyresiteinfos.id')  // Added join with the 'tyreinformations' table
            ->where('tyrefitmanremovalinfos.type', 'removal') 
            ->select(
                'tyrefitmanremovalinfos.id',
                'tyrefitmanremovalinfos.tyre_site_id', 
                'tyrefitmanremovalinfos.lbsr',
                'tyrefitmanremovalinfos.service_date',
                'tyrefitmanremovalinfos.type',
                'tyreperformanceinfos.tfr_id',
                'tyreperformanceinfos.tyre_site_id as tyreper_tyre_site_id', 
                'tyreperformanceinfos.rtd_a',
                'tyreperformanceinfos.rtd_b',
                'tyreperformanceinfos.current_hmr',
                'tyreperformanceinfos.lbsr as tyreper_lbsr',
                'tyreperformanceinfos.hcicm',
                'tyreperformanceinfos.service_date as tyreper_service_date',
                'tyreperformanceinfos.fl',
                'tyreperformanceinfos.rl',
                'tyreperformanceinfos.repaire_life',
                'tyreperformanceinfos.remark',
                'tyreperformanceinfos.operatorid',
            )
            ->orderBy('tyrefitmanremovalinfos.id', 'desc')
            ->get(); 

            $transTyreRemovalList = [];
        foreach ($tyrefitmanremovalinfos as $tyreRemoval) {
            $dataTyreRemoval = (object)[];
            $dataTyreRemoval->id = $tyreRemoval->id;
            $dataTyreRemoval->tyre_site_id = $tyreRemoval->tyre_site_id;
            $dataTyreRemoval->lbsr = $tyreRemoval->lbsr;
            $dataTyreRemoval->service_date = $tyreRemoval->service_date;
            $dataTyreRemoval->type = $tyreRemoval->type;
            $dataTyreRemoval->tfr_id = $tyreRemoval->tfr_id;
            $dataTyreRemoval->tyreper_tyre_site_id = $tyreRemoval->tyre_site_id;
            $dataTyreRemoval->rtd_a = $tyreRemoval->rtd_a;
            $dataTyreRemoval->rtd_b = $tyreRemoval->rtd_b;
            $dataTyreRemoval->current_hmr = $tyreRemoval->current_hmr;
            $dataTyreRemoval->tyreper_lbsr = $tyreRemoval->tyreper_lbsr;
            $dataTyreRemoval->hcicm = $tyreRemoval->hcicm;
            $dataTyreRemoval->tyreper_service_date = $tyreRemoval->service_date;
            $dataTyreRemoval->repaire_life = $tyreRemoval->repaire_life;
            $dataTyreRemoval->fl = $tyreRemoval->fl;
            $dataTyreRemoval->rl = $tyreRemoval->rl;
            $dataTyreRemoval->repaire_life = $tyreRemoval->repaire_life;
            $dataTyreRemoval->remark = $tyreRemoval->remark;
            $dataTyreRemoval->operatorid = $tyreRemoval->operatorid;
            $transTyreRemovalList[] = $dataTyreRemoval;

        }
        // Return the results
        return response()->json($transTyreRemovalList);  // Return as JSON response
    }

    // get site Tyre Removal Insert
    public function insertTyreRemoval(Request $request)
    {
        // return "hello";die;
        $validator = Validator::make($request->all(), [
            'tyre_info_id' => 'required',
            'service_date' => 'required',
        ]);

        if ($validator->fails()) {
            $obj = ["Status" => false, "success" => 0, "errors" => $validator->errors()];
            return response()->json($obj);
        }
        $result = DB::table('tyrefitmanremovalinfos')
            ->join('tyresiteinfos', 'tyrefitmanremovalinfos.tyre_site_id', '=', 'tyresiteinfos.id')
            ->join('tyreinformations', 'tyresiteinfos.tyre_info_id', '=', 'tyreinformations.id')
            ->where('tyreinformations.id', $request->tyre_info_id)
            ->where('tyrefitmanremovalinfos.type', 'removal')
            ->where('tyreinformations.current_status', 'running')
            ->select(DB::raw('MAX(tyrefitmanremovalinfos.remark) as max_remark'))
            ->get();

        $max_remark = $result[0]->max_remark;
        //create tyrefitmanremovalinfos
        $tyrefitmanremovalinfos = new Tyrefitmanremovalinfo();
        $tyrefitmanremovalinfos->tyre_site_id = $request->tyre_site_id;
        $tyrefitmanremovalinfos->service_date =  $request->service_date;
        $tyrefitmanremovalinfos->type =  'removal';
        $tyrefitmanremovalinfos->lbsr =  $request->lbsr ?? ($request->current_hmr ?? 0);
        if (!$max_remark) {
            $tyrefitmanremovalinfos->remark = 1;
        } else {
            $tyrefitmanremovalinfos->remark = $max_remark + 1;
        }

        try {
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
            $tyreperformanceinfos->tyre_site_id = $request->tyre_site_id;
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
            if (!$max_remark) {
                $tyreperformanceinfos->remark = 1;
            } else {
                $tyreperformanceinfos->remark = $max_remark + 1;
            }
    
            // $tyreperformanceinfos->current_status =  $request->current_status;
            $tyreperformanceinfos->operatorid =  $request->operatorid;

            $tyreperformanceinfos->save();

            // update tyre information for colunm current_status
            if ($request->current_status == 'scrap') {
                $tyreinformations = Tyreinformation::find($request->tyre_info_id);
                if (!$tyreinformations) {
                    $obj = ["Status" => false, "success" => 0, "msg" => "Tyre Information not found!"];
                    return response()->json($obj);
                }
                $tyreinformations->current_status = $request->current_status;
                $tyreinformations->save();
            }

            // update tyre Site information for colunm current_status
            // $tyresiteinfos = Tyresiteinfos::find($request->tyre_info_id);
            $tyresiteinfos = Tyresiteinfos::where('tyre_info_id',$request->tyre_info_id)->first();
            if (!$tyresiteinfos) {
                $obj = ["Status" => false, "success" => 0, "msg" => "Tyre Site Information not found!"];
                return response()->json($obj);
            }
            $tyresiteinfos->current_status = $request->current_status;
            $tyresiteinfos->save();

            return response()->json(['message' => 'Tyre Removal Added successfully!']);
        } catch (\Exception $ex) {
            return $ex;
            $obj = ["Status" => false, "success" => 0, "msg" => "Tyre Removal Not Added!!"];
            return response()->json($obj);
        }
    }

    // get update tyre removal
    public function updateTyreRemoval(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            // 'tyre_info_id' => 'required',
            'service_date' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(["Status" => false, "success" => 0, "errors" => $validator->errors()]);
        }

        try {
            // Fetch the tyrefitmanremovalinfos record
            $tyrefitmanremovalinfos = Tyrefitmanremovalinfo::find($request->id);

            if (!$tyrefitmanremovalinfos) {
                return response()->json(['Status' => false, 'success' => 0, 'msg' => 'Tyre Removal not found!'], 404);
            }

            // Update the tyrefitmanremovalinfos record
            $tyrefitmanremovalinfos->update([
                'tyre_site_id' => $request->tyre_site_id,
                'service_date' => $request->service_date,
                'lbsr' => $request->lbsr ?? ($request->current_hmr ?? 0),
                // 'remark' => $request->remark ?? 1,  // Remark can be dynamically set
                'type' => 'removal',
            ]);
            $tyrefitmanremovalinfos->save();

            // Fetch the related tyreperformanceinfo record
            $tyreperformanceinfos = Tyreperformanceinfo::where('tfr_id', $tyrefitmanremovalinfos->id)->first();

            if (!$tyreperformanceinfos) {
                return response()->json(['Status' => false, 'success' => 0, 'msg' => 'Tyre performance info not found!']);
            }

            // Update the tyreperformanceinfos record
            $tyreperformanceinfos->update([
                'tyre_site_id' => $request->tyre_site_id,
                // 'tfr_id' => $request->tfr_id,
                'rtd_a' => $request->rtd_a ?? '0',
                'rtd_b' => $request->rtd_b ?? '0',
                'current_hmr' => $request->current_hmr ?? ($request->lbsr ?? 0),
                'lbsr' => $request->lbsr ?? ($request->current_hmr ?? 0),
                'hcicm' => $request->hcicm ?? '0',
                'service_date' => $request->service_date,
                'fl' => $request->fl ?? '0',
                'rl' => $request->rl ?? '0',
                'repaire_life' => $request->repaire_life ?? '0',
                // 'remark' => $request->remark ?? 1,  // Same remark logic
                'operatorid' => $request->operatorid,
            ]);
            $tyreperformanceinfos->save();

            // Return success response
            return response()->json([
                'Status' => true,
                'success' => 1,
                'message' => 'Tyre Removal Updated Successfully!',
            ]);
        } catch (\Exception $ex) {
            // Handle any unexpected errors
            return response()->json([
                'Status' => false,
                'success' => 0,
                'msg' => 'Tyre Removal Not Updated!',
                'error' => $ex->getMessage(),
            ], 500);
        }
    }

    // get delete tyre removal
    public function deleteTyreRemoval(Request $request){
        // return "okk";die;
        try {
            // Fetch the tyrefitmanremovalinfos record
            $tyrefitmanremovalinfos = Tyrefitmanremovalinfo::find($request->id);

            if (!$tyrefitmanremovalinfos) {
                return response()->json(['message' => 'Tyre Removal not found!'], 404);
            }
            // Get the related tyreperformanceinfos record
            $tyreperformanceinfos = Tyreperformanceinfo::where('tfr_id', $tyrefitmanremovalinfos->id)->first();
            if ($tyreperformanceinfos) {
                $tyreperformanceinfos->delete();
            }
            // Delete the tyrefitmanremovalinfos record
            $tyrefitmanremovalinfos->delete();
            return response()->json(['message' => 'Tyre Removal Deleted Successfully!']);
        } catch (\Exception $ex) {
            // Handle the exception and return the error message
            return response()->json(['Status' => false, 'success' => 0, 'msg' => 'Tyre Removal Not Deleted!!', 'error' => $ex->getMessage()]);
        }
    }

    // get tyre sr. number By Id
    public function getTyreInfoById(Request $request)
    {
        // return "okk";die;
        $tyresiteinfos = Tyresiteinfos::join('siteprojects', 'tyresiteinfos.project_id', '=', 'siteprojects.id')
            ->join('tyreinformations', 'tyreinformations.id', '=', 'tyresiteinfos.tyre_info_id')
            ->Where('siteprojects.site_id', $request->userid)
            ->where('tyresiteinfos.current_status', 'running')
            ->select(
                'tyresiteinfos.id as tyre_site_id',
                'tyreinformations.id as tyre_info_id',
                'tyreinformations.tyre_no as tyre_sr_no',
            )
            ->orderBy('tyre_sr_no', 'Asc')
            ->get();
        // return response()->json($tyresiteinfos);
        $transTyreSiteInfo = [];
        foreach ($tyresiteinfos as $tyresiteinfo) {
            $tyrenumber = getval('tyreinformations', 'id', $tyresiteinfo->tyre_info_id, 'tyre_no');

            $dataTyreSiteInfo = (object)[];
            $dataTyreSiteInfo->tyre_site_id = $tyresiteinfo->tyre_site_id;
            $dataTyreSiteInfo->tyre_info_id = $tyresiteinfo->tyre_info_id;
            $dataTyreSiteInfo->tyre_sr_no = $tyrenumber;
            // $dataTyreSiteInfo->project_name = $tyresiteinfo->project_name;
            $transTyreSiteInfo[] = $dataTyreSiteInfo;
        }
        return response()->json($transTyreSiteInfo);
    }

    // get tyre_info_id detail tyre site tablbe basi details
    public function getTyreSiteByIdInfo(Request $request)
    {
        // return "okk";die;
        $tyresiteinfos = Tyresiteinfos::where('tyre_info_id', $request->id)->where('current_status', 'running')
            ->where('status', '1')->get();

        if ($tyresiteinfos->isEmpty()) {
            return response()->json(['Status' => false, 'Success' => '0', 'msg' => 'Tyre Site Info Not Found!!']);
        }

        $transTyreSiteInfo = [];
        foreach ($tyresiteinfos as $tyresite) {
            $tyre_info_name = getval('tyreinformations', 'id', $tyresite->tyre_info_id, 'tyre_no');
            $project_name = getval('siteprojects', 'id', $tyresite->project_id, 'project_name');
            $truck_modal_name = getval('mtruckmodels', 'id', $tyresite->truck_modal_id, 'category_name');
            $position_name = getval('mtyrepositions', 'id', $tyresite->position_id, 'category_name');

            $dataTyreSiteInfo = (object)[];
            $dataTyreSiteInfo->id = $tyresite->id;
            $dataTyreSiteInfo->tyre_info_id = $tyresite->tyre_info_id;
            $dataTyreSiteInfo->tyre_info_name = $tyre_info_name;
            $dataTyreSiteInfo->project_id = $tyresite->project_id;
            $dataTyreSiteInfo->project_name = $project_name;
            $dataTyreSiteInfo->truck_modal_id = $tyresite->truck_modal_id;
            $dataTyreSiteInfo->truck_modal_name = $truck_modal_name;
            $dataTyreSiteInfo->position_id = $tyresite->position_id;
            $dataTyreSiteInfo->position_name = $position_name;
            $dataTyreSiteInfo->truck_no = $tyresite->truck_no;
            $dataTyreSiteInfo->fitmandate = $tyresite->fitmandate;
            $transTyreSiteInfo[] = $dataTyreSiteInfo;
        }
        return response()->json($transTyreSiteInfo);
    }

    // get current status
    public function getcurrentStatus()
    {
        // return "okk";die;
        $mtyrestatustypes = Mtyrestatustype::orderBy('id', 'asc')
            ->where('status', '1')->get();
        // return  $mtyresizes ;
        if ($mtyrestatustypes->isEmpty()) {
            return response()->json(['Status' => false, 'Success' => '0', 'msg' => 'Tyre Status Not Found!!']);
        }

        $transTyreStatus = [];
        foreach ($mtyrestatustypes as $tyreStatus) {
            $dataTyreStatus = (object)[];
            $dataTyreStatus->id = $tyreStatus->id;
            $dataTyreStatus->category_name = $tyreStatus->category_name;
            $transTyreStatus[] = $dataTyreStatus;
        }
        return response()->json($transTyreStatus);
    }

    // get count fitman api
    public function getCountFitmanById(Request $request)
    {
        $result = DB::table('tyrefitmanremovalinfos')
            ->join('tyresiteinfos', 'tyrefitmanremovalinfos.tyre_site_id', '=', 'tyresiteinfos.id')
            ->join('tyreinformations', 'tyresiteinfos.tyre_info_id', '=', 'tyreinformations.id')
            ->where('tyreinformations.id', $request->tyre_info_id)
            ->where('tyrefitmanremovalinfos.type', 'fitment')
            ->where('tyreinformations.current_status', 'running')
            ->select(DB::raw('MAX(tyrefitmanremovalinfos.remark) as max_remark'))
            ->get();
        return $result;
        die;

        if ($count > 0) {
            // Get the current maximum remark value for the given tyre_site_id
            $maxRemark = Tyrefitmanremovalinfo::where('tyre_site_id', $request->tyre_site_id)
                ->max('remark');

            // Increment the max remark value by 1
            $remarks = $maxRemark + 1;

            // Create a new Tyrefitmanremovalinfo instance and set the values
            $fitmans = new Tyrefitmanremovalinfo();
            $fitmans->tyre_site_id = $request->tyre_site_id; // Set the tyre_site_id
            $fitmans->remark = $remarks; // Set the new remark value

            // return $fitmans;
            $fitmans->save(); // Uncomment to actually save the record
        }
        // $count = DB::table('tyrefitmanremovalinfos')
        // ->join('tyreinformations', 'tyrefitmanremovalinfos.id', '=', 'tyreinformations.id')
        // ->select('tyreinformations.id as tyre_info_id', DB::raw('MAX(tyrefitmanremovalinfos.remark) as max_remark'))
        // ->groupBy('tyreinformations.id') // Group by the tyre information ID
        // ->get();



    }
}
