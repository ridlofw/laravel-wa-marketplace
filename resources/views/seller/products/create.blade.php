<x-seller-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 font-serif">Tambah Produk</h1>
        <p class="text-purple-600">Tambahkan produk baru ke katalog Anda</p>
    </div>

    <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100 max-w-3xl">
        <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Image -->
            <div>
                <div x-data="imagePreview()">
                    <x-input-label for="images" :value="__('Foto Produk (Max 5)')" class="text-gray-700 font-medium" />
                    
                    <!-- Preview Container -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-4" x-show="images.length > 0" style="display: none;">
                        <template x-for="(image, index) in images" :key="index">
                            <div class="relative group overflow-hidden rounded-xl border border-gray-200 aspect-w-1 aspect-h-1">
                                <img :src="image" class="w-full h-32 object-cover transition-transform duration-300 group-hover:scale-110">
                                
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                    <button type="button" @click="removeImage(index)" class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow-lg flex items-center gap-2 font-medium text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-400 transition-colors bg-gray-50 cursor-pointer" @click="$refs.fileInput.click()">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500" @click.stop>
                                    <span class="px-2">Upload file</span>
                                    <input x-ref="fileInput" id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*" @change="handleFileChange">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB per file</p>
                        </div>
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('images')" />
                    @foreach($errors->get('images.*') as $messages)
                        <x-input-error class="mt-2" :messages="$messages" />
                    @endforeach



                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    function imagePreview() {
                        return {
                            images: [],
                            allFiles: [],
                            
                            handleFileChange(event) {
                                const files = event.target.files;
                                
                                if (files) {
                                    // Append new files to our array
                                    for (let i = 0; i < files.length; i++) {
                                        this.allFiles.push(files[i]);
                                        
                                        // Create preview
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            this.images.push(e.target.result);
                                        };
                                        reader.readAsDataURL(files[i]);
                                    }
                                    
                                    // Update the input files
                                    this.updateInputFiles();
                                }
                            },
                            
                            updateInputFiles() {
                                const dataTransfer = new DataTransfer();
                                this.allFiles.forEach(file => {
                                    dataTransfer.items.add(file);
                                });
                                this.$refs.fileInput.files = dataTransfer.files;
                            },
                            
                            removeImage(index) {
                                Swal.fire({
                                    title: 'Hapus foto ini?',
                                    text: "Foto yang baru dipilih akan dihapus dari daftar upload.",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#d33',
                                    cancelButtonColor: '#3085d6',
                                    confirmButtonText: 'Ya, Hapus!',
                                    cancelButtonText: 'Batal',
                                    reverseButtons: true,
                                    customClass: {
                                        popup: 'rounded-2xl',
                                        confirmButton: 'px-6 py-2.5 rounded-xl font-bold',
                                        cancelButton: 'px-6 py-2.5 rounded-xl font-bold'
                                    }
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Remove from arrays
                                        this.images.splice(index, 1);
                                        this.allFiles.splice(index, 1);
                                        
                                        // Update input
                                        this.updateInputFiles();
                                    }
                                });
                            }
                        }
                    }
                </script>
                </div>
            </div>

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Nama Produk')" class="text-gray-700 font-medium" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500" :value="old('name')" required autofocus placeholder="Contoh: Kripik Pisang Coklat" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <!-- Price -->
            <div>
                <x-input-label for="price" :value="__('Harga (Rp)')" class="text-gray-700 font-medium" />
                <div class="relative mt-1 rounded-md shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="text-gray-500 sm:text-sm">Rp</span>
                    </div>
                    <x-text-input id="price" name="price" type="number" class="block w-full rounded-lg border-gray-300 pl-10 focus:border-purple-500 focus:ring-purple-500" :value="old('price')" required placeholder="0" />
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('price')" />
            </div>

            <!-- Category -->
            <div>
                <x-input-label for="category" :value="__('Kategori')" class="text-gray-700 font-medium" />
                <x-text-input id="category" name="category" type="text" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500" :value="old('category')" required placeholder="Contoh: Makanan Ringan" />
                <x-input-error class="mt-2" :messages="$errors->get('category')" />
            </div>

            <!-- Description -->
            <div>
                <x-input-label for="description" :value="__('Deskripsi')" class="text-gray-700 font-medium" />
                <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-lg shadow-sm" rows="4" required placeholder="Jelaskan detail produk Anda...">{{ old('description') }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('description')" />
            </div>



            <!-- WhatsApp (Auto-filled) -->
            <div>
                <x-input-label for="whatsapp_number" :value="__('Nomor WhatsApp')" class="text-gray-700 font-medium" />
                <x-text-input id="whatsapp_number" name="whatsapp_number" type="text" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500" :value="old('whatsapp_number', auth()->user()->shop_whatsapp)" placeholder="628xxxxxxxxxx" />
                <p class="text-xs text-gray-500 mt-1 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Otomatis terisi dari pengaturan toko.
                </p>
                <x-input-error class="mt-2" :messages="$errors->get('whatsapp_number')" />
            </div>

            <div class="flex items-center gap-4 pt-6">
                <button type="submit" class="px-6 py-2 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                    {{ __('Simpan Produk') }}
                </button>
                <a href="{{ route('seller.products.index') }}" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                    {{ __('Batal') }}
                </a>
            </div>
        </form>
    </div>
</x-seller-layout>
