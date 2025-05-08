import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import NProgress from 'nprogress';
import $ from 'jquery';

// // Set jQuery ke window object
// window.$ = $;

// Pusher Configuration
window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

window.Echo.channel('presensi')
    .listen('eventPresensi', (event) => {
        console.log(event);
        refreshPresensi();
    })
    .error((error) => {
        console.log("Error:", error);
    });

const homePage = document.getElementById('home-page');
// if (!homePage) return;

let lastDataLength = 0;

function refreshPresensi() {
    fetch('/presensi/today')
        .then(response => response.json())
        .then(data => {
            console.log('Data Presensi:', data);
            const presensiList = document.getElementById('presensi-list');
            if (!presensiList) return;

            presensiList.innerHTML = ''; // Clear presensi list sebelum menambahkan data baru

            if (data.length === 0) {
                presensiList.innerHTML = `
                        <div class="flex justify-center items-center h-24 mt-25">
                            <p class="text-gray-500 italic text-center">
                                Belum ada yang melakukan presensi pada hari ini.
                            </p>
                        </div>
                    `;
                return;
            }

            // Menambahkan data presensi ke dalam list
            data.reverse().forEach((item, index) => {
                const presensiItem = document.createElement('p');
                presensiItem.classList.add('mt-1', 'max-w-lg', 'text-sm/6', 'text-gray-600', 'lg:text-start');
                presensiItem.innerHTML = `${index + 1}. <strong>${item.user.name}</strong>, telah melakukan presensi pada mata kuliah
                    <strong>${item.mata_kuliah.name}</strong> di <strong>${item.jadwal_kuliah.ruangan.name}</strong>
                    pada <strong>${new Date(item.waktu_presensi).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false })}</strong>.`;

                presensiList.appendChild(presensiItem);
            });

            // Auto-scroll ke bawah jika data baru ditambahkan
            if (data.length > lastDataLength) {
                presensiList.scrollTop = presensiList.scrollHeight;
            }
            lastDataLength = data.length;
        })
        .catch(error => console.error('Error:', error));
}

// Fungsi untuk refresh rekap presensi
function refreshRekapPresensi() {
    fetch('/rekap/presensi/json')
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('rekap-container');
            if (!container) return;

            container.innerHTML = ''; // Clear rekap sebelum menambahkan data baru
            data.rekapPresensi.forEach(item => {
                container.innerHTML += `
                    <div class="relative mt-5">
                        <div class="absolute inset-px rounded-lg bg-white ring-1 shadow-sm ring-black/5 lg:rounded-lg"></div>
                        <a href="/detail-presensi/${encodeURIComponent(item.program_studi)}" class="relative flex flex-col overflow-hidden lg:rounded-2xl cursor-pointer">
                            <div class="px-6 p-3 lg:px-6 sm:p-3">
                                <div class="flex justify-between">
                                    <p class="text-lg font-medium tracking-tight text-black">
                                        ${item.program_studi}
                                    </p>

                                    <div class="flex justify-between space-x-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-7 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd"/>
                                        </svg>
                                        <p class="text-lg text-gray-600 max-w-lg pr-5">${item.sudah}</p>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-7 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd"/>
                                        </svg>
                                        <p class="text-lg text-gray-600 max-w-lg pr-5">${item.belum}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                `;
            });

            // Update total presensi
            const totalSudahEl = document.getElementById('total-sudah-value');
            const totalBelumEl = document.getElementById('total-belum-value');
            if (totalSudahEl) totalSudahEl.textContent = data.totalSudah;
            if (totalBelumEl) totalBelumEl.textContent = data.totalBelum;
        })
        .catch(err => console.error('Gagal ambil rekap:', err));
}
// Inisialisasi interval untuk refresh
setInterval(refreshPresensi, 3000);  // Refresh setiap 5 detik
setInterval(refreshRekapPresensi, 3000);  // Refresh setiap 5 detik

refreshPresensi();
refreshRekapPresensi();

// Listen for the modal trigger
// Fungsi untuk membuka modal dan menampilkan informasi program studi
function openModal(programName) {
    document.getElementById('programModal').classList.remove('hidden');
    document.getElementById('programDetails').innerText = `Details for: ${programName}`;
}


// Fungsi untuk menutup modal
function closeModal() {
    document.getElementById('programModal').classList.add('hidden');
}


// }


// Program Studi Selection Functionality
function initializeProgramStudiSelection() {
    const programStudiDataElement = document.getElementById('program-studi-data');
    if (!programStudiDataElement) return;

    const programStudiData = JSON.parse(programStudiDataElement.dataset.programStudi);
    const programStudiSelect = document.getElementById('program_studi');
    const golonganSelect = document.getElementById('golongan');
    const golonganIdInput = document.getElementById('golongan_id');

    function updateGolonganOptions(programStudi) {
        const golonganOptions = programStudiData[programStudi] ? Object.values(programStudiData[programStudi]) : [];
        golonganSelect.innerHTML = '';

        golonganOptions.forEach(golongan => {
            const option = document.createElement('option');
            option.value = golongan.id;
            option.textContent = golongan.nama_golongan;
            golonganSelect.appendChild(option);
        });

        if (golonganSelect.value) {
            golonganIdInput.value = golonganSelect.value;
        }
    }

    if (programStudiSelect) {
        programStudiSelect.addEventListener('change', () => {
            updateGolonganOptions(programStudiSelect.value);
        });

        // Initial update
        updateGolonganOptions(programStudiSelect.value);
    }

    if (golonganSelect) {
        golonganSelect.addEventListener('change', () => {
            golonganIdInput.value = golonganSelect.value;
        });
    }
}


