<?php

namespace App\Http\Controllers\API;


use App\Http\Library\ApiHelpers;
use App\Models\Role;
use App\Models\User;
use App\Http\Resources\RoleResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class RolesController extends Controller
{
    use ApiHelpers;


    //display all roles
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();

        if ($this->isAdmin($user) || $this->isUser($user)) {
           // $role = DB::table('roles')->get();
            $role =Role::all();
            if (!empty($role)) {
                return $this->onSuccess(['role'=> RoleResource::collection($role), 'message' => 'Role Retrieved']);
            }
            return $this->onError(404, 'No Roles Found');
        }

        return $this->onError(401, 'Unauthorized Access');
    }
    //display single role
    public function show(Request $request, $id): JsonResponse
    {
        $user = Auth::user();
        if ($this->isAdmin($user) || $this->isUser($user)) {
            $role = Role::find($id);
            //$role = DB::table('roles')->where('id', $request->$id)->first();
            if (!empty($role)) {
                return $this->onSuccess(['role'=> RoleResource::collection($role), 'message' => 'Role Retrieved']);
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
            $role = new Role();
            $role->title = $request->get('title');
            //$Role->title = $request->input('title');
            $role->slug = Str::slug($request->get('title'));
            $role->description = $request->get('description');
            $role->save();
            return $this->onSuccess(['role'=> RoleResource::collection($role), 'message' => 'Role Created']);
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
            $role = Role::find($id);
            if (!empty($role)) {
            $role->title = $request->input('title');
            $role->content = $request->input('content');
            $role->save();
            return $this->onSuccess(['role'=> RoleResource::collection($role), 'message' => 'Role Updated']);

            }
            return $this->onError(404, 'Role Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }
    public function destroy(Request $request, $id): JsonResponse
    {
        $user = Auth::user();
        if ($this->isAdmin($user) || $this->isWriter($user)) {
            $role = Role::find($id); // Find the id of the Role passed
            $role->delete(); // Delete the specific Role data
            if (!empty($Role)) {
                return $this->onSuccess(['role'=> RoleResource::collection($role), 'message' => 'Role Deleted']);

            }
            return $this->onError(404, 'Role Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }
}
