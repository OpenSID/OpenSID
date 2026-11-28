<?php

namespace App\Http\Requests\Analisis;

use Illuminate\Foundation\Http\FormRequest;

class AnalisisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'filter'              => 'nullable|array',
            'filter.id'           => 'nullable|integer',
            'filter.id_master'    => 'nullable|integer',
            'filter.tahun'        => 'nullable|string',
            'filter.id_periode'   => 'nullable|integer',
            'filter.id_indikator' => 'nullable|integer',
            'filter.search'       => 'nullable|string',
            'filter.subjek_tipe'  => 'nullable|integer|in:1,2,3,4',
            'sort'                => 'nullable|string',
            'page'                => 'nullable|array',
            'page.size'           => 'nullable|integer|min:1|max:100',
            'page.number'         => 'nullable|integer|min:1',
            'per_page'            => 'nullable|integer|min:1|max:100',
            'fields'              => 'nullable|array',
        ];
    }
}
