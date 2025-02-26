<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Cache;

Cache::flush();

class UserController extends Controller
{
    public function userlist()
    {
        $users = User::orderBy('id', 'asc')->get();

        if ($users->isEmpty()) {
            return response()->json(['error' => 'No users list found.'], 404);
        }

        $transformedUserRoles = [];
        foreach ($users as $userrole) {
            $parentname =  getval('roles', 'id', $userrole->parent_id, 'name');
            $dataObject = (object)[];
            $dataObject->role_id = $userrole->role->name;
            $dataObject->parent_id = $parentname;
            $dataObject->name = $userrole->name;
            $dataObject->mobile_no = $userrole->mobile_no;
            $dataObject->email = $userrole->email;
            $dataObject->address = $userrole->address;
            $transformedUserRoles[] = $dataObject;
        }
        return response()->json($transformedUserRoles);
    }

    // public function userlist()
    // {
    //     // Get all users along with their associated role and parent role
    //     $users = DB::table('users')
    //         ->join('roles as roles', 'users.role_id', '=', 'roles.id')  // Joining the roles table for the current role
    //         ->join('roles as parent_roles', 'users.parent_id', '=', 'parent_roles.id')  // Assuming parent_role_id in roles table
    //         ->select('users.*', 'roles.name as role_name', 'parent_roles.name as parent_name')  // Select the user's details, role name, and parent role name
    //         ->orderBy('users.id', 'asc')  // Ordering users by id in ascending order
    //         ->get();
    //     return response()->json($users);
    // }

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

    public function deleteuser(Request $request, $id)
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

    public function getroles()
    {
        // return "hello";
        $roles = DB::table('roles')->select('roles.*')->get();
        if ($roles->isEmpty()) {
            return response()->json(['error' => 'No roles found.'], 404);
        }
        $transformedRoles = [];
        foreach ($roles as $role) {
            $dataObject = (object)[];
            $dataObject->id = $role->id;
            $dataObject->name = $role->name;
            $transformedRoles[] = $dataObject;
        }
        return response()->json($transformedRoles);
    }

    public function getparentroles()
    {
        $roles = DB::table('roles')->whereIn('id', [1, 2])->get();
        // return response()->json($roles);
        $transformedRoles = [];
        foreach ($roles as $role) {
            $dataObject = (object)[];
            $dataObject->id = $role->id;
            $dataObject->name = $role->name;
            $transformedRoles[] = $dataObject;
        }
        return response()->json($transformedRoles);
    }

    // public function GetUserbyRoleId($role_id, $parent_id)
    // {
    //     if (!$role_id) {
    //         $obj = ["Status" => false, "success" => 0, "errors" => "Invalid Role Id"];
    //         return response()->json($obj);
    //     }
    //     if (!$parent_id) {
    //         $obj = ["Status" => false, "success" => 0, "errors" => "Invalid Parent Id"];
    //         return response()->json($obj);
    //     }
    //     $userdatas = DB::table('users')
    //         ->select('users.*')
    //         ->where("role_id", $role_id)
    //         ->where('parent_id', $parent_id)
    //         ->orderBy('id', 'desc')->get();

    //     $arrayObj = [];

    //     foreach ($userdatas as $userdata) {

    //         $dataObje = (object)[];
    //         $dataObje->id = $userdata->id;
    //         $dataObje->name = $userdata->name;
    //         $dataObje->email = $userdata->email;
    //         $dataObje->mobile_no = $userdata->mobile_no;
    //         $dataObje->address = $userdata->address;

    //         $arrayObj[] = $dataObje;
    //     }
    //     if (count($arrayObj) > 0) {
    //         $obj = ["Status" => true, "success" => 1, "data" => ['Users' => $arrayObj], "msg" => "User List"];
    //         return response()->json($obj);
    //     } else {
    //         $obj = ["Status" => false, "success" => 0, "errors" => "No data found."];
    //         return response()->json($obj);
    //     }
    // }

    public function getUserByRoleId(Request $request)
    { 
                    // return $request;die;
        $role_id = $request->query('role_id');
        $parent_id = $request->query('parent_id');

        if (!$role_id) {
            return response()->json(["Status" => false, "success" => 0, "errors" => "Invalid Role Id!!"]);
        }
        if (!$parent_id) {
            return response()->json(["Status" => false, "success" => 0, "errors" => "Invalid Parent Id!!"]);
        }
        
        $users = DB::table('users')
            ->select('users.*')
            ->where('role_id', $role_id)
            ->where('parent_id', $parent_id)
            ->orderBy('id', 'Asc')
            ->get();
            
        if ($users->isEmpty()) {
            return response()->json(["Status" => false, "success" => 0, "errors" => "No data found."]);
        }

        $arrayObj = [];
        foreach ($users as $user) {
            $rolename = getval('roles','id',$user->role_id,'name');
            $parentname = getval('roles','id',$user->parent_id,'name');
            $dataObje = new \stdClass();
            $dataObje->role_id = $rolename;
            $dataObje->parent_id = $parentname;
            $dataObje->name = $user->name;
            $dataObje->mobile_no = $user->mobile_no;
            $dataObje->email = $user->email;
            $dataObje->address = $user->address;
            
            $arrayObj[] = $dataObje;
        }

        return response()->json(["Status" => true, "success" => 1, "data" => ['posts' => $arrayObj], "msg" => "User List"]);
    }

    // public function sendRentLinkEmail(Request $request)
    // {
    //     // Validate the email
    //     // return $request;
    //     $request->validate(['email' => 'required|email']);
    //     // Send the rent reset link
    //     $status = Password::sendRentLink($request->only('email'));
    //     // Check if the reset link was sent successfully
    //     if ($status === Password::RESET_LINK_SENT) {
    //         return response()->json(['message' => $status], 200);
    //     }
    //     // If something goes wrong, return an error response
    //     return response()->json(['message' => 'Failed to send reset link. Please try again.'], 500);
    // }


    // public function resetPassword(Request $request)
    // {
    //     return $request;
    // }
}
