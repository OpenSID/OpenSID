{{-- prettier-ignore-start --}}
@php
    $isMultiple   = !empty($value['raw_attr']['multiple']);
    $nameAttr     = $isMultiple ? "{$value['key']}[]" : $value['key'];
    $multipleAttr = $isMultiple ? 'multiple="multiple"' : '';

    $defaultVals = [];
    if ($isMultiple) {
        $defaultVals = is_array($value['default']) ? $value['default'] : (json_decode($value['default'] ?? '[]', true) ?? []);
        $defaultVals = is_array($defaultVals) ? array_map('strval', $defaultVals) : [];
    }
@endphp

@if ($isMultiple)
    <input type="hidden" name="{{ $value['key'] }}" value="[]">
@endif

<select id="{{ $value['key'] }}" name="{{ $nameAttr }}" {!! $multipleAttr !!} class="form-control input-sm select2 {!! $value['class'] ?? '' !!}" {{ $value['readonly'] ?? '' }} {{ $value['disabled'] ?? '' }} {!! $value['attributes'] ?? '' !!}>
    @foreach ($value['option'] ?? [] as $key => $val)
        @php
            $optId    = is_array($val) ? ($val['id'] ?? $key) : (is_object($val) ? ($val->id ?? $key) : $key);
            $optLabel = is_array($val) ? ($val['nama'] ?? json_encode($val)) : (is_object($val) ? ($val->nama ?? '') : $val);
            $isSelected = $isMultiple
                ? in_array((string) $optId, $defaultVals, true)
                : ((string) $optId === (string) ($value['default'] ?? '') || (string) $key === (string) ($value['default'] ?? ''));
        @endphp
        <option value="{{ $optId }}" @selected($isSelected)>
            {{ SebutanDesa($optLabel) }}
        </option>
    @endforeach
</select>
{{-- prettier-ignore-end --}}
