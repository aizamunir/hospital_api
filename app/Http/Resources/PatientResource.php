<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'patient_id'=>$this->patient_id,
            'name'=>$this->name,
            'age'=>$this->age,
            'disease'=>$this->disease,
            'phn_num'=>$this->phn_num,
            'gender'=>$this->gender,
            'doctor'=> [
                'doctor_id'=>$this->doctor->doctor_id,
                'name'=>$this->doctor->name
            ],
            
        ];
    }
}
