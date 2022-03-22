<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizationUserRequest;
use App\Models\OrganizationUser;
use  Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = Auth::user();

        if ($user) {

            $myorg = OrganizationUser::all();
            return response($myorg);
        }
        return response(401, 'Unauthorized Access');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrganizationUserRequest $request)
    {
        $user = Auth::user();
       // $validator = Validator::make($request->all(), $request->validated());
       $validator = $request->validated();
        if ($user->id == 1) {
          
        //if ($this->isAdmin($user)){
            if (!$validator) {
                return response(['error' => $validator->errors(), 'Validation Failed']);
            }
            $myorg = new OrganizationUser();
            $myorg->organization_id = $request->get('organization_id');
            $myorg->user_id = $request->get('user_id');
            $myorg->save();
            return response(['message' => 'User Succesfully added to Organization']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $user = Auth::user();

        if ($user) {
            $myorg = OrganizationUser::find($id);
            return response(["Organization" => $myorg ,"Retrieved"]);
        }
        return response(401, 'Unauthorized Access');
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = Auth::user();
        $myorg = OrganizationUser::find($id);
        if ($user->id == 1) {
            if (!empty($myorg)) {
                //assign Organizations
                $myorg->organization_id = $request->get('organization_id');
                $myorg->user_id = $request->get('user_id');
                $myorg->save();
                return response(['message' => 'User Organization Succesfully updated']);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = Auth::user();
        if ($user->id == 1) {
            $myorg = OrganizationUser::find($id);
            if (!empty($myorg)){
                $myorg ->delete();
                return response(['message'=>'user removed from organization']);
            }
            return response(401, 'user not a avilable');
        }
    }
}
