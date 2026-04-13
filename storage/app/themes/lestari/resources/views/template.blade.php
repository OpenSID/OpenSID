<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    @include('theme::commons.meta')
    <script type="text/javascript">
        var temaPremium = @json($tema_premium);
        var siteUrl = "{{ site_url() }}";
    </script>
</head>
<body>
	<div class="mainarea">
	@yield('layout')
    @include('theme::commons.meta_footer')
	</div>
    @stack('scripts')
</body>
</html>
