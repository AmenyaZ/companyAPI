<?php

namespace App\Http\Resources;

use App\Models\Organization;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = Auth::user();

        //return parent::toArray($request);
        return [
            "type" => "user",
            "id" => $this->id,
            "attributes" => [
                "name" => $this->name,
                "email" => $this->email,
            ],
            // "relationships" => [
            //     "roles" => RoleResource::collection($this->roles)
            // ]
        ];
    }
}
