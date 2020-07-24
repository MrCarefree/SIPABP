<?php

namespace App\Http\Requests\Prodi;

use Illuminate\Foundation\Http\FormRequest;

class ProdiStoreRequest extends FormRequest
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
            'kode_prodi' => ['required', 'present', 'max:5'],
            'nama_prodi' => ['required', 'present', 'max:50'],
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute tidak boleh kosong',
            'present' => ':attribute harus tersedia',
            'max' => 'panjang :attribute maksimal :size',
        ];
    }
}
