<?php

namespace App\Http\Requests;

use App\Enums\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        return [
            'name'              =>  'sometimes|string|min:3|max:30|alpha:ascii',
            'username'          =>  'sometimes|string|min:5|max:30|alpha_dash:ascii|unique:users,username',
            'lastname'          =>  'nullable|string|min:5|max:30|alpha_dash:ascii'    ,
            'profileImageUrl'   =>  'nullable|url:http,https',
            'bio'               =>  'nullable|string|min:5|max:30|alpha_dash:ascii',
            'gender'            =>  ['required', Rule::enum(GenderEnum::class)],
        ];
    }
}
