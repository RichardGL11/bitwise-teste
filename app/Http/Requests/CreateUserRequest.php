<?php

namespace App\Http\Requests;

use App\Enums\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'              =>  'bail|required|string|min:3|max:30|alpha:ascii',
            'username'          =>  'required|string|min:5|max:30|alpha_dash:ascii|unique:users,username',
            'lastname'          =>  'nullable|string|min:5|max:30|alpha_dash:ascii'    ,
            'profileImageUrl'   =>  'nullable|url:http,https',
            'bio'               =>  'nullable|string|min:5|max:30|alpha_dash:ascii',
            'gender'            =>  ['required', Rule::enum(GenderEnum::class)],
            'email'             =>  'required|string|email',
            'password'          =>  'required|confirmed',
        ];
    }
}
