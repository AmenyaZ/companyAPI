<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationResource;
use App\Models\organization;
use App\Http\Library\ApiHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use ApiHelpers;

    public function index(Request $request)
    {
        $user = Auth::user();
        // $user = DB::table('users')->select('role')->where('id',1)->first();

        if ($this->isAdmin($user) || $this->isWriter($user) || $this->isSubscriber($user)) {
            $org = organization::all();
            return response(['organizations' => OrganizationResource::collection($org), 'message' => 'Retrieved successfully'], 200);
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
        $data = $request->all();

        $myvalidator = Validator::make($data, [
            'legal_name',
            'physical_location'
        ]);
        $user = Auth::user();
        // $user = DB::table('users')->select('role')->where('id',1)->first();
        if ($this->isAdmin($user) || $this->isWriter($user)) {
            $validator = Validator::make($data, $myvalidator);
            if ($validator->fails()) {
                return response(['error' => $validator->errors(), 'Validation Error']);
            }

            $org = organization::create($data);

            return response(['org' => new OrganizationResource($org), 'message' => 'Created successfully'], 200);
        }
        return $this->onError(401, 'Unauthorized Access');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function show(organization $organization, $id)
    {
        $user = Auth::user();

        // $user = DB::table('users')->select('role')->where('id',1)->first();
        if ($this->isAdmin($user) || $this->isWriter($user) || $this->isSubscriber($user)) {
            $org = organization::find($id);
            return $this->onSuccess($org, 'Retrieved successfully', 200);
           // return response(['org' => new OrganizationResource($organization), 'message' => 'Retrieved successfully'], 200);
        }
        return $this->onError(401, 'Unauthorized Access');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, organization $organization)
    {
        $user = Auth::user();
        // $user = DB::table('users')->select('role')->where('id',1)->first();
        if ($this->isAdmin($user) || $this->isWriter($user)) {
            $organization->update($request->all());

            return response(['org' => new OrganizationResource($organization), 'message' => 'Retrieved successfully'], 200);
        }
        return $this->onError(401, 'Unauthorized Access');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function destroy(organization $organization, Request $request, $id)
    {
        //
        $user = Auth::user();
        // $user = DB::table('users')->select('role')->where('id',1)->first();

        if ($this->isAdmin($user)) {
            $dOrg = organization::find($id); //find id of the Organization
            if (!empty($dOrg)) {
                $dOrg->delete();
                return response(['message' => 'Deleted']);
            }
            return $this->onError(404, 'Organization not found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }
}
