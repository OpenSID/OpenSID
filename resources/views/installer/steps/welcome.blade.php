@extends('installer.install')

@section('step')
    <p class="pb-2 text-gray-800">
        Selamat datang di instalasi OpenSID.
    </p>
    <p class="pb-3 text-gray-800">
        Sebelum memulai, kami memerlukan beberapa informasi tentang database.
        Anda perlu mengetahui hal-hal berikut sebelum melanjutkan.
    </p>
    <div class="px-3 pb-3 text-gray-800">
        <ol class="list-decimal list-inside">
            <li>Database host</li>
            <li>Database port</li>
            <li>Database name</li>
            <li>Database username</li>
            <li>Database password</li>
        </ol>
    </div>
    <p class="pb-3 text-gray-800">
        Kemungkinan besar item ini diberikan kepada Anda oleh Host Web Anda.
        Jika Anda tidak memiliki informasi ini, Anda harus menghubungi mereka sebelum dapat melanjutkan.
    </p>
    <p class="pb-3 text-gray-800">
        Instalasi akan memasukkan informasi ini ke dalam file konfigurasi sehingga situs Anda dapat berkomunikasi dengan database Anda.
    </p>
    <p class="pb-4 text-gray-800">
        Butuh lebih banyak bantuan?
        <a class="text-blue-500 hover:underline" href="https://helpdesk.opendesa.id/" target="_blank">Kontak Bantuan</a>.
    </p>
    <div class="flex justify-end">
        <a href="{{ site_url('install/server') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
            Langkah berikutnya
            <svg class="fill-current w-5 h-5 ml-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
        </a>

    </div>
@endsection
