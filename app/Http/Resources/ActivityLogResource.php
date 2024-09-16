<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'activity_log_id'=>$this->activity_log_id,
            'patient_id'=>$this->patient_id,
            'doctor_id'=>$this->doctor_id,
            'remarks'=>$this->remarks,
            'date'=>$this->date,
            'time'=>$this->time,
            'doctor'=> $this->doctor ? [
                'doctor_id'=>$this->doctor->doctor_id,
                'name'=>$this->doctor->name
            ] : null,
            'patient'=> $this->patient ? [
                'patient_id'=>$this->patient->patient_id,
                'name'=>$this->patient->name
            ] : null
        ];
    }
}
