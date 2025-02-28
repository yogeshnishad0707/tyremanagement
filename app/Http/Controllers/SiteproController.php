<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siteproject;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class SiteproController extends Controller
{
    public function siteprojectlist()
    {
        $siteprojects = Siteproject::orderBy('id','desc')->get();

        $transSiteProject = [];
        foreach ($siteprojects as $siteproject) {
            $site_name = getval('users','id',$siteproject->site_id,'name');
            $dataSiteProject = (object)[];
            $dataSiteProject->site_id = $site_name;
            $dataSiteProject->project_name = $siteproject->project_name;
            $dataSiteProject->status = $siteproject->status;
            $dataSiteProject->operatorid = $siteproject->operatorid;
            $transSiteProject[] = $dataSiteProject;
        }
        return response()->json($transSiteProject);
    }

    public function insertsiteproject(Request $request)
    {
        // return "hello";die;
        $validator = Validator::make($request->all(), [
            'site_id' => 'required',
            'project_name' => 'required',
        ]);

        if ($validator->fails()) {
            $obj = ["Status" => false, "success" => 0, "errors" => $validator->errors()];
            return response()->json($obj);
        }

        // Create a new user
        $siteprojects = new Siteproject();
        $siteprojects->site_id = $request->site_id;
        $siteprojects->project_name =  $request->project_name;
        $siteprojects->status =  $request->status;
        $siteprojects->operatorid = $request->operatorid;

        // $existingTyreSize = Siteproject::where('category_name', $siteprojects->category_name)->first();
        // if ($existingTyreSize) {
        //     // Handle duplicate Email Id
        //     $obj = ["Status" => false, "success" => 0, "errors" => 'The tyre type has already been taken.'];
        //     return response()->json($obj);
        // }
        try {
            $siteprojects->save();
            return response()->json(['message' => 'Site Project Added successfully!']);
        } catch (\Exception $ex) {
            return $ex;
            $obj = ["Status" => false, "success" => 0, "msg" => "Site Project Not Added!!"];
            return response()->json($obj);
        }
    }

    public function updatesiteproject(Request $request, $id)
    {
        // return "ok";
        $validator = Validator::make($request->all(),[
            'site_id'=>'required',
            'project_name'=>'required',
        ]);
        if($validator->fails()){
            $val = ['Stauts'=> false, 'success'=> '0','errors'=>$validator->errors()];
            return response()->json($val);
        }
        try {
            $siteprojects = Siteproject::findOrFail($id);
            $siteprojects->update([
                'site_id'=>$request->site_id,
                'project_name'=>$request->project_name,
                'status'=>$request->status,
                'operatorid'=>$request->operatorid,
            ]);
            return response()->json(['message'=>'Site Project Updated SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "Site Project Not Updated!!"];
            return response()->json($err);
        }
    }

    public function deletesiteproject($id){
        // return "ok";die;
        try {
            $siteprojects = Siteproject::findOrFail($id);
            $siteprojects->delete();
            return response()->json(['message'=>'Site Project Deleted SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "Site Project Not Deleted!!"];
            return response()->json($err);
        }
    }

    public function getsitename(){
        // return "okk";die;
        $users = User::where('role_id','2')->get(); 

        // if ($mtruckmakes->isEmpty()) {
        //     return response()->json(['error' => 'No Truck Make Found.'], 404);
        // }

        $transSiteName = [];
        foreach ($users as $sitename) {
            $dataSiteName = (object)[];
            $dataSiteName->id = $sitename->id;
            $dataSiteName->name = $sitename->name;
            $transSiteName[] = $dataSiteName;
        }
        return response()->json($transSiteName);
    }
}
