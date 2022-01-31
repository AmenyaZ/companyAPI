<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\UsersController;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RoleUser;
use  Illuminate\Support\Facades\Validator;


//use Illuminate\Support\Facades\Validator;

class RoleUserController extends Controller
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

            $ru = RoleUser::all();
            return response($ru);
        }
        return response(401, 'Unauthorized Access');
    
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleUserRequest $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), $request->validated());
        if ($user) {
            if (!$validator) {
                return response(['error' => $validator->errors(), 'Validation Errors']);
            }
            //create roles
            $myrole = new  RoleUser();
            $myrole->role_id = $request->get('role_id');
            $myrole->user_id = $request->get('user_id');
            
            $myrole->save();

            return response(['message' => 'Role  ', $myrole->role_id, 'Succesfully assigned to user id', $myrole->user_id]);
        }
        return response(401, 'Unauthorized Access');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $user = Auth::user();
        if ($user) {
            $myrole = RoleUser::find($id);
            if (!empty($myrole)) {
                return Response($myrole);
            }
            return response(404, 'No role Found');
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
        if ($user) {

            $myrole = RoleUser::find($id);
            if (!empty($myrole)) {
                //create roles
                $myrole->role_id = $request->get('role');
                $myrole->user_id = $request->get('user');
                $myrole->save();
                return response([$myrole, 'message' => 'Role Updated']);
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
        if ($user) {
            $myrole = RoleUser::find($id);
            if (!empty($myrole)) {
                $myrole->delete();
                return response(['message' => 'Role deleted']);
            }
        }
    }
}
