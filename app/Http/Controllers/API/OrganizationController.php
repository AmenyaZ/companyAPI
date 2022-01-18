<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
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

        if ($this->isAdmin($user) || $this->isUser($user)) {
            
            $org = Organization::all();
            if (!empty($org)) {
            return response(['organizations' => OrganizationResource::collection($org), 'message' => 'Organizations Retrieved successfully'], 200);
            }
            return $this->onError(404, 'No Organizations Found');
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
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'legal_name',
            'physical_location',
            'year',
            'company_logo'
        ]);
        // $user = DB::table('users')->select('role')->where('id',1)->first();
        if ($this->isAdmin($user)) {

            if ($validator->fails()) {
                return response(['error' => $validator->errors(), 'Validation Error']);
            }
            $org = new Organization();
            $org->legal_name = $request->get('legal_name');
            $org->physical_location = $request->get('physical_location');
            $org->year = $request->get('year');
            $org->company_logo = $request->file('company_logo');
            $org->save();

            //return "here";

            //$org = Organization::create($data);

            return response(['org' => new OrganizationResource($org), 'message' => 'Organization Created successfully'], 200);
        }
        return $this->onError(401, 'Unauthorized Access');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        //
        $user = Auth::user();

        if ($this->isAdmin($user)) {

            $myOrganization = Organization::find($id);

            if (!empty($myOrganization)) {

                return $this->onSuccess($myOrganization, 'Orgnaization Retrieved successfully', 200);
            }
            return $this->onError(404, 'Organization  Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        // $user = DB::table('users')->select('role')->where('id',1)->first();
        if ($this->isAdmin($user)) {

            $org = Organization::find($id);
            if (!empty($org)) {
                $org->legal_name = $request->get('legal_name');
                $org->physical_location = $request->get('physical_location');
                $org->year = $request->get('year');
                $org->company_logo = $request->file('company_logo');
                $org->save();
                return $this->onSuccess($org, 'Role Updated');

            }
            return $this->onError(404, 'Organization  Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
        $user = Auth::user();
        // $user = DB::table('users')->select('role')->where('id',1)->first();

        if ($this->isAdmin($user)) {
            $dOrg = Organization::find($id); //find id of the Organization
            if (!empty($dOrg)) {
                $dOrg->delete();
                return response(['message' => 'Deleted']);
            }
            return $this->onError(404, 'Organization not found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }
}
