@extends('installer.install')

@section('step')
    <p class="pb-3 text-gray-800">
        Di bawah ini Anda harus memasukkan rincian koneksi database Anda. Jika Anda tidak yakin tentang ini, hubungi penyedia hosting Anda.
    </p>
    @if ($ci->session->errors)
        @if (is_array($ci->session->errors))
            @foreach ($ci->session->errors as $error)
                <div class="bg-red-100 border-l-4 border-red-500 p-4 mb-3">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm leading-5 text-red-700">
                                {!! $error !!}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-red-100 border-l-4 border-red-500 p-4 mb-3">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm leading-5 text-red-700">
                            {!! $ci->session->errors !!}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    @endif
    <form method="post" action="{{ site_url('install/database') }}">
        <input type="hidden" name="<?= $ci->security->get_csrf_token_name() ?>" value="<?= $ci->security->get_csrf_hash() ?>" />
        <div class="mb-3">
            <label for="database_hostname" class="block font-medium leading-5 text-gray-700 pb-2">
                Database host
                <span class="text-red-400">*</span>
            </label>
            <input
                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="database_hostname"
                type="text"
                name="database_hostname"
                placeholder="Masukkan host database Anda"
                value="{{ set_value('database_hostname', 'localhost') }}"
                required
            >
            @if (form_error('database_hostname'))
                {!! form_error('database_hostname') !!}
            @endif
        </div>
        <div class="mb-3">
            <label for="database_port" class="block font-medium leading-5 text-gray-700 pb-2">
                Database port
                <span class="text-red-400">*</span>
            </label>
            <input
                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="database_port"
                type="text"
                name="database_port"
                placeholder="Masukkan port database Anda"
                value="{{ set_value('database_port', '3306') }}"
                required
            >
            @if (form_error('database_port'))
                {!! form_error('database_port') !!}
            @endif
        </div>
        <div class="mb-3">
            <label for="database_name" class="block font-medium leading-5 text-gray-700 pb-2">
                Database name
                <span class="text-red-400">*</span>
            </label>
            <input
                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="database_name"
                type="text"
                name="database_name"
                placeholder="Masukkan nama database Anda"
                value="{{ set_value('database_name') }}"
                required
            >
            @if (form_error('database_name'))
                {!! form_error('database_name') !!}
            @endif
        </div>
        <div class="mb-3">
            <label for="database_username" class="block font-medium leading-5 text-gray-700 pb-2">
                Database user
                <span class="text-red-400">*</span>
            </label>
            <input
                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="database_username"
                type="text"
                name="database_username"
                placeholder="Masukkan user database Anda"
                value="{{ set_value('database_username', 'root') }}"
                required
            >
            @if (form_error('database_username'))
                {!! form_error('database_username') !!}
            @endif
        </div>
        <div class="mb-3">
            <label for="database_password" class="block font-medium leading-5 text-gray-700 pb-2">
                Database password
            </label>
            <input class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="database_password" type="password" name="database_password" placeholder="Masukkan password database Anda" value="{{ set_value('database_password') }}">
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center" onClick="this.form.submit(); this.disabled=true; this.innerText='Mohon tunggu sebentar…';">
                Langkah berikutnya
                <svg class="fill-current w-5 h-5 ml-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </button>

        </div>
    </form>
@endsection
