<?php

namespace App\Http\Requests\Prodi;

use Illuminate\Foundation\Http\FormRequest;

class ProdiUpdateRequest extends FormRequest
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
            'id' => ['required', 'present', 'integer'],
            'kode_prodi' => ['required', 'present', 'max:5'],
            'nama_prodi' => ['required', 'present', 'max:50'],
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute tidak boleh kosong',
            'present' => ':attribute harus tersedia',
            'integer' => ':attribute harus bernilai angka',
            'max' => 'panjang :attribute maksimal :size',
        ];
    }
}
