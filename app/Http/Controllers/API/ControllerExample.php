<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Models\roles;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;




use Illuminate\Validation\UnauthorizedException;
use NunoMaduro\Collision\Contracts\Writer;

class ControllerExample extends Controller
{
    use ApiHelpers;


    //display all roles
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();

        if ($this->isAdmin($user)) {
            $Role = DB::table('roles')->get();
            return $this->onSuccess($Role, 'Role Retrieved');
        }

        return $this->onError(401, 'Unauthorized Access');
    }
    //display single role
    public function show(Request $request, $id): JsonResponse
    {
        $user = Auth::user();
        if ($this->isAdmin($user) || $this->isWriter($user) || $this->isSubscriber($user)) {
            $role = roles::find($id);
            //$role = DB::table('roles')->where('id', $request->$id)->first();
            if (!empty($role)) {
                return $this->onSuccess($role, 'Roles Retrieved');
            }
            return $this->onError(404, 'Roles Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }
    //create Role
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        $data = Request::all();

        if ($this->isAdmin($user)) {
            $validator = Validator::make($data, $this->roleValidationRules());
            if ($validator->fails()) {
                return $this->onError(404, 'Validator Error');
            }
            // Create New Role;
            $Role = new roles();
            $Role->title = Request::get('title');
            //$Role->title = $request->input('title');
            $Role->slug = Str::slug(Request::get('title'));
            $Role->description = Request::get('description');
            $Role->save();
            return $this->onSuccess($Role, 'Role Created');
        }

        return $this->onError(401, 'Unauthorized Access');
    }

    public function update(Request $request, $id): JsonResponse
    {
        $user = Auth::user();
        if ($this->isAdmin($user) || $this->isWriter($user)) {
            $validator = Validator::make($request->all(), $this->roleValidationRules());
            if ($validator->fails()) {
                return $this->onError(400, $validator->errors());
            }
            // Update New Role
            $Role = roles::find($id);
            $Role->title = $request->input('title');
            $Role->content = $request->input('content');
            $Role->save();
            return $this->onSuccess($Role, 'Role Updated');
        }
        return $this->onError(401, 'Unauthorized Access');
    }
    public function destroy(Request $request, $id): JsonResponse
    {
        $user = Auth::user();
        if ($this->isAdmin($user) || $this->isWriter($user)) {
            $Role = roles::find($id); // Find the id of the Role passed
            $Role->delete(); // Delete the specific Role data
            if (!empty($Role)) {
                return $this->onSuccess($Role, 'Role Deleted');
            }
            return $this->onError(404, 'Role Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }
    public function createWriter(Request $request)
    {
        $user = Auth::user();
        if ($this->isAdmin($user)) {
            $validator = Validator::make($request->all(), $this->userValidatedRules());
            if ($validator->fails()) {
                return $this->onError(400, $validator->errors());
            }
            // Create New Writer
            $newWriter = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'role' => 2,
                'password' => Hash::make($request->input('password')),
            ]);
            $writerToken = $newWriter->createToken('authToken', ['writer'])->accessToken;
            //return $this->onSuccess($writerToken, 'User Created With Writer Privilege');
            return response(['Writer' => $newWriter, 'Writer Token' => $writerToken]);
        }
        return $this->onError(401, 'Unauthorized Access');
    }
    public function createSubscriber(Request $request)
    {
        //$user = $request->user();
        $user = Auth::user();

        if ($this->isAdmin($user)) {

            $validator = Validator::make($request->all(), $this->userValidatedRules());
            if ($validator->fails()) {
                return $this->onError(400, $validator->errors());
            }
            //Create New Subscriber
            $newSubscriber = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'role' => 3,
                'password' => Hash::make($request->input('password')),
            ]);
            // $data = array(
            //     'name' => $request->input('name'),
            //     'email' => $request->input('email'),
            //     'role' => 3,
            //     'password' => Hash::make($request->input('password')),
            // );

            // $newSubscriber = User::create($data);

            $subscriberToken = $newSubscriber->createToken('authToken', ['subscriber'])->plainTextToken;
            //return $this->onSuccess($SubscriberToken, 'User Created With Subscriber Privilege');
            return response(['Subscriber' => $newSubscriber, 'Subscriber Token' => $subscriberToken]);
        }

        return $this->onError(401, 'Unauthorized Access');
    }

    public function deleteUser(Request $request, $id): JsonResponse
    {
        //$user = $request->user();
        $user = Auth::user();
        if ($this->isAdmin($user)) {
            $duser = User::find($id); // Find the id of the Role passed
            if (!empty($duser)) {
                if ($duser->role !== 1) {

                    $duser->delete(); // Delete the specific user

                    return response($duser, 'User Deleted');
                }
                return $this->onError(401, 'This is an Admin.');
            }
            return $this->onError(404, 'User Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }
}
