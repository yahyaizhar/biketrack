<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ClientRidersLocationsResourceCollection extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        if($this->getLatestLocation($this->id) && $this->getLatestLocation($this->id)->rider->online != 3)
        {
            // $current_date = strtotime(date('Y-m-d H:i:s'));
            // $location_date = strtotime($this->getLatestLocation($this->id)->created_at);
            // $difference = ($current_date - $location_date)/60;
            return [
                'id' => $this->getLatestLocation($this->id) ? $this->getLatestLocation($this->id)->rider_id : null,
                'name' => $this->getLatestLocation($this->id) ? $this->getLatestLocation($this->id)->rider->name : null,
                'vehicle_number' => $this->getLatestLocation($this->id) ? $this->getLatestLocation($this->id)->rider->vehicle_number : null,
                'phone' => $this->getLatestLocation($this->id) ? $this->getLatestLocation($this->id)->rider->phone : null,
                'latitude' => $this->getLatestLocation($this->id) ? $this->getLatestLocation($this->id)->latitude : null,
                'longitude' => $this->getLatestLocation($this->id) ? $this->getLatestLocation($this->id)->longitude : null,
                'online' => $this->getLatestLocation($this->id)->rider->online == 1 ? true : false,
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
