document.addEventListener("DOMContentLoaded", () => {
        /* CONFIRM BOX 1: Konfirmasi Keluar Akun (semua halaman dashboard) */
    document.querySelectorAll(".logout-link, .btn-header-logout").forEach(link => {
        link.addEventListener("click", (e) => {
            const yakin = confirm("Apakah Anda yakin ingin keluar dari FlashVault?");
            if (!yakin) {
                e.preventDefault(); // batal: tetap di halaman ini
            }
        });
    });

    /* CONFIRM BOX 2: Konfirmasi hapus deck (kelola-deck.php) - data dihapus lewat PHP + MySQL */
    document.querySelectorAll(".form-hapus").forEach(form => {
        form.addEventListener("submit", (e) => {
            const yakin = confirm(`Hapus deck "${form.dataset.nama}"?\nSemua kartu di dalamnya akan ikut terhapus.`);
            if (!yakin) e.preventDefault();
        });
    });

    /* EVENT CHANGE: Pratinjau Deck Langsung (tambah-deck.php) */
    const selectKategori = document.getElementById("kategoriDeck");
    if (selectKategori) {
        const KATEGORI = {
            "Web Development":    { warna: "#2196f3", ikon: "fa-globe" },
            "Mobile Development": { warna: "#7b1fa2", ikon: "fa-mobile-screen" },
            "Frontend":           { warna: "#00897b", ikon: "fa-palette" },
            "Backend":            { warna: "#ef6c00", ikon: "fa-server" },
            "Data Science & ML":  { warna: "#2e7d32", ikon: "fa-chart-line" },
            "Lainnya":            { warna: "#546e7a", ikon: "fa-shapes" }
        };

        const preview = document.getElementById("deckPreview");
        const previewCard = preview.querySelector(".deck-preview-card");
        const previewIcon = document.getElementById("previewIcon");
        const previewBadge = document.getElementById("previewBadge");
        const previewName = document.getElementById("previewName");
        const inputNama = document.getElementById("namaDeck");

        // Event change: kategori dipilih -> warna, ikon, dan badge ikut berubah
        selectKategori.addEventListener("change", () => {
            const kategori = selectKategori.selectedOptions[0]?.text || "";
            const gaya = KATEGORI[kategori];
            if (!gaya) return;

            previewCard.style.setProperty("--accent", gaya.warna);
            previewIcon.innerHTML = `<i class="fa-solid ${gaya.ikon}"></i>`;
            previewBadge.textContent = kategori;

            // Animasi kecil agar perubahan terasa hidup
            previewIcon.classList.add("pop");
            setTimeout(() => previewIcon.classList.remove("pop"), 250);
        });

        // Halaman edit: kategori sudah terpilih dari database -> tampilkan pratinjaunya
        if (selectKategori.value !== "") selectKategori.dispatchEvent(new Event("change"));

        // Nama deck ikut tampil langsung saat diketik
        inputNama.addEventListener("input", () => {
            const nama = inputNama.value.trim();
            previewName.textContent = nama === "" ? "Nama deck Anda" : nama;
            preview.dataset.empty = nama === "" ? "true" : "false";
        });
    }
});
