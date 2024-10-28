<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'prescription_id'=>$this->prescription_id,
            'patient_id'=>$this->patient_id,
            'doctor_id'=>$this->doctor_id,
            'description'=>$this->description,
            'medicines'=>$this->medicines,
            'next_visit'=>$this->next_visit,
            'created_at'=>$this->created_at,
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
