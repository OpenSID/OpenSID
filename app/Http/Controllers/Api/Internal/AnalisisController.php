<?php

namespace App\Http\Controllers\Api\Internal;

use App\Http\Requests\Analisis\AnalisisRequest;
use App\Http\Resources\Analisis\AnalisisIndikatorResource;
use App\Http\Resources\Analisis\AnalisisJumlahJawabanResource;
use App\Http\Resources\Analisis\AnalisisMasterResource;
use App\Repositories\AnalisisRepository;

class AnalisisController
{
    public function __construct(
        protected AnalisisRepository $analisis
    ) {}

    public function master(AnalisisRequest $request)
    {
        return AnalisisMasterResource::collection($this->analisis->analisisMaster());
    }

    public function indikator(AnalisisRequest $request)
    {
        return AnalisisIndikatorResource::collection($this->analisis->analisisIndikator());
    }

    public function jawaban(AnalisisRequest $request)
    {
        return AnalisisJumlahJawabanResource::collection($this->analisis->jumlahAnalisisJawaban());
    }
}
