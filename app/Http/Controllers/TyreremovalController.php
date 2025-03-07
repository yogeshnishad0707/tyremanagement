<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Tyreinformation;
use App\Models\Tyresiteinfos;
use App\Models\Tyrefitmanremovalinfo;
use App\Models\Tyreperformanceinfo;
use App\Models\Siteproject;
use App\Models\Mtyresize;
use App\Models\Mmake;
use App\Models\Mtruckmodel;
use App\Models\Mtyreposition;

class TyreremovalController extends Controller
{
    // get site Tyre Removal Insert
    public function insertTyreRemoval(Request $request)
    {
        // return "hello";die;
        $validator = Validator::make($request->all(), [
            'tyre_info_id' => 'required',
        ]);

        if ($validator->fails()) {
            $obj = ["Status" => false, "success" => 0, "errors" => $validator->errors()];
            return response()->json($obj);
        }
        //create tyre information
        $tyrefitmanremovalinfos = new Tyrefitmanremovalinfo();
        // $tyrefitmanremovalinfos->tyre_info_id = $request->tyre_info_id;
        $tyrefitmanremovalinfos->service_date =  $request->service_date;
        $tyrefitmanremovalinfos->type =  'removal';
        $tyrefitmanremovalinfos->lbsr =  $request->lbsr ?? ($request->current_hmr ?? 0);
        // $tyrefitmanremovalinfos->remark = '1st fitman';

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
            // $tyreperformanceinfos->tyre_site_id = $tyresite_id;
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
            // $tyreperformanceinfos->remark =  '1st fitman';
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
            // if($request->current_status == 'scrap'){
            $tyresiteinfos = Tyresiteinfos::find($request->tyre_info_id);
            if (!$tyreinformations) {
                $obj = ["Status" => false, "success" => 0, "msg" => "Tyre Information not found!"];
                return response()->json($obj);
            }
            $tyresiteinfos->current_status = $request->current_status;
            $tyresiteinfos->save();
            // }

            return response()->json(['message' => 'Tyre Removal Added successfully!']);
        } catch (\Exception $ex) {
            return $ex;
            $obj = ["Status" => false, "success" => 0, "msg" => "Tyre Removal Not Added!!"];
            return response()->json($obj);
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
        $tyresiteinfos = Tyresiteinfos::where('tyre_info_id', $request->id)->where('current_status','running')
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

    // get count fitman api
    public function getCountFitmanById(Request $request)
    {
        // return "okk";die;
        $tyrefitmanremovalinfos = Tyresiteinfos::where('tyre_site_id', $request->tyre_site_id)
            ->where('status', '1')->count();
            return $tyrefitmanremovalinfos ;
        
    }
}
