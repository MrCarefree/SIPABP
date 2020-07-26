<?php

namespace App\Http\Requests\Pengajuan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PengajuanStoreRequest extends FormRequest
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
            'tahun_akademik' => ['required', 'present', 'max:9', 'min:9'],
            'semester' => ['required', 'present', Rule::in(['ganjil', 'genap'])],
            'prodi' => ['required', 'present', 'array'],
            'siswa' => ['required', 'present']
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute tidak boleh kosong',
            'present' => ':attribute harus tersedia',
            'min' => 'panjang :attribute minimal :size',
            'max' => 'panjang :attribute maksimal :size',
            'in' => ':attribute harus salah satu dari :values',
            'array' => ':attribute harus array'
        ];
    }
}
