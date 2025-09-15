<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUsersRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $method = $this->method();
        if($method == 'PUT'){
        return [
            'name'=>['required'],
            'role'=>['required', Rule::in(['manager','dispatcher','client'])],
            'password'=>['required']
            ];
        }else{
            return [
            'name'=>['sometimes','required'],
            'role'=>['sometimes','required', Rule::in(['manager','dispatcher','client'])],
            'password'=>['sometimes','required']
            ];
        }



    }
}