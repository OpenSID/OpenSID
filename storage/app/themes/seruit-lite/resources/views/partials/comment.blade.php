@php
    $comments = [];
    if (is_array($komentar) && $single_artikel['boleh_komentar']) {
        $comments = array_filter($komentar, function($comment) {
            return $comment['is_archived'] != 1;
        });
        $comments = array_reverse($comments);
        $forms = [
            'owner' => 'Nama',
            'email' => 'Alamat Email',
            'no_hp' => 'No. HP',
        ];
    }
    $notif = session('notif');
    $CI =& get_instance();
    $csrf_name = $CI->security->get_csrf_token_name();
    $csrf_hash = $CI->security->get_csrf_hash();
@endphp
<div class="border-t dark:border-gray-700 pt-8">
    @if (count($comments) > 0)
        <h3 class="text-xl font-bold mb-6">Komentar</h3>
        <div class="space-y-6">
            @foreach ($comments as $comment)
                <div class="flex space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-gray-500"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $comment['pengguna']['nama'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ tgl_indo($comment['tgl_upload']) }}</p>
                        <p class="text-gray-700 dark:text-gray-300">{{ $comment['komentar'] }}</p>
                    </div>
                </div>
                @if (count($comment['children']) > 0)
                    @foreach ($comment['children'] as $children)
                        <div class="ml-10 flex space-x-4">
                             <div class="flex-shrink-0 w-12 h-12 bg-blue-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-shield text-blue-500"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $children['pengguna']['nama'] }} <span class="text-xs font-light text-blue-500">({{ $children['pengguna']['level'] }})</span></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ tgl_indo($children['tgl_upload']) }}</p>
                                <p class="text-gray-700 dark:text-gray-300">{{ $children['komentar'] }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            @endforeach
        </div>
    @endif
    @if ($single_artikel['boleh_komentar'] == 1)
        <div class="mt-10 border-t dark:border-gray-700 pt-8">
            <h3 class="text-xl font-bold mb-4">Beri Komentar</h3>
            <div class="alert alert-info text-sm mb-4">Komentar baru terbit setelah disetujui oleh admin.</div>
            @if ($notif['pesan'])
                @php $alert = ($notif['status'] == -1) ? 'danger' : 'success'; @endphp
                <div class="alert alert-{{ $alert }} text-sm mb-4">{{ $notif['pesan'] }}</div>
            @endif
            <form action="{{ site_url('/add_comment/' . $single_artikel['id']) }}" method="POST" class="space-y-4">
                <input type="hidden" name="{{ $csrf_name }}" value="{{ $csrf_hash }}">
                <div>
                    <label for="komentar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Komentar <span class="text-red-500">*</span></label>
                    <textarea class="mt-1 block w-full rounded-none border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" name="komentar" id="komentar" rows="4" required>{{ $notif['data']['komentar'] ?? '' }}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach ($forms as $name => $label)
                        <div>
                            <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }} @if ($name !== 'email')<span class="text-red-500">*</span>@endif</label>
                            <input type="text" class="mt-1 block w-full rounded-none border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="{{ $name }}" name="{{ $name }}" value="{{ $notif['data'][$name] ?? '' }}" {{ in_array($name, ['owner', 'no_hp']) ? 'required' : '' }}>
                        </div>
                    @endforeach
                </div>
                 <div class="flex flex-col md:flex-row gap-4 items-center">
                    <div class="flex-shrink-0">
                        <img id="captcha" src="{{ site_url('captcha') }}" alt="CAPTCHA Image" class="rounded-none">
                        <button type="button" class="text-sm text-blue-600 hover:underline mt-1" onclick="document.getElementById('captcha').src = '{{ ci_route('captcha') }}?' + Math.random();">[Ganti Gambar]</button>
                    </div>
                    <input type="text" name="captcha_code" class="block w-full rounded-none border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Masukkan kode captcha" required>
                </div>
                <button type="submit" class="btn text-white font-bold py-2 px-6 rounded-none shadow-lg transform transition-transform hover:scale-105 bg-gradient-to-r {{ $gradient_class }} dark:bg-gray-700 dark:hover:bg-gray-600">
                    Kirim Komentar
                </button>
            </form>
        </div>
    @endif
</div>