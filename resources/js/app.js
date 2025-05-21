// import './bootstrap';
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});
// const homePage = document.getElementById('home-page');
// if (!homePage) return;

// Listen for the modal trigger
// Fungsi untuk membuka modal dan menampilkan informasi program studi
// function openModal(programName) {
//     document.getElementById('programModal').classList.remove('hidden');
//     document.getElementById('programDetails').innerText = `Details for: ${programName}`;
// }

// // Fungsi untuk menutup modal
// function closeModal() {
//     document.getElementById('programModal').classList.add('hidden');
// }

// }

// Program Studi Selection Functionality
// function initializeProgramStudiSelection() {
//     const programStudiDataElement = document.getElementById('program-studi-data');
//     if (!programStudiDataElement) return;

//     const programStudiData = JSON.parse(programStudiDataElement.dataset.programStudi);
//     const programStudiSelect = document.getElementById('program_studi');
//     const golonganSelect = document.getElementById('golongan');
//     const golonganIdInput = document.getElementById('golongan_id');

//     function updateGolonganOptions(programStudi) {
//         const golonganOptions = programStudiData[programStudi] ? Object.values(programStudiData[programStudi]) : [];
//         golonganSelect.innerHTML = '';

//         golonganOptions.forEach(golongan => {
//             const option = document.createElement('option');
//             option.value = golongan.id;
//             option.textContent = golongan.nama_golongan;
//             golonganSelect.appendChild(option);
//         });

//         if (golonganSelect.value) {
//             golonganIdInput.value = golonganSelect.value;
//         }
//     }

//     if (programStudiSelect) {
//         programStudiSelect.addEventListener('change', () => {
//             updateGolonganOptions(programStudiSelect.value);
//         });

//         // Initial update
//         updateGolonganOptions(programStudiSelect.value);
//     }

//     if (golonganSelect) {
//         golonganSelect.addEventListener('change', () => {
//             golonganIdInput.value = golonganSelect.value;
//         });
//     }
// }
