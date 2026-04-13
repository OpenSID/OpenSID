<?php
// Calculate totals and percentages
$total_population = 0;
$gender_data = [];

// Process the data to get totals
foreach ($stat_widget as $data) {
    if ($data['jumlah'] > 0 && $data['nama'] != 'JUMLAH') {
        $total_population += $data['jumlah'];
        $gender_data[] = $data;
    }
}

// Calculate percentages for each gender
foreach ($gender_data as &$item) {
    $item['percentage'] = $data['jumlah'] > 0 ? round(($item['jumlah'] / $data['jumlah']) * 100, 1) : 0;
}
?>

<div class="box box-primary box-solid items-center">
    <div class="bg-green-600 flex items-center justify-center py-3 px-6 mb-1">
        <h3 class="text-md font-semibold text-white text-center">
            {{ strtoupper($judul_widget) }}
        </h3>
    </div>
    <div class="h-1 bg-green-500 mb-2"></div>
    <!-- Single Column Layout -->
    <div class="space-y-6">
        <!-- Pie Chart -->
        <div class="flex items-center justify-center">
            <div class="relative w-48 h-48">
                <svg class="w-48 h-48 transform -rotate-90" viewBox="0 0 100 100">
                    <!-- Background circle -->
                    <circle cx="50" cy="50" r="40" fill="none" stroke="#e5e7eb" stroke-width="8"/>
                    
                    @php
                        $circumference = 2 * pi() * 40; // 2πr where r=40
                        $current_offset = 0;
                        $stroke_colors = ['#15803d', '#22c55e', '#10b981', '#059669', '#047857'];
                    @endphp
                    
                    @foreach ($gender_data as $index => $data)
                        @php
                            $stroke_length = ($data['percentage'] / 100) * $circumference;
                            $stroke_offset = $circumference - $current_offset;
                        @endphp
                        
                        <circle cx="50" cy="50" r="40" fill="none" 
                                stroke="{{ $stroke_colors[$index % count($stroke_colors)] }}" 
                                stroke-width="8" 
                                stroke-dasharray="{{ $stroke_length }}" 
                                stroke-dashoffset="{{ $stroke_offset }}" 
                                stroke-linecap="round"/>
                        
                        @php $current_offset += $stroke_length; @endphp
                    @endforeach
                </svg>
                
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-primary-700">
                            @if(end($gender_data)['jumlah'] >= 1000)
                                {{ number_format(end($gender_data)['jumlah'] / 1000, 1) }}K
                            @else
                                {{ number_format(end($gender_data)['jumlah']) }}
                            @endif
                        </div>
                        <div class="text-sm text-gray-600">Total</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics List -->
        <div class="space-y-4">

            @foreach ($gender_data as $data)
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-900">{{ $data['nama'] }}</span>
                        <span class="text-sm font-bold text-primary-700">{{ number_format($data['jumlah']) }} ({{ $data['percentage'] }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-600 h-3 rounded-full transition-all duration-500" 
                             style="width: {{ $data['percentage'] }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>