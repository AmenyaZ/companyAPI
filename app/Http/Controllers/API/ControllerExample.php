<?php

namespace App\Http\Controllers\API;


use App\Http\Library\ApiHelpers;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

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
            $role = Roles::find($id);
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
        $data = $request->all();

        if ($this->isAdmin($user)) {
            $validator = Validator::make($data, $this->roleValidationRules());
            if ($validator->fails()) {
                return $this->onError(404, 'Validator Error');
            }
            // Create New Role;
            $Role = new Roles();
            $Role->title = $request->get('title');
            //$Role->title = $request->input('title');
            $Role->slug = Str::slug($request->get('title'));
            $Role->description = $request->get('description');
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
            $Role = Roles::find($id);
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
            $Role = Roles::find($id); // Find the id of the Role passed
            $Role->delete(); // Delete the specific Role data
            if (!empty($Role)) {
                return $this->onSuccess($Role, 'Role Deleted');
            }
            return $this->onError(404, 'Role Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }
}
