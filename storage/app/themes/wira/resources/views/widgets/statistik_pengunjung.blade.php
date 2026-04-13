<?php

    if ($yesterday > 0) {
        $percentageChange = (($statistik_pengunjung['hari_ini'] - $statistik_pengunjung['kemarin']) / $statistik_pengunjung['kemarin']) * 100;
    } else {
        $percentageChange = 0; // avoid division by zero
    }
?>

<div class="box box-primary box-solid items-center">
    <div class="bg-green-600 flex items-center justify-center py-3 px-6 mb-1">
        <h3 class="text-xl font-semibold text-white text-center">
            STATISTIK PENGUNJUNG
        </h3>
    </div>
    <div class="h-1 bg-green-500 mb-2"></div>
    
    <div class="space-y-4">
        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-primary-50 to-primary-100 rounded-xl">
            <div>
                <p class="text-sm font-medium text-gray-900">Hari Ini</p>
                <p class="text-xs text-gray-600">Pengunjung aktif</p>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold text-primary-700">{{ number_format($statistik_pengunjung['hari_ini']) }}</p>
                <p class="text-xs text-primary-600">{{ $percentageChange . '%'}}</p>
            </div>
        </div>
        
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
            <div>
                <p class="text-sm font-medium text-gray-900">Kemarin</p>
                <p class="text-xs text-gray-600">Total pengunjung</p>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold text-gray-700">{{ number_format($statistik_pengunjung['kemarin']) ?? 0 }}</p>
            </div>
        </div>
        
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
            <div>
                <p class="text-sm font-medium text-gray-900">Total</p>
                <p class="text-xs text-gray-600">Semua pengunjung</p>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold text-gray-700">{{ number_format($statistik_pengunjung['total']) }}</p>
            </div>
        </div>
    </div>
</div>