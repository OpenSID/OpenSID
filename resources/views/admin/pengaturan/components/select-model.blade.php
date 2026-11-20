{{-- prettier-ignore-start --}}
@php
    $isMultiple   = !empty($value['raw_attr']['multiple']);
    $nameAttr     = $isMultiple ? "{$value['key']}[]" : $value['key'];
    $multipleAttr = $isMultiple ? 'multiple="multiple"' : '';
    $modelData    = $value['option'] ?? [];
    $whereClause  = $value['raw_attr']['where'] ?? [];
    $groupConfig  = $modelData['group'] ?? null;
    $referensiData = [];

    if (!empty($modelData['model']) && class_exists($modelData['model'])) {
        $query = (new $modelData['model']())
            ->select([$modelData['value'], $modelData['label']]);

        if (!empty($modelData['distinct'])) {
            $query->distinct();
        }

        foreach ($whereClause as $column => $val) {
            $query->where($column, $val);
        }

        if ($groupConfig && !empty($groupConfig['column'])) {
            $query->addSelect($groupConfig['column'])->orderBy($groupConfig['column']);
        }

        if (!empty($modelData['orderBy'])) {
            $orderDir = $modelData['orderDir'] ?? $modelData['orderDirection'] ?? 'asc';
            $query->orderBy($modelData['orderBy'], $orderDir);
        }

        try {
            $referensiData = $query->get()->toArray();
        } catch (\Throwable) {
            $referensiData = [];
        }
    }

    $selectedValue = is_array($value['default']) ? $value['default'] : json_decode($value['default'] ?? '[]', true);
    $selectedList  = is_array($selectedValue) ? array_map('strval', $selectedValue) : (is_null($value['default']) ? [] : [(string) $value['default']]);
@endphp

@if ($isMultiple)
    <input type="hidden" name="{{ $value['key'] }}" value="[]">
@endif

<select class="form-control input-sm select2 {!! $value['class'] ?? '' !!}" id="{{ $value['key'] }}" name="{{ $nameAttr }}" {!! $multipleAttr !!} {{ $value['readonly'] ?? '' }} {{ $value['disabled'] ?? '' }} {!! $value['attributes'] ?? '' !!}>
    @if ($isMultiple)
        <option value="-" @selected(empty($selectedList))>Tanpa Referensi (kosong)</option>
    @else
        <option value="" @selected(empty($selectedList) || $value['default'] === '')>-- Pilih {{ SebutanDesa($pengaturan->judul ?? 'Pilihan') }} --</option>
    @endif
    @if ($groupConfig && !empty($groupConfig['labels']))
        @foreach ($groupConfig['labels'] as $groupValue => $groupLabel)
            @php
                $groupItems = array_filter($referensiData, fn ($v) => (string) ($v[$groupConfig['column']] ?? '') === (string) $groupValue);
            @endphp
            @if (count($groupItems))
                <optgroup label="{{ SebutanDesa($groupLabel) }}">
                    @foreach ($groupItems as $val)
                        <option value="{{ $val[$modelData['value']] }}" @selected(in_array((string) $val[$modelData['value']], $selectedList, true))>{{ $val[$modelData['label']] }}</option>
                    @endforeach
                </optgroup>
            @endif
        @endforeach
    @else
        @foreach ($referensiData as $val)
            <option value="{{ $val[$modelData['value']] }}" @selected(in_array((string) $val[$modelData['value']], $selectedList, true))>{{ $val[$modelData['label']] }}</option>
        @endforeach
    @endif
</select>
{{-- prettier-ignore-end --}}
