import Echo from 'laravel-echo';

Echo.channel('presensi')
.listen('.presensi.created', (e) => {
    const container = document.getElementById("list=presensi");
    const waktu = new Date(e.presensi.waktu_presensi).toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
    });

    const el = document.createElement("p");
    el.className = "mt-1 max-w-lg text-sm/6 text-gray-600 lg:text-start";
    el.innerText =
        `${container.children.length + 1}. ${e.presensi.user.name}, telah melakukan presensi pada ${waktu} di mata kuliah ${e.presensi.mata_kuliah}`;
    container.prepend(el);
});