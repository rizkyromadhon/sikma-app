<x-layout>
    <div class="max-w-4xl mx-auto py-12 px-4 md:px-8 w-full">
        <div class="bg-white dark:bg-gray-900/40 dark-mode-transition shadow-md rounded-lg p-4 md:p-6">
            <h2
                class="text-2xl text-center md:text-left pt-6 md:pt-0 font-bold text-gray-800 dark:text-gray-200 dark-mode-transition mb-2 md:mb-6">
                Profil Saya</h2>
            <div class="flex flex-col-reverse md:flex-row md:items-start gap-4 md:gap-6 w-full px-2 md:px-4">
                <div class="md:hidden mt-4 flex items-end justify-end">
                    <a href="/profile/edit"
                        class="inline-block px-4 py-2 bg-gray-800 text-white dark:text-gray-200 dark-mode-transition rounded-md hover:bg-gray-900 transition text-sm md:text-md">
                        Edit Profil
                    </a>
                </div>
                <div class="flex flex-col md:flex-row items-center justify-center md:items-start gap-4 md:gap-6 w-full">
                    <div class="flex flex-col space-y-3 w-full md:w-2/3">
                        <div>
                            <label
                                class="block text-sm font-extrabold text-gray-700 dark:text-gray-200 dark-mode-transition">Nama
                                Lengkap</label>
                            <p class="mt-1 text-gray-900 dark:text-gray-200 dark-mode-transition text-sm">
                                {{ Auth::user()->name }}</p>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-extrabold text-gray-700 dark:text-gray-200 dark-mode-transition">NIM</label>
                            <p class="mt-1 text-gray-900 dark:text-gray-300 dark-mode-transition text-sm">
                                {{ Auth::user()->nim }}</p>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-extrabold text-gray-700 dark:text-gray-200 dark-mode-transition">Program
                                Studi</label>
                            <p class="mt-1 text-gray-900 dark:text-gray-300 dark-mode-transition text-sm">
                                {{ Auth::user()->programStudi->name ?? '-' }}</p>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-extrabold text-gray-700 dark:text-gray-200 dark-mode-transition">Golongan</label>
                            <p class="mt-1 text-gray-900 dark:text-gray-300 dark-mode-transition text-sm">
                                {{ Auth::user()->golongan->nama_golongan ?? '-' }}</p>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-extrabold text-gray-700 dark:text-gray-200 dark-mode-transition">Semester
                                tempuh</label>
                            <p class="mt-1 text-gray-900 dark:text-gray-300 dark-mode-transition text-sm">
                                {{ isset(Auth::user()->semester?->display_name) ? explode(' ', Auth::user()->semester->display_name)[1] ?? '' : '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-col space-y-3 w-full md:w-2/3">
                        <div>
                            <label
                                class="block text-sm font-extrabold text-gray-700 dark:text-gray-200 dark-mode-transition">No.
                                Hp
                                (WhatsApp)</label>
                            <p class="mt-1 text-gray-900 dark:text-gray-300 dark-mode-transition text-sm">
                                {{ Auth::user()->no_hp ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-extrabold text-gray-700 dark:text-gray-200 dark-mode-transition">Email</label>
                            <p class="mt-1 text-gray-900 dark:text-gray-300 dark-mode-transition text-sm">
                                {{ Auth::user()->email }}</p>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-extrabold text-gray-700 dark:text-gray-200 dark-mode-transition">Alamat</label>
                            <p class="mt-1 text-gray-900 dark:text-gray-300 dark-mode-transition text-sm">
                                {{ Auth::user()->alamat ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:space-y-24 items-center md:items-end w-full md:w-2/5">
                    <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/avatar-default.png') }}"
                        alt="Foto Profil"
                        class="w-[120px] h-[150px] md:w-[130px] md:h-[170px] rounded object-cover my-8 md:my-0 md:mb-20">

                    <div class="hidden md:block">
                        <a href="{{ route('profile.edit') }}"
                            class="inline-block px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-900 transition">
                            Edit Profil
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layout>
