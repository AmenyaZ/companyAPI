<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            "type" => "organization",
            "id" => $this->id,
            "attributes"=>[
                "name"=>$this->legal_name,
                "location"=>$this->physical_location,
                "year"=>$this->year,
                "logo"=>$this->company_logo,
                'image'=> isset($this->image) ? $this->image : null,
            ]
        ];
    }
}
