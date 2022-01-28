<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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
            "type" => "role",
            "id" => $this->id,
            "attributes" => [
                "title" => $this->title,
                "slug" => $this->slug,
                "description" => $this->description,
            ],
            
        ];
    }
}
