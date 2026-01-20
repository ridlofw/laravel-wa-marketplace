<x-seller-layout>
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 font-serif">Pengaturan Toko</h1>
            <p class="text-purple-600 mt-1">Kelola informasi dan profil toko Anda</p>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-8 shadow-sm border border-gray-100">
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative mb-8 flex items-center" role="alert">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('seller.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Shop Name -->
                <div>
                    <x-input-label for="shop_name" :value="__('Nama Toko')" class="text-gray-700 font-bold mb-2 text-base" />
                    <x-text-input id="shop_name" name="shop_name" type="text" class="mt-1 block w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500 py-3 px-4 bg-gray-50 focus:bg-white transition-colors" :value="old('shop_name', $user->shop_name)" required autofocus placeholder="Masukkan nama toko Anda" />
                    <x-input-error class="mt-2" :messages="$errors->get('shop_name')" />
                </div>

                <!-- Shop Address -->
                <div>
                    <x-input-label for="shop_address" :value="__('Alamat Toko')" class="text-gray-700 font-bold mb-2 text-base" />
                    <textarea id="shop_address" name="shop_address" class="mt-1 block w-full border-gray-200 focus:border-purple-500 focus:ring-purple-500 rounded-xl shadow-sm py-3 px-4 bg-gray-50 focus:bg-white transition-colors" rows="4" placeholder="Contoh: Jl. Raya No. 123, Dusun Krajan, Klepu">{{ old('shop_address', $user->shop_address) }}</textarea>
                    <p class="mt-2 text-sm text-gray-500">💡 Tip: Sertakan nama dusun di alamat (contoh: "Dusun Krajan") agar muncul di profil toko</p>
                    <x-input-error class="mt-2" :messages="$errors->get('shop_address')" />
                </div>

                <!-- WhatsApp -->
                <div>
                    <x-input-label for="shop_whatsapp" :value="__('Nomor WhatsApp')" class="text-gray-700 font-bold mb-2 text-base" />
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-medium">+</span>
                        </div>
                        <x-text-input id="shop_whatsapp" name="shop_whatsapp" type="text" class="block w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500 py-3 pl-8 pr-4 bg-gray-50 focus:bg-white transition-colors" :value="old('shop_whatsapp', $user->shop_whatsapp)" required placeholder="628123456789" />
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Gunakan format internasional (contoh: 628123456789)</p>
                    <x-input-error class="mt-2" :messages="$errors->get('shop_whatsapp')" />
                </div>

                <!-- Logo -->
                <div class="pt-4 border-t border-gray-100">
                    <x-input-label for="shop_logo" :value="__('Logo Toko')" class="text-gray-700 font-bold mb-4 text-base" />
                    
                    <div class="flex flex-col sm:flex-row items-start gap-6">
                        <div class="flex-shrink-0 {{ $user->shop_logo ? '' : 'hidden' }}" id="logo-preview-container">
                            <img src="{{ $user->shop_logo ? asset('storage/' . $user->shop_logo) : '#' }}" alt="Shop Logo" id="shop-logo-preview" class="h-32 w-32 object-cover rounded-2xl border-4 border-purple-50 shadow-sm">
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex justify-center items-center w-full">
                                <label for="shop_logo" class="flex flex-col justify-center items-center w-full h-32 bg-purple-50 rounded-2xl border-2 border-purple-200 border-dashed cursor-pointer hover:bg-purple-100 transition-colors group">
                                    <div class="flex flex-col justify-center items-center pt-5 pb-6">
                                        <svg class="mb-3 w-8 h-8 text-purple-400 group-hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        <p class="mb-2 text-sm text-purple-500 group-hover:text-purple-700"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                                        <p class="text-xs text-purple-400 group-hover:text-purple-600">SVG, PNG, JPG or GIF (MAX. 2MB)</p>
                                    </div>
                                    <input id="shop_logo" name="shop_logo" type="file" class="hidden" />
                                </label>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('shop_logo')" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end pt-6 border-t border-gray-100">
                    <button type="submit" class="px-8 py-3 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-700 focus:outline-none focus:ring-4 focus:ring-purple-200 transition-all shadow-lg hover:shadow-purple-200 transform hover:-translate-y-0.5">
                        {{ __('Simpan Perubahan') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('shop_logo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('shop-logo-preview');
                    const container = document.getElementById('logo-preview-container');
                    preview.src = e.target.result;
                    container.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-seller-layout>
