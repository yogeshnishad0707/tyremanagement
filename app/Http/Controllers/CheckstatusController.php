<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maccuratelocation;
use App\Models\Mcutlocation;
use App\Models\Mntccut;
use App\Models\Mtruckmake;
use App\Models\Mtruckmodel;
use App\Models\Mtyrestatustype;
use App\Models\Mtyresize;
use App\Models\Mtyreposition;
use App\Models\Mtyretype;
use App\Models\Mmake;
use App\Models\Siteproject;

class CheckstatusController extends Controller
{
    public function checkstatus(Request $request){
        // return "ok";die;
        if (!isset($request->page_name)) {
            return response()->json(['Status' => false, 'success' => 0, 'msg' => 'Page name is required!']);
        }

        try {
            // now start check status Truckmake page
            if($request->page_name == 'TruckMake'){
                if (!$request->has('id') || !$request->has('status')) {
                    return response()->json(['Status' => false, 'success' => 0, 'msg' => 'ID and Status is required!']);
                }
                $checkstatus = Mtruckmake::findOrFail($request->id);
                $checkstatus->update([
                    'status'=>$request->status,
                ]);
                return response()->json(['message'=>'Status Updated SuccessFully!!']);
            }

            // now start check status TruckModel page
            if($request->page_name == 'TruckModel'){
                if (!$request->has('id') || !$request->has('status')) {
                    return response()->json(['Status' => false, 'success' => 0, 'msg' => 'ID and Status is required!']);
                }
                $checkstatus = Mtruckmodel::findOrFail($request->id);
                $checkstatus->update([
                    'status'=>$request->status,
                ]);
                return response()->json(['message'=>'Status Updated SuccessFully!!']);
            }

            // now start check status TyreType page
            if($request->page_name == 'TyreType'){
                if (!$request->has('id') || !$request->has('status')) {
                    return response()->json(['Status' => false, 'success' => 0, 'msg' => 'ID and Status is required!']);
                }

                $checkstatus = Mtyretype::findOrFail($request->id);
                $checkstatus->update([
                    'status'=>$request->status,
                ]);
                return response()->json(['message'=>'Status Updated SuccessFully!!']);
            }

            // now start check status TyreTypeSize page
            if($request->page_name == 'TyreTypeSize'){
                if (!$request->has('id') || !$request->has('status')) {
                    return response()->json(['Status' => false, 'success' => 0, 'msg' => 'ID and Status is required!']);
                }
                $checkstatus = Mtyresize::findOrFail($request->id);
                $checkstatus->update([
                    'status'=>$request->status,
                ]);
                return response()->json(['message'=>'Status Updated SuccessFully!!']);
            }

            // now start check status AccurateLocation page
            if($request->page_name == 'AccurateLocation'){
                if (!$request->has('id') || !$request->has('status')) {
                    return response()->json(['Status' => false, 'success' => 0, 'msg' => 'ID and Status is required!']);
                }
                $checkstatus = Maccuratelocation::findOrFail($request->id);
                $checkstatus->update([
                    'status'=>$request->status,
                ]);
                return response()->json(['message'=>'Status Updated SuccessFully!!']);
            }

            // now start check status CutLocation page
            if($request->page_name == 'CutLocation'){
                if (!$request->has('id') || !$request->has('status')) {
                    return response()->json(['Status' => false, 'success' => 0, 'msg' => 'ID and Status is required!']);
                }
                $checkstatus = Mcutlocation::findOrFail($request->id);
                $checkstatus->update([
                    'status'=>$request->status,
                ]);
                return response()->json(['message'=>'Status Updated SuccessFully!!']);
            }

            // now start check status Ntc&Tc page
            if($request->page_name == 'Ntc&Tc'){
                if (!$request->has('id') || !$request->has('status')) {
                    return response()->json(['Status' => false, 'success' => 0, 'msg' => 'ID and Status is required!']);
                }
                $checkstatus = Mntccut::findOrFail($request->id);
                $checkstatus->update([
                    'status'=>$request->status,
                ]);
                return response()->json(['message'=>'Status Updated SuccessFully!!']);
            }

            // now start check status TyreStatusType page
            if($request->page_name == 'TyreStatusType'){
                if (!$request->has('id') || !$request->has('status')) {
                    return response()->json(['Status' => false, 'success' => 0, 'msg' => 'ID and Status is required!']);
                }
                $checkstatus = Mtyrestatustype::findOrFail($request->id);
                $checkstatus->update([
                    'status'=>$request->status,
                ]);
                return response()->json(['message'=>'Status Updated SuccessFully!!']);
            }
            
            // now start check status TyrePosition page
            if($request->page_name == 'TyrePosition'){
                if (!$request->has('id') || !$request->has('status')) {
                    return response()->json(['Status' => false, 'success' => 0, 'msg' => 'ID and Status is required!']);
                }
                $checkstatus = Mtyreposition::findOrFail($request->id);
                $checkstatus->update([
                    'status'=>$request->status,
                ]);
                return response()->json(['message'=>'Status Updated SuccessFully!!']);
            }

            // now start check status Make page
            if($request->page_name == 'Make'){
                if (!$request->has('id') || !$request->has('status')) {
                    return response()->json(['Status' => false, 'success' => 0, 'msg' => 'ID and Status is required!']);
                }
                $checkstatus = Mmake::findOrFail($request->id);
                $checkstatus->update([
                    'status'=>$request->status,
                ]);
                return response()->json(['message'=>'Status Updated SuccessFully!!']);
            }
            
            // now start check status Site Project Page
            if($request->page_name == 'SiteProject'){
                if (!$request->has('id') || !$request->has('status')) {
                    return response()->json(['Status' => false, 'success' => 0, 'msg' => 'ID and Status is required!']);
                }
                $checkstatus = Siteproject::findOrFail($request->id);
                $checkstatus->update([
                    'status'=>$request->status,
                ]);
                return response()->json(['message'=>'Status Updated SuccessFully!!']);
            }
        } catch (\Exception $ex) {
            return $ex;
            return response()->json(['Status' => false, 'success' => 0, 'msg' => 'Status Not Updated!', 'error' => $ex->getMessage()]);
        }
    }
}
