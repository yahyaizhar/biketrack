<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RiderLocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        // $current_date = strtotime(date('Y-m-d H:i:s'));
        // $location_date = strtotime($this->created_at);
        // $difference = ($current_date - $location_date)/60;
        return [
            'data' => [
                'id' => $this->rider_id,
                'name' => $this->rider->name,
                'vehicle_number' => $this->rider->vehicle_number,
                'phone' => $this->rider->phone,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'online' => $this->rider->online == 1 ? true : false,
            ],
            'status' => true
        ];
        
    }
}
