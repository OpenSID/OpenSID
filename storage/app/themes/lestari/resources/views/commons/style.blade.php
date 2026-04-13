@php $color1 = theme_config('color1', '#005dfa'); @endphp
@php $color2 = theme_config('color2', '#ffb200'); @endphp
@php $color3 = theme_config('color3', '#003793'); @endphp
<style type="text/css">
:root{
	--color1: {{ $color1 }};
	--color2: {{ $color2 }};
	--color3: {{ $color3 }};
}
</style>	