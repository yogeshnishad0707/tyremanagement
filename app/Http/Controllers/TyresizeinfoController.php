<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tyresizeinfo;
use App\Models\Siteproject;
use App\Models\Mtruckmodel;
use App\Models\Tyreinformation;
use App\Models\Mtyreposition;
use Illuminate\Support\Facades\Validator;

class TyresizeinfoController extends Controller
{
    public function tyresitelist()
    {
        $tyresizeinfos = Tyresizeinfo::orderBy('id','desc')->get();

        $transTyreSite = [];
        foreach ($tyresizeinfos as $tyresiteinfo) {
            $projectname = getval('siteprojects','id',$tyresiteinfo->project_id,'project_name');
            $truckmodel = getval('mtruckmodels','id',$tyresiteinfo->truck_modal_id,'category_name');
            $tyreinfo = getval('tyreinformations','id',$tyresiteinfo->tyre_info_id,'make');
            $tyreposition = getval('mtyrepositions','id',$tyresiteinfo->position_id,'category_name');

            $dataTyreSiteInfo = (object)[];
            $dataTyreSiteInfo->id = $tyresiteinfo->id;
            $dataTyreSiteInfo->project_id = $projectname;
            $dataTyreSiteInfo->truck_modal_id = $truckmodel;
            $dataTyreSiteInfo->tyre_info_id = $tyreinfo;
            $dataTyreSiteInfo->position_id = $tyreposition;
            $dataTyreSiteInfo->ponumber = $tyresiteinfo->ponumber;
            $dataTyreSiteInfo->truck_no = $tyresiteinfo->truck_no;
            $dataTyreSiteInfo->otl = $tyresiteinfo->otl;
            $dataTyreSiteInfo->fitmandate = $tyresiteinfo->fitmandate;
            $dataTyreSiteInfo->removaldate = $tyresiteinfo->removaldate;
            $dataTyreSiteInfo->replacedate = $tyresiteinfo->replacedate;
            $dataTyreSiteInfo->curr_status = $tyresiteinfo->curr_status;
            $dataTyreSiteInfo->remark = $tyresiteinfo->remark;
            $dataTyreSiteInfo->status = $tyresiteinfo->status;
            $dataTyreSiteInfo->operatorid = $tyresiteinfo->operatorid;
            $transTyreSite[] = $dataTyreSiteInfo;
        }
        return response()->json($transTyreSite);
    }

    public function inserttyresiteinfo(Request $request)
    {
        // return "hello";die;
        $validator = Validator::make($request->all(), [
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

        // Create a new tyre site entry
        $tyresizeinfos = new Tyresizeinfo();
        $tyresizeinfos->project_id = $request->project_id;
        $tyresizeinfos->truck_modal_id = $request->truck_modal_id;
        $tyresizeinfos->tyre_info_id = $request->tyre_info_id;
        $tyresizeinfos->position_id = $request->position_id;
        $tyresizeinfos->ponumber = $request->ponumber;
        $tyresizeinfos->truck_no = $request->truck_no;
        $tyresizeinfos->otl = $request->otl;
        $tyresizeinfos->fitmandate = $request->fitmandate;
        $tyresizeinfos->removaldate = $request->removaldate;
        $tyresizeinfos->replacedate = $request->replacedate;
        $tyresizeinfos->curr_status = $request->curr_status;
        $tyresizeinfos->remark = $request->remark;
        $tyresizeinfos->status = $request->status;
        $tyresizeinfos->operatorid = $request->operatorid;

        $existingTyreSiteiInfo = Tyresizeinfo::where('ponumber', $tyresizeinfos->ponumber)->first();
        if ($existingTyreSiteiInfo) {
            // Handle duplicate Email Id
            $obj = ["Status" => false, "success" => 0, "errors" => 'The P.O. Number has already been taken.'];
            return response()->json($obj);
        }
        try {
            $tyresizeinfos->save();
            return response()->json(['message' => 'Tyre Site Info Added successfully!']);
        } catch (\Exception $ex) {
            return $ex;
            $obj = ["Status" => false, "success" => 0, "msg" => "Tyre Site Info Not Added!!"];
            return response()->json($obj);
        }
    }

    public function updatetyresiteinfo(Request $request, $id)
    {
        // return "ok";
        $validator = Validator::make($request->all(),[
            'project_id' => 'required',
            'truck_modal_id' => 'required',
            'tyre_info_id' => 'required',
            'position_id' => 'required',
            'ponumber' => 'required',
            'truck_no' => 'required',
        ]);
        if($validator->fails()){
            $val = ['Stauts'=> false, 'success'=> '0','errors'=>$validator->errors()];
            return response()->json($val);
        }
        try {
            $tyresizeinfos = Tyresizeinfo::findOrFail($id);
            $tyresizeinfos->update([
                'project_id'=> $request->project_id,
                'truck_modal_id'=> $request->truck_modal_id,
                'tyre_info_id'=> $request->tyre_info_id,
                'position_id'=> $request->position_id,
                'ponumber'=> $request->ponumber,
                'truck_no'=> $request->truck_no,
                'otl'=> $request->otl,
                'fitmandate'=> $request->fitmandate,
                'removaldate'=> $request->removaldate,
                'replacedate'=> $request->replacedate,
                'curr_status'=> $request->curr_status,
                'remark'=> $request->remark,
                'status'=> $request->status,
                'operatorid'=> $request->operatorid,
            ]);
            return response()->json(['message'=>'Tyre Site Info Updated SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "Tyre Site Info Not Updated!!"];
            return response()->json($err);
        }
    }

    public function deletetyresiteinfo($id)
    {
        // return "ok";die;
        try {
            $tyresizeinfos = Tyresizeinfo::findOrFail($id);
            $tyresizeinfos->delete();
            return response()->json(['message'=>'Tyre Site Info Deleted SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "Tyre Site Info Not Deleted!!"];
            return response()->json($err);
        }
    }

    public function getsiteproject()
    {
        // return "okk";die;
        $siteprojects = Siteproject::orderBy('id','desc')->get(); 

        $transSiteProject = [];
        foreach ($siteprojects as $siteproject) {
            $dataSiteProject = (object)[];
            $dataSiteProject->id = $siteproject->id;
            $dataSiteProject->project_name = $siteproject->project_name;
            $transSiteProject[] = $dataSiteProject;
        }
        return response()->json($transSiteProject);
    }

    public function gettruckmodel()
    {
        // return "okk";die;
        $mtruckmodels = Mtruckmodel::orderBy('id','desc')->get(); 

        $transTruckModel = [];
        foreach ($mtruckmodels as $truckmodel) {
            $dataTruckModel = (object)[];
            $dataTruckModel->id = $truckmodel->id;
            $dataTruckModel->category_name = $truckmodel->category_name;
            $transTruckModel[] = $dataTruckModel;
        }
        return response()->json($transTruckModel);
    }

    public function gettyreinfo()
    {
        // return "okk";die;
        $tyreinformations = Tyreinformation::orderBy('id','desc')->get(); 

        $transTyreInfo = [];
        foreach ($tyreinformations as $tyerinfo) {
            $dataTyreInfo = (object)[];
            $dataTyreInfo->id = $tyerinfo->id;
            $dataTyreInfo->make = $tyerinfo->make;
            $transTyreInfo[] = $dataTyreInfo;
        }
        return response()->json($transTyreInfo);
    }

    public function gettyreposition()
    {
        // return "okk";die;
        $mtyrepositions = Mtyreposition::orderBy('id','desc')->get(); 

        $transTyrePosition = [];
        foreach ($mtyrepositions as $tyreposition) {
            $dataTyrePosition = (object)[];
            $dataTyrePosition->id = $tyreposition->id;
            $dataTyrePosition->category_name = $tyreposition->category_name;
            $transTyrePosition[] = $dataTyrePosition;
        }
        return response()->json($transTyrePosition);
    }
}
