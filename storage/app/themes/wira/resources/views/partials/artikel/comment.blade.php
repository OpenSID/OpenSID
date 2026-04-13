@php
    $comments = [];
    if (is_array($komentar) && $single_artikel['boleh_komentar']) {
        $comments = [];

        foreach ($komentar as $comment) {
            if ($comment['is_archived'] != 1) {
                $comments[] = $comment;
            }
        }
        $comments = array_reverse($comments);
        $forms = [
            'owner' => 'Nama',
            'email' => 'Alamat Email',
            'no_hp' => 'No. HP',
        ];
    }
    $notif = session('notif');
@endphp

{{-- Comments Section --}}
@if (count($comments) > 0)
    <div class="mt-12 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-900">
                Komentar <span class="text-sm font-normal text-gray-500">({{ count($comments) }})</span>
            </h3>
        </div>

        <div class="space-y-6">
            @foreach ($comments as $comment)
                <div class="group">
                    {{-- Main Comment --}}
                    <div class="flex space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-medium text-sm">
                                {{ strtoupper(substr($comment['pengguna']['nama'], 0, 1)) }}
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="bg-gray-50 rounded-lg px-4 py-3 transition-colors group-hover:bg-gray-100">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="font-medium text-gray-900">{{ $comment['pengguna']['nama'] }}</span>
                                    <span class="text-xs text-gray-500">{{ tgl_indo($comment['tgl_upload']) }}</span>
                                </div>
                                <p class="text-gray-700 text-sm leading-relaxed">{{ $comment['komentar'] }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Replies --}}
                    @if (count($comment['children']) > 0)
                        <div class="ml-14 mt-4 space-y-4">
                            @foreach ($comment['children'] as $children)
                                <div class="flex space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-medium text-xs">
                                            {{ strtoupper(substr($children['pengguna']['nama'], 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="bg-blue-50 rounded-lg px-4 py-3 border-l-2 border-blue-200">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <span class="font-medium text-gray-900">{{ $children['pengguna']['nama'] }}</span>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $children['pengguna']['level'] }}
                                                </span>
                                                <span class="text-xs text-gray-500">{{ tgl_indo($children['tgl_upload']) }}</span>
                                            </div>
                                            <p class="text-gray-700 text-sm leading-relaxed">{{ $children['komentar'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif

{{-- Comment Form --}}
@if ($single_artikel['boleh_komentar'] == 1)
    <div class="mt-12 mb-8">
        <div class="bg-white border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Tinggalkan Komentar</h3>
                <p class="text-sm text-gray-600 mt-1">Komentar akan ditampilkan setelah disetujui oleh admin</p>
            </div>

            <form action="{{ site_url('/add_comment/' . $single_artikel['id']) }}" method="POST" class="p-6 space-y-6">
                <input type="hidden" name="{{ get_instance()->security->get_csrf_token_name() }}" value="{{ get_instance()->security->get_csrf_hash() }}">

                {{-- Alert Messages --}}
                @php $alert = ($notif['status'] == -1) ? 'error' : 'success'; @endphp
                @if ($flash_message = $notif['pesan'])
                    <div class="rounded-lg p-4 {{ $alert === 'success' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                        <div class="flex items-center">
                            <i class="{{ $notif['status'] != -1 ? 'fas fa-check-circle text-green-500' : 'fas fa-exclamation-circle text-red-500' }} mr-3"></i>
                            <span class="text-sm {{ $alert === 'success' ? 'text-green-800' : 'text-red-800' }}">{{ $flash_message }}</span>
                        </div>
                    </div>
                @endif

                {{-- Comment Textarea --}}
                <div class="space-y-2">
                    <label for="komentar" class="block text-sm font-medium text-gray-700">
                        Komentar <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none transition-colors"
                        name="komentar" 
                        id="komentar" 
                        rows="4" 
                        placeholder="Tulis komentar Anda di sini..."
                        required>{{ $notif['data']['komentar'] ?? '' }}</textarea>
                </div>

                {{-- Personal Information --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach ($forms as $name => $label)
                        <div class="space-y-2">
                            <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
                                {{ $label }}
                                @if ($name !== 'email')
                                    <span class="text-red-500">*</span>
                                @endif
                            </label>
                            <input 
                                type="{{ $name === 'email' ? 'email' : 'text' }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                id="{{ $name }}" 
                                name="{{ $name }}" 
                                value="{{ $name === 'owner' && !empty($notif['data']['nama']) ? $notif['data']['nama'] : ($notif['data'][$name] ?? '') }}"
                                placeholder="{{ $label }}"
                                {{ $name !== 'email' ? 'required' : '' }}>
                        </div>
                    @endforeach
                </div>

                {{-- CAPTCHA --}}
                <div class="flex flex-col sm:flex-row gap-4 items-start">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Verifikasi</label>
                        <div class="relative">
                            <img id="captcha" src="{{ site_url('captcha') }}" alt="CAPTCHA" class="border border-gray-300 rounded-lg">
                            <button 
                                type="button" 
                                class="absolute -bottom-6 left-0 text-xs text-green-600 hover:text-green-800 transition-colors"
                                onclick="document.getElementById('captcha').src = '{{ ci_route('captcha') }}?' + Math.random();">
                                <i class="fas fa-refresh mr-1"></i>Ganti Gambar
                            </button>
                        </div>
                    </div>
                    <div class="flex-1 space-y-2">
                        <label for="captcha_code" class="block text-sm font-medium text-gray-700">Kode Verifikasi <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            name="captcha_code" 
                            id="captcha_code"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                            placeholder="Masukkan kode di atas"
                            required>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="flex justify-end pt-4">
                    <button 
                        type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Kirim Komentar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif