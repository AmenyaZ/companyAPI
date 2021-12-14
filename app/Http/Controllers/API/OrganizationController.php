<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationResource;
use App\Models\organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $org = organization::all();
        return response([ 'organizations' => OrganizationResource::collection($org), 'message' => 'Retrieved successfully'], 200);
    
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

        $validator = Validator::make($data, [
            'legal_name',
            'physical_location'
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $org = organization::create($data);

        return response([ 'org' => new OrganizationResource($org), 'message' => 'Created successfully'], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function show(organization $organization)
    {
        return response([ 'org' => new OrganizationResource($organization), 'message' => 'Retrieved successfully'], 200);

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
        $organization->update($request->all());

        return response([ 'org' => new OrganizationResource($organization), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function destroy(organization $organization)
    {
        //
        $organization->delete();

        return response(['message' => 'Deleted']);
    }
}
