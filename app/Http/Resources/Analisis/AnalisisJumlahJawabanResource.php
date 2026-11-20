<?php

namespace App\Http\Resources\Analisis;

use App\Http\Resources\Concerns\WithPaginationFormat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;

class AnalisisJumlahJawabanResource extends JsonApiResource
{
    use WithPaginationFormat;

    public function toId(Request $request): string
    {
        return (string) $this->resource->id;
    }

    public function toType(Request $request): string
    {
        return 'analisis-jawaban';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'id'           => (int) $this->resource->id,
            'id_indikator' => (int) $this->resource->id_indikator,
            'jawaban'      => $this->resource->jawaban,
            'kode_jawaban' => $this->resource->kode_jawaban,
            'jml'          => isset($this->resource->jml) ? (int) $this->resource->jml : null,
        ];
    }
}
