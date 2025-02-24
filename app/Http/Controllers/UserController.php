<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function userlist()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function adduser(Request $request)
    {
        // return $request;
        $validator = Validator::make($request->all(), [
            'role_id' => 'required',
            'name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:12',
            'email' => 'required|email',
            'password' => 'required|string',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            $obj = ["Status" => false, "success" => 0, "errors" => $validator->errors()];
            return response()->json($obj);
        }

        // Create a new user
        $user = new User();
        $user->role_id =  $request->role_id;
        $user->parent_id =  $request->parent_id;
        $user->name = $request->name;
        $user->mobile_no = $request->mobile_no;
        $user->address = $request->address;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $existingEmailId = User::where('email', $user->email)->first();
        if ($existingEmailId) {
            // Handle duplicate Email Id
            $obj = ["Status" => false, "success" => 0, "errors" => 'The email has already been taken.'];
            return response()->json($obj);
        }
        try {
            $user->save();
            return response()->json(['message' => 'User Added successfully!']);
        } catch (\Exception $ex) {
            return $ex;
            $obj = ["Status" => false, "success" => 0, "msg" => "User Not Added Faild!"];
            return response()->json($obj);
        }
    }

    public function updateuser(Request $request, $id)
    {
        // return $request;
        $validator = Validator::make($request->all(), [
            'role_id' => 'required',
            'name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:12',
            'email' => 'required|email',
            'password' => 'required|string',
            'address' => 'required|',
        ]);

        if ($validator->fails()) {
            $obj = ["Status" => false, "success" => 0, "errors" => $validator->errors()];
            return response()->json($obj);
        }
        // $user = Auth::user();
        try {
            $user = User::findOrFail($id);
            $user->update([
                'role_id' => $request->role_id,
                'parent_id' => $request->parent_id,
                'name' => $request->name,
                'mobile_no' => $request->mobile_no,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'address' => $request->address,
            ]);
            return response()->json(['message' => 'User Updated successfully!']);
        } catch (\Exception $ex) {
            return $ex;
            $obj = ["Status" => false, "success" => 0, "msg" => "User Not Updated!"];
            return response()->json($obj);
        }
    }

    public function deleteuser(Request $request,$id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['message' => 'User deleted successfully!']);
        } catch (\Exception $ex) {
            return $ex;
            return response()->json([
                "Status" => false,
                "success" => 0,
                "msg" => "User Not Deleted!",
                "error" => $ex->getMessage()
            ], 500); // Return 500 status code for server error
        }
    }

    // public function multideleteuser(Request $request)
    // {
    //     $userIds = $request->userid;
    //     // return response()->json($userIds);
    //     if(is_array($userIds) && !empty($userIds)){
    //         $deleted = User::whereIn('userid',$userIds)->delete();
    //         return response()->json([
    //             'message' => 'Users deleted successfully!',
    //             'deleted_count' => $deleted,
    //         ], 200);
    //     }else{
    //         return response()->json([
    //             'message' => 'No valid user IDs provided.',
    //         ], 400);
    //     }
    // }

    public function getroles(){
        $roles = DB::table('roles')->select('roles.*')->get();
        // return response()->json($roles);
        $transformedRoles = [];
        foreach($roles as $role){
            $dataObject=(object)[];
            $dataObject->id=$role->id;
            $dataObject->name=$role->name;
            $transformedRoles[] = $dataObject;
        }
        return response()->json($transformedRoles);
    }

    public function getparentroles(){
        $roles = DB::table('roles')->whereIn('id',[3,4])->get();
        // return response()->json($roles);
        $transformedRoles = [];
        foreach($roles as $role){
            $dataObject=(object)[];
            $dataObject->id=$role->id;
            $dataObject->name=$role->name;
            $transformedRoles[] = $dataObject;
        }
        return response()->json($transformedRoles);
    }
}
