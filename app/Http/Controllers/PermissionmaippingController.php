<?php

namespace App\Http\Controllers;

use App\Models\Permissionmaipping;
use App\Models\Pageinfo;
use App\Models\Permissioncategory;
use Illuminate\Http\Request;

class PermissionmaippingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getus()
    {
        return "hello";
        die;
        //$users = User::where('role_id', $roleid)->get();
        // return $users;
        // die;
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request;
        $permissioncat_ids = explode(',', $request->permissioncat_id); // Explode the string into an array
        $page_names = explode(',', $request->pageid);

        try {
            $permissionMappings = [];
            foreach ($page_names as $pagename) {
                // foreach ($permissioncat_ids as $permissioncat_id) {
                    $permissionMapping = new Permissionmaipping();
                    $permissionMapping->userid = $request->userid;
                    $permissionMapping->pageid = $pagename;
                    $permissionMapping->permissioncat_id = json_encode($permissioncat_ids); // Store as a JSON array
                    $permissionMappings[] = $permissionMapping;
                // }
            }
            Permissionmaipping::insert(
                array_map(function ($permissionMapping) {
                    return $permissionMapping->getAttributes();
                }, $permissionMappings)
            );
            return response()->json(['message' => 'Permission Mappings Added successfully!']);
        } catch (\Exception $ex) {
            return $ex;
            return response()->json(['Status' => false, 'success' => 0, 'msg' => 'Permission Mapping Not Added. Failed!', 'error' => $ex->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Permissionmaipping $permissionmaipping)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permissionmaipping $permissionmaipping)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permissionmaipping $permissionmaipping)
    {
        // return $request;
        $permissioncat_ids = explode(',', $request->permissioncat_id); // Explode the string into an array
        $page_names = explode(',', $request->pageid);

        try {
            $permissionMappings = [];
            foreach ($page_names as $pagename) {
                // foreach ($permissioncat_ids as $permissioncat_id) {
                    $permissionMapping = new Permissionmaipping();
                    $permissionMapping->userid = $request->userid;
                    $permissionMapping->pageid = $pagename;
                    $permissionMapping->permissioncat_id = json_encode($permissioncat_ids); // Store as a JSON array
                    $permissionMappings[] = $permissionMapping;
                // }
            }
            Permissionmaipping::insert(
                array_map(function ($permissionMapping) {
                    return $permissionMapping->getAttributes();
                }, $permissionMappings)
            );
            return response()->json(['message' => 'Permission Mappings Added successfully!']);
        } catch (\Exception $ex) {
            return $ex;
            return response()->json(['Status' => false, 'success' => 0, 'msg' => 'Permission Mapping Not Added. Failed!', 'error' => $ex->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permissionmaipping $permissionmaipping)
    {
        //
    }

    public function getpagename(){
        $pages = Pageinfo::orderBy('id','asc')->get();
        // return $pages;
        $transForm = [];
        foreach ($pages as $page) {
            $dataobject = (object)[];
            $dataobject->id =  $page->id;
            $dataobject->pagename =  $page->pagename;
            $transForm[]=$dataobject;
        }
        return response()->json($transForm);
    }

    public function getcategory(){
        $pagecates = Permissioncategory::orderBy('id','asc')->get();
        // return $pages;
        $transForm = [];
        foreach ($pagecates as $page) {
            $dataobject = (object)[];
            $dataobject->id =  $page->id;
            $dataobject->pc_name =  $page->pc_name;
            $transForm[]=$dataobject;
        }
        return response()->json($transForm);
    }
}
