<x-layout>
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8 w-full">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Profil Saya</h2>
            <div class="flex justify-center gap-6 w-full px-4">
                <div class="flex flex-col space-y-3 w-1/3">
                    <div>
                        <label class="block text-sm font-extrabold text-gray-700">Nama Lengkap</label>
                        <p class="mt-1 text-gray-900 text-sm">{{ Auth::user()->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-extrabold text-gray-700">NIM</label>
                        <p class="mt-1 text-gray-900 text-sm">{{ Auth::user()->nim }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-extrabold text-gray-700">Program Studi</label>
                        <p class="mt-1 text-gray-900 text-sm">{{ Auth::user()->programStudi->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-extrabold text-gray-700">Golongan</label>
                        <p class="mt-1 text-gray-900 text-sm">{{ Auth::user()->golongan->nama_golongan }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-extrabold text-gray-700">Semester tempuh</label>
                        <p class="mt-1 text-gray-900 text-sm">
                            {{ explode(' ', Auth::user()->semester->semester_name)[1] }}
                        </p>
                    </div>
                </div>
                <div class="flex flex-col space-y-3 w-1/3.5">
                    <div>
                        <label class="block text-sm font-extrabold text-gray-700">No. Hp (WhatsApp)</label>
                        <p class="mt-1 text-gray-900 text-sm">{{ Auth::user()->no_hp }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-extrabold text-gray-700">Email</label>
                        <p class="mt-1 text-gray-900 text-sm">{{ Auth::user()->email }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-extrabold text-gray-700">Alamat</label>
                        <p class="mt-1 text-gray-900 text-sm">{{ Auth::user()->alamat }}
                        </p>
                    </div>
                </div>
                <div class="flex flex-col space-y-24 items-end w-1/5">
                    <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/avatar-default.png') }}"
                        alt="Foto Profil" class="w-[130px] h-[170px] rounded object-cover">

                    <div class="">
                        <a href="/profile/edit"
                            class="inline-block px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-900 transition">
                            Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
