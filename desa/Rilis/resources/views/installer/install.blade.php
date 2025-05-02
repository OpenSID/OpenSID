<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pemasangan - {{ config_item('nama_aplikasi') }}</title>
    <link rel="shortcut icon" href="{{ base_url('favicon.ico') }}">
    <link href="{{ asset('installer/styles.css') }}" rel="stylesheet">
</head>

<body class="min-h-screen h-full w-full bg-cover bg-no-repeat bg-center flex" style="background-image: url('{{ base_url() }}');">
    <div class="p-12 h-full m-auto">
        <div class="mx-auto w-full max-w-5xl w-[64rem]">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-8 border-b border-gray-200 sm:px-6">
                    <div class="flex justify-center items-center">
                        <img alt="App logo" class="h-12" src="{{ base_url('favicon.ico') }}">
                        <h2 class="pl-6 font-medium text-2xl text-gray-800">Pemasangan {{ config_item('nama_aplikasi') }}</h2>
                    </div>
                </div>
                <div class="px-4 py-5 sm:px-6 w-full">
                    @yield('step')
                </div>
            </div>
        </div>
    </div>
    @stack('scripts')
</body>

</html>
