<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use DateTime;

class RiderLocationResourceCollection extends Resource
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
                // 'online' => $difference < 5 ? true : false,
                'online' => $this->getLatestLocation($this->id)->rider->online == 1 ? true : false,
                'status' => true
            ];
        }
        else
        {
            // return [
            //     'status'=> false
            // ];
            return [
                'id' => $this->id,
                'name' => $this->name,
                'vehicle_number' => $this->vehicle_number,
                'phone' => $this->phone,
                'latitude' => $this->id % 2 == 0 ? '25.06'.$this->id.'3' : '25.09'.$this->id.'6', 
                'longitude' => $this->id % 2 == 0 ? '55.14'.$this->id.'7' : '55.16'.$this->id.'1',
                'online' => $this->online == 1 ? true : false,
                'status'=> false
            ];
        }
    }
}
