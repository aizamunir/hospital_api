<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'schedule_id'=>$this->schedule_id,
            'patient_id'=>$this->patient_id,
            'doctor_id'=>$this->doctor_id,
            'description'=>$this->description,
            'date'=>$this->date,
            'start-time'=>$this->start_time,
            'end_time'=>$this->end_time,
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
