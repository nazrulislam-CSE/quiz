<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable'],
            'username' => ['nullable'],
            'email' => ['nullable'],
            'phone' => ['nullable'],
            'image' => ['nullable'],
            'fathers_name' => ['nullable'],
            'mothers_name' => ['nullable'],
            'present_address' => ['nullable'],
            'parmanent_address' => ['nullable'],
            'date_of_birth' => ['nullable'],
            'nationality' => ['nullable'],
            'religion' => ['nullable'],
            'nid' => ['nullable'],
            'birth_certificate' => ['nullable'],
            'blood_group' => ['nullable'],
            'gender' => ['nullable'],
            'edu_qualification' => ['nullable'],
            'experience' => ['nullable'],
            'staff_id' => ['nullable'],
            'staff_type' => ['nullable'],
            'department_id' => ['nullable'],
            'designation_id' => ['nullable'],
            'office_zone' => ['nullable'],
            'joining_date' => ['nullable'],
            'discharge_date' => ['nullable'],
            'machine_id' => ['nullable'],
            'description' => ['nullable'],
            'marital_status' => ['nullable'],
        ];
    }
}
