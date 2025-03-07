<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordReset;
use App\Models\Pageinfo;
use App\Models\Permissioncategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\Resetpassword;
use Carbon\Carbon;

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
            // $parentname =  getval('roles', 'id', $userrole->parent_id, 'name');
            $dataObject = (object)[];
            $dataObject->id = $userrole->id;
            $dataObject->role_id = $userrole->role_id;
            // $dataObject->role_name = $userrole->role->name;
            $dataObject->parent_id = $userrole->parent_id;
            // $dataObject->parent_name = $parentname;
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

    public function getuserByid(Request $request)
    {
        // return "okk";die;
        try {
            $users = DB::table('users')
            ->join('roles as roles', 'users.role_id', '=', 'roles.id')  
            ->join('roles as parent_roles', 'users.parent_id', '=', 'parent_roles.id')  
            ->select('users.*','roles.name as role_name', 'parent_roles.name as parent_name') 
            ->where("users.id", $request->id)->get();
            // $users = DB::table('users as tsinfo')
            //     ->join('mtyretypes as ttinfo', 'tsinfo.tyretype_id', '=', 'ttinfo.id')
            //     ->select('tsinfo.tyretype_id as tyretype_id', 'ttinfo.category_name as tyretype', 'tsinfo.category_name as tyresize')
            //     ->where("tsinfo.id", $request->id)->get();

            $obj = ["Status" => true, "success" => 1, 'User For Update' => $users];
            return response()->json($obj);
        } catch (\Exception $ex) {
            return $ex;
            $obj = ["Status" => false, "success" => 0, "msg" => "User For Update Not Found!"];
            return response()->json($obj);
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
            $rolename = getval('roles', 'id', $user->role_id, 'name');
            $parentname = getval('roles', 'id', $user->parent_id, 'name');
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

    public function usersearch(Request $request)
    {
        // Validate the input fields
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'mobile_no' => 'nullable|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "Status" => false,
                "success" => 0,
                "errors" => $validator->errors()
            ]);
        }

        // Initialize query builder
        $query = DB::table('users');

        // Apply search filters if any
        if ($request->has('name') && $request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('email') && $request->email) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->has('mobile_no') && $request->mobile_no) {
            $query->where('mobile_no', 'like', '%' . $request->mobile_no . '%');
        }

        // Get filtered users
        $users = $query->orderBy('id', 'asc')->get();

        // If no users found
        if ($users->isEmpty()) {
            return response()->json([
                "Status" => false,
                "success" => 0,
                "errors" => "No data found."
            ]);
        }

        // Prepare response data
        $arrayObj = [];
        foreach ($users as $user) {
            $rolename = getval('roles', 'id', $user->role_id, 'name');
            $parentname = getval('roles', 'id', $user->parent_id, 'name');

            $dataObj = new \stdClass();
            $dataObj->id = $user->id;
            $dataObj->role_id = $rolename;
            $dataObj->parent_id = $parentname;
            $dataObj->name = $user->name;
            $dataObj->mobile_no = $user->mobile_no;
            $dataObj->email = $user->email;
            $dataObj->address = $user->address;

            $arrayObj[] = $dataObj;
        }

        return response()->json([
            "Status" => true,
            "success" => 1,
            "data" => ['posts' => $arrayObj],
            "msg" => "User List"
        ]);
    }

    public function resetPasswordEmail(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->get();
            if (count($user) > 0) {
                $token = Str::random(40);
                $domain = URL::to('/');
                $url = $domain . '/api/resetPasswordForm?token=' . $token;
                $confirmed = [
                    'url' => $url,
                    'email' => $request->email,
                    'title' => 'Password Reset!!',
                    'body'  => 'Please Click Below Link To Reset Password.',
                ];
                // $data['url'] = $url;
                // $data['email'] = $request->email;
                // $data['title'] = 'Password Reset!!';
                // $data['body']  = 'Please Click Below Link To Reset Password.';
                Mail::to($request->email)->send(new Resetpassword($confirmed));
                // Mail::send('sendresetpassword',['data'=>$data],function($message) use ($data){
                //     $message->to($data['email'])->subject($data['title']);
                // });
                $datetime = Carbon::now()->format('Y-m-d H:i:s');
                PasswordReset::updateOrCreate(
                    ['email' => $request->email],
                    [
                        'email' => $request->email,
                        'token' => $token,
                        'created_at' => $datetime,
                    ]
                );
                return response()->json(['success' => true, 'msg' => 'Please Check Your Email Id!!!']);
            } else {
                return response()->json(['success' => false, 'msg' => 'User Email Not Found!!']);
            }
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
        }
    }

    public function resetPasswordForm(Request $request)
    {
        // $resetData = PasswordReset::where('email',$request->email)->get();
        // $emailid =  $resetData->email;
        $Resetassword_Count_Row = DB::table('password_reset_tokens')
            ->selectRaw('COUNT(*) as total')
            ->where('token', $request->token)
            ->get();
        $totalCount = $Resetassword_Count_Row[0]->total;
        if ($totalCount > 0) {
            $Resetassword = DB::table('password_reset_tokens')
                ->select('password_reset_tokens.*')
                ->where("token", $request->token)->get();
            $emailid = $Resetassword[0]->email;

            $User_Count_Row = DB::table('users')
                ->selectRaw('COUNT(*) as total')
                ->where('email', $emailid)
                ->get();
            $userCount = $User_Count_Row[0]->total;
            if ($userCount > 0) {

                $UserData = DB::table('users')
                    ->select('users.*')
                    ->where("email", $emailid)->get();
                $emailid = $Resetassword[0]->email;
                $msg = $UserData[0]->id;
            } else {
                $msg = "Record not found or link expireed!";
            }
        } else {
            $msg = 'Link expired!';
        }
        return response()->json($msg);
        // if(isset($request->token) && count($resetData)>0){
        //     $user = User::where('email', $resetData[0]['email'])->get();
        //     // return view('resetPassword',compact('user'));
        // }else{
        //     return "404";
        // }
    }

    // public function passwordresent(Request $request)
    // {
    //     $resetData = PasswordReset::where('token',$request->token)->get();

    //     if(isset($request->token) && count($resetData)>0){
    //         $user = User::where('email', $resetData[0]['email'])->get();
    //         return view('resetPassword',compact('user'));
    //     }else{
    //         return "404";
    //     }
    // }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::find($request->id);
        $user->password = Hash::make($request->password);
        $user->save();
        PasswordReset::where('email', $user->email)->delete();
        return "Your Password Change Successfully!!!";
    }

    public function getPage()
    {
        // return "hello";die;
        $Pageinfos = Pageinfo::orderBy('id','desc')->get();
        if ($Pageinfos->isEmpty()) {
            return response()->json(['error' => 'No pages found.'], 404);
        }
        $transPageInfos = [];
        foreach ($Pageinfos as $Page) {
            $dataPage = (object)[];
            $dataPage->id = $Page->id;
            $dataPage->name = $Page->pagename;
            $transPageInfos[] = $dataPage;
        }
        return response()->json($transPageInfos);
    }

    public function getCategory()
    {
        // return "hello";die;
        $permissioncategories = Permissioncategory::orderBy('id','desc')->get();
        if ($permissioncategories->isEmpty()) {
            return response()->json(['error' => 'No pages found.'], 404);
        }
        $transPerCate = [];
        foreach ($permissioncategories as $category) {
            $dataCategory = (object)[];
            $dataCategory->id = $category->id;
            $dataCategory->name = $category->pc_name;
            $transPerCate[] = $dataCategory;
        }
        return response()->json($transPerCate);
    }
}
