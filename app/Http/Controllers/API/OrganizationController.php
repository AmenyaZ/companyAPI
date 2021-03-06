<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Http\Library\ApiHelpers;
use App\Http\Requests\OrganizationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
            $organizations = OrganizationResource::collection($org);
            $formatted_orgs = [];
            foreach ($organizations as $organization) {
                $image_64 = $organization['company_logo'];
                $orgName = $organization['legal_name'];
                $newOrgName = str_replace(' ', '', $orgName);
              //  $extension = "";
                if ($image_64) {    
                    
                   // $extension = explode('/', mime_content_type($image_64))[1];   // .jpg .png .pdf
                   $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
                    $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
                    $image = str_replace($replace, '', $image_64);

                    $image = str_replace(' ', '+', $image);
                    $imageName = $newOrgName.'.'. $extension;
                   // $imageName = Str::random(10).'.'. $extension;
                    Storage::disk('public')->put($imageName, base64_decode($image));
                    $img_file = $imageName;
                    $organization->image = $img_file;
                    
                  
                }
                array_push($formatted_orgs, $organization);
            }
            if (!empty($org)) {
                return response(['organizations' => OrganizationResource::collection($formatted_orgs), 'message' => 'Organizations Retrieved successfully'], 200);
            }
            return response(404, 'No Organizations Found');
        }
        return response(401, 'Unauthorized Access');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrganizationRequest $request)
    {
        $user = Auth::user();

        // $validator = $request->validated();
        $validator = Validator::make($request->all(), $request->validated());
        // $user = DB::table('users')->select('role')->where('id',1)->first();
        if ($this->isAdmin($user)) {

            if ($validator->fails()) {
                return response(['error' => $validator->errors(), 'Validation Error']);
            }
            $org = new Organization();
            $org->legal_name = $request->get('legal_name');
            $org->physical_location = $request->get('physical_location');
            $org->year = $request->get('year');
            $org->company_logo  = $request->get('company_logo');
            // $org->company_logo = base64_encode(file_get_contents($request->file('company_logo')->path()));
            // $org->company_logo_type = $request->file('company_logo')->getMimeType();
            //$org->company_logo = Storage::url($image);
            $org->save();


            return response(["Organition" => $org, 'message' => 'Organization Created successfully'], 200);
        }
        return response(401, 'Unauthorized Access');
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

                return response(['Organization' => $myOrganization, 'Orgnaization Retrieved successfully']);
            }
            return response(404, 'Organization  Not Found');
        }
        return response(401, 'Unauthorized Access');
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
                return response($org, 'Role Updated');
            }
            return response(404, 'Organization  Not Found');
        }
        return response(401, 'Unauthorized Access');
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
            return response(404, 'Organization not found');
        }
        return response(401, 'Unauthorized Access');
    }
}
