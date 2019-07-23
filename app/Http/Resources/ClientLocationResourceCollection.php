<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ClientLocationResourceCollection extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->latitude && $this->longitude)
        {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'address' => $this->address,
                'phone' => $this->phone,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'status' => true
            ];
        }
        else
        {
            return [
                'status'=> false
            ];
        }
    }
}
