<x-layout>
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex">
                <div class="flex-1 space-y-4">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Profil Saya</h2>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <p class="mt-1 text-gray-900">{{ Auth::user()->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">NIM</label>
                        <p class="mt-1 text-gray-900">{{ Auth::user()->nim }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Program Studi</label>
                        <p class="mt-1 text-gray-900">{{ Auth::user()->program_studi }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Golongan/Kelas</label>
                        <p class="mt-1 text-gray-900">{{ Auth::user()->kelas }}</p>
                    </div>
                </div>

                <div class="flex-1 mr-35 space-y-4 mt-12">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">No. Hp (WhatsApp)</label>
                        <p class="mt-1 text-gray-900">{{ Auth::user()->no_hp }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p class="mt-1 text-gray-900">{{ Auth::user()->email }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <p class="mt-1 text-gray-900">{{ Auth::user()->alamat }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-col items-center">
                    <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/user.png') }}"
                        alt="Foto Profil" class="w-30 h-30 mr-5 mt-6 rounded-full object-cover">

                    <!-- Tombol Edit Profil -->
                    <div class="mt-20 mr-5">
                        <a href="/profile/edit"
                            class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500">
                            Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
