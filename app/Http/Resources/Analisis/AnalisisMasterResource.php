<?php

namespace App\Http\Resources\Analisis;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;

class AnalisisMasterResource extends JsonApiResource
{
    public function toId(Request $request): string
    {
        return (string) $this->resource->id;
    }

    public function toType(Request $request): string
    {
        return 'analisis-master';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'id'          => (int) $this->resource->id,
            'master'      => $this->resource->master,
            'subjek_tipe' => (int) $this->resource->subjek_tipe,
            'subjek'      => $this->resource->subjek,
            'periode'     => $this->resource->periode,
            'tahun'       => $this->resource->tahun,
            'id_periode'  => (int) $this->resource->id_periode,
        ];
    }
}
