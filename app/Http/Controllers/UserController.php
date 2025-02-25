<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    public function index()
    {
        dd('validate request');
    }

    public function userlist()
    {
        // Get all users along with their associated role and parent role
        $users = DB::table('users')
            ->join('roles as roles', 'users.role_id', '=', 'roles.id')  // Joining the roles table for the current role
            ->join('roles as parent_roles', 'users.parent_id', '=', 'parent_roles.id')  // Assuming parent_role_id in roles table
            ->select('users.*', 'roles.name as role_name', 'parent_roles.name as parent_name')  // Select the user's details, role name, and parent role name
            ->orderBy('users.id', 'asc')  // Ordering users by id in ascending order
            ->get();

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
        $roles = DB::table('roles')->select('roles.*')->get();
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

    public function getparentroles()
    {
        $roles = DB::table('roles')->whereIn('id', [3, 4])->get();
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

    public function sendRentLinkEmail(Request $request)
    {
        // Validate the email
        // return $request;
        $request->validate(['email' => 'required|email']);
        // Send the rent reset link
        $status = Password::sendRentLink($request->only('email'));
        // Check if the reset link was sent successfully
        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => $status], 200);
        }
        // If something goes wrong, return an error response
        return response()->json(['message' => 'Failed to send reset link. Please try again.'], 500);
    }


    public function resetPassword(Request $request)
    {
        return $request;
    }
}
