<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mtyreposition;
use Illuminate\Support\Facades\Validator;

class MtyrepositionController extends Controller
{
    public function tyrepositionlist()
    {
        $mtyrepositions = Mtyreposition::orderBy('id','desc')->get();

        $transTyrePosition = [];
        foreach ($mtyrepositions as $mtyreposition) {
            $datatyreposition = (object)[];
            $datatyreposition->category_name = $mtyreposition->category_name;
            $datatyreposition->status = $mtyreposition->status;
            $datatyreposition->operatorid = $mtyreposition->operatorid;
            $transTyrePosition[] = $datatyreposition;
        }
        return response()->json($transTyrePosition);
    }

    public function inserttyreposition(Request $request)
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
        $mtyrepositions = new Mtyreposition();
        $mtyrepositions->category_name =  $request->category_name;
        $mtyrepositions->status =  $request->status;
        $mtyrepositions->operatorid = $request->operatorid;

        $existingTyrePosition = Mtyreposition::where('category_name', $mtyrepositions->category_name)->first();
        if ($existingTyrePosition) {
            // Handle duplicate Email Id
            $obj = ["Status" => false, "success" => 0, "errors" => 'The tyre Position has already been taken.'];
            return response()->json($obj);
        }
        try {
            $mtyrepositions->save();
            return response()->json(['message' => 'Tyre Position Added successfully!']);
        } catch (\Exception $ex) {
            return $ex;
            $obj = ["Status" => false, "success" => 0, "msg" => "Tyre Position Not Added!!"];
            return response()->json($obj);
        }
    }

    public function updatetyreposition(Request $request, $id)
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
            $mtyrepositions = Mtyreposition::findOrFail($id);
            $mtyrepositions->update([
                'category_name'=>$request->category_name,
            ]);
            return response()->json(['message'=>'Tyre Position Updated SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "Tyre Position Not Updated!!"];
            return response()->json($err);
        }
    }

    public function deletetyreposition($id){
        // return "ok";die;
        try {
            $mtyrepositions = Mtyreposition::findOrFail($id);
            $mtyrepositions->delete();
            return response()->json(['message'=>'Tyre Position Deleted SuccessFully!!']);
        } catch (\Exception $ex) {
            return $ex;
            $err = ["Status" => false, "success" => 0, "msg" => "Tyre Position Not Deleted!!"];
            return response()->json($err);
        }
    }
}
