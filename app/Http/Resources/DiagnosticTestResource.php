<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiagnosticTestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'diagnostictest_id'=>$this->diagnostictest_id,
            'patient_id'=>$this->patient_id,
            'doctor_id'=>$this->doctor_id,
            'description'=>$this->description,
            'tests'=>$this->tests,
            'result'=>$this->result,
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
