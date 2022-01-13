<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Library\ApiHelpers;
use App\Models\roles;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class CreateUsers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use ApiHelpers;


    public function index()
    {
        $user = Auth::user();

        if ($this->isAdmin($user)) {
            $myusers = DB::table('users')->get();
            return $this->onSuccess($myusers, 'Users Retrieved');
        }

        return $this->onError(401, 'Unauthorized Access');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $user = Auth::user();

        if ($this->isAdmin($user)) {

            $validator = Validator::make($request->all(), $this->userValidatedRules());
            if ($validator->fails()) {
                return $this->onError(400, $validator->errors());
            }
            //Create New User
            $newSubscriber = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'role' => 3,
                'password' => Hash::make($request->input('password')),
            ]);
            

            $subscriberToken = $newSubscriber->createToken('authToken', ['subscriber'])->plainTextToken;
            return response(['Subscriber' => $newSubscriber, 'Subscriber Token' => $subscriberToken]);
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
        //
        $user = Auth::user();

        if ($this->isAdmin($user)) {

            $myuser = User::find($id);
            
            return $this->onSuccess($myuser, 'Retrieved successfully', 200);
        }
        return $this->onError(401, 'Unauthorized Access');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, $id)
    {
        //
        $myuser = Auth::user();

        if ($this->isAdmin($user)) {

            $myuser = User::find($id);
            $myuser = roles::find($id);
            $myuser->name = $request->input('name');
            $myuser->email = $request->input('email');
            $myuser->save();
            return $this->onSuccess($myuser, 'Role Updated');
        }
        return $this->onError(401, 'Unauthorized Access');
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
            $myuser->delete();
            if (!empty($myuser)) {
                return $this->onSuccess($myuser, 'Role Deleted');
            }
            return $this->onError(404, 'Role Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }
}
