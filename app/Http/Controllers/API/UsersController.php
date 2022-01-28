<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;
use Illuminate\Support\Str;
use App\Http\Library\ApiHelpers;
use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\RoleUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Symfony\Component\Console\Input\Input;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use ApiHelpers;

    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function index(Request $request)
    {
        $user = Auth::user();

        if ($this->isAdmin($user)) {

            $myuser = User::all();
            if (($myuser)) {

                //return UserResource::collection($myuser);
                return $this->response(['user' => UserResource::collection($myuser), 'message' => 'Users Retrieved']);
            }
            return response(404, 'No users Available');
        }

        return response(401, 'Unauthorized Access');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $user = Auth::user();
        //$myrole = [2, 3];
        $validator = Validator::make($request->all(), $request->validated());



        if ($this->isAdmin($user)) {
            $validator = $request->validated();
            if (!$validator) {
                return response(['error' => $validator->errors(), 'Validation Error']);
            }
            //Create New User
            $user = new User();
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            //$user->role = $request->get('role');
            $user->password = Hash::make($request->get('password'));
            $user->save();


            // $myId = $user->id;
            $myrole = $request->role;
            $myOrg = $request->organization;

            $user->roles()->attach($myrole);
            $user->organizations()->attach($myOrg);


            $userToken = $user->createToken('authToken')->accessToken;
            return $this->response(['user' => $user, 'Access Token' => $userToken, 'message' => 'Users Created']);
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

        if ($this->isAdmin($user)) {

            $myuser = User::find($id);

            if (!empty($myuser)) {

                return response($myuser);

                //  return response([UserResource::collection($myuser), 'message' => 'User Retrieved']);

                //return $this->response(['user' => UserResource::collection($myuser), 'message' => 'Users Retrieved']);
            }
            return response('User Not Found');
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

        if ($this->isAdmin($user)) {

            $myuser = User::find($id);
            if (!empty($myuser)) {
                $myuser->name = $request->input('name');
                $myuser->email = $request->input('email');
                $user->password = Hash::make($request->get('password'));
                $myuser->save();
                return response(['user' => UserResource::collection($myuser), 'message' => 'Users Updated']);
            }
            return response(404, 'User Not Found');
        }
        return response(401, 'Unauthorized Access');
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
        if ($this->isAdmin($user)) {
            $myuser = User::find($id);
            if (!empty($myuser)) {

                $myuser->delete();
                return response([$myuser, 'message' => 'Users Deleted']);
            }
            return response(404, 'User Not Found');
        }
        return response(401, 'Unauthorized Access');
    }
}
