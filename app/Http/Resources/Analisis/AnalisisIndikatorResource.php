<?php

namespace App\Http\Resources\Analisis;

use App\Http\Resources\Concerns\WithPaginationFormat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;

class AnalisisIndikatorResource extends JsonApiResource
{
    use WithPaginationFormat;

    public function toId(Request $request): string
    {
        return (string) $this->resource->id;
    }

    public function toType(Request $request): string
    {
        return 'analisis-indikator';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'id'          => (int) $this->resource->id,
            'nomor'       => (int) $this->resource->nomor,
            'id_master'   => (int) $this->resource->id_master,
            'indikator'   => $this->resource->indikator,
            'subjek'      => $this->resource->subjek,
            'subjek_tipe' => (int) $this->resource->subjek_tipe,
            'periode'     => $this->resource->periode,
            'tahun'       => $this->resource->tahun,
            'master'      => $this->resource->master,
            'id_periode'  => (int) $this->resource->id_periode,
        ];
    }
}
