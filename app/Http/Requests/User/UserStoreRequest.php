<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'present', 'max:50', 'regex:/^[a-zA-Z0-9\s]+$/'],
            'email' => ['required', 'present', 'max:50', 'email'],
            'username' => ['required', 'present', 'max:24', 'alpha_num', 'unique:users,email'],
            'password' => ['required', 'present', 'alpha_num', 'max:32', 'min:8'],
            'role' => ['required', 'present', Rule::in(['prodi', 'wakil_direktur', 'tata_usaha'])],
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute tidak boleh kosong',
            'present' => ':attribute harus tersedia',
            'alpha_num' => ':attribute hanya boleh alphanumeric',
            'max' => 'panjang :attribute maksimal :size',
            'email' => ':attribute bukan format email yang benar',
            'unique' => ':attribute harus unique',
            'regex' => ':attribute hanya boleh alphanumeric dan spasi',
            'min' => 'panjang :attribute minimal :size',
            'in' => ':attribute harus salah satu dari :values'
        ];
    }
}
