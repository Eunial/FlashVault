/*  Pop-up Boxes : Alert, Confirm, Prompt
    Event        : addEventListener (click, input, change, submit)
    Web Storage  : localStorage lewat FlashStore (js/storage.js) 
*/

document.addEventListener("DOMContentLoaded", () => {
    const punyaStore = typeof FlashStore !== "undefined";

    /* PROFIL: nama hasil register tampil di sapaan dan kartu pengguna sidebar */
    if (punyaStore) {
        const user = FlashStore.getUser();
        if (user) {
            const sapaan = document.getElementById("namaPengguna");
            if (sapaan) sapaan.textContent = user.nama; // textContent: aman dari XSS

            document.querySelectorAll(".user-details h4").forEach(el => {
                el.textContent = user.nama;
            });
            document.querySelectorAll(".user-avatar img").forEach(img => {
                img.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(user.nama)}&background=2196F3&color=fff`;
            });
        }
    }

    /* CONFIRM BOX 1: Konfirmasi Keluar Akun (semua halaman dashboard) */
    document.querySelectorAll(".logout-link, .btn-header-logout").forEach(link => {
        link.addEventListener("click", (e) => {
            const yakin = confirm("Apakah Anda yakin ingin keluar dari FlashVault?");
            if (!yakin) {
                e.preventDefault(); // batal: tetap di halaman ini
            }
        });
    });

    /* PENGATURAN: form profil diisi dari localStorage + simpan perubahan (pengaturan.html) */
    const inputProfNama = document.getElementById("profNama");
    if (punyaStore && inputProfNama) {
        const inputProfEmail = document.getElementById("profEmail");
        const user = FlashStore.getUser();
        if (user) {
            inputProfNama.value = user.nama;
            if (user.email) inputProfEmail.value = user.email;
        }

        inputProfNama.closest("form").addEventListener("submit", (e) => {
            e.preventDefault();

            const nama = inputProfNama.value.trim();
            const email = inputProfEmail.value.trim();

            if (nama === "") {
                alert("Nama lengkap tidak boleh kosong!");
                return;
            }
            if (!FlashStore.setUser({ nama, email })) {
                alert("Gagal menyimpan: penyimpanan browser tidak tersedia.");
                return;
            }
            alert("Profil berhasil diperbarui!");
            window.location.href = "../dashboard.html";
        });
    }

    /* OVERVIEW: Statistik koleksi dibaca dari localStorage (dashboard.html) */
    const statDeck = document.getElementById("statTotalDeck");
    if (punyaStore && statDeck) {
        statDeck.textContent = `${FlashStore.getDecks().length} Deck`;
        document.getElementById("statTotalKartu").textContent = `${FlashStore.totalKartu()} total kartu`;
    }

    /* KELOLA DECK: Render dari localStorage + Confirm, Alert, Prompt (kelola-deck.html) */
    const deckGrid = document.getElementById("deckGrid");
    if (punyaStore && deckGrid) {
        // Helper pembuat elemen. Data pengguna selalu lewat textContent (aman dari XSS).
        const buatEl = (tag, kelas, teks) => {
            const el = document.createElement(tag);
            if (kelas) el.className = kelas;
            if (teks !== undefined) el.textContent = teks;
            return el;
        };

        function renderDecks() {
            const decks = FlashStore.getDecks();
            deckGrid.innerHTML = "";

            if (decks.length === 0) {
                const kosong = buatEl("div", "empty-state");
                kosong.append(
                    buatEl("p", "", "Belum ada deck. Yuk buat deck pertama Anda!"),
                );
                const link = buatEl("a", "btn-primary-sm", "+ Buat Deck Baru");
                link.href = "tambah-deck.html";
                kosong.appendChild(link);
                deckGrid.appendChild(kosong);
                return;
            }

            decks.forEach(deck => {
                const card = buatEl("div", "card-item");
                card.dataset.id = deck.id;

                const info = document.createElement("div");
                info.append(
                    buatEl("h4", "", deck.nama),
                    buatEl("p", "", `${deck.jumlahKartu} Kartu • Kategori: ${deck.kategori}`)
                );

                const aksi = buatEl("div", "card-actions");

                const btnBuka = buatEl("a", "btn-sm btn-edit");
                btnBuka.href = "kuis.html";
                btnBuka.innerHTML = '<i class="fa-solid fa-folder-open"></i> Buka';

                const btnEdit = buatEl("button", "btn-sm btn-edit btn-rename");
                btnEdit.type = "button";
                btnEdit.innerHTML = '<i class="fa-solid fa-pen"></i> Edit';

                const btnHapus = buatEl("button", "btn-sm btn-delete");
                btnHapus.type = "button";
                btnHapus.innerHTML = '<i class="fa-solid fa-trash"></i> Hapus';

                aksi.append(btnBuka, btnEdit, btnHapus);
                card.append(info, aksi);
                deckGrid.appendChild(card);
            });
        }

        // Event delegation: satu listener untuk semua tombol, termasuk kartu yang dibuat dinamis
        deckGrid.addEventListener("click", (e) => {
            const tombol = e.target.closest(".btn-delete, .btn-rename");
            if (!tombol) return;

            const card = tombol.closest(".card-item");
            const id = card.dataset.id;
            const deck = FlashStore.getDecks().find(d => d.id === id);
            if (!deck) return;

            if (tombol.classList.contains("btn-delete")) {
                /* CONFIRM BOX 2 + ALERT BOX */
                const yakin = confirm(`Hapus deck "${deck.nama}"?\nSemua kartu di dalamnya akan ikut terhapus.`);
                if (!yakin) return;

                if (FlashStore.deleteDeck(id)) {
                    renderDecks();
                    alert(`Deck "${deck.nama}" berhasil dihapus.`);
                } else {
                    alert("Gagal menghapus: penyimpanan browser tidak tersedia.");
                }
            } else {
                /* PROMPT BOX: ubah nama deck */
                const namaBaru = prompt("Ubah nama deck:", deck.nama);
                if (namaBaru === null) return; // pengguna menekan Cancel

                const bersih = namaBaru.trim();
                if (bersih === "") {
                    alert("Nama deck tidak boleh kosong!");
                    return;
                }
                if (bersih.toLowerCase() !== deck.nama.toLowerCase() && FlashStore.exists(bersih)) {
                    alert(`Deck bernama "${bersih}" sudah ada!`);
                    return;
                }
                if (FlashStore.renameDeck(id, bersih)) {
                    renderDecks();
                } else {
                    alert("Gagal menyimpan: penyimpanan browser tidak tersedia.");
                }
            }
        });

        renderDecks();
    }

    /* EVENT CHANGE: Pratinjau Deck Langsung (tambah-deck.html) */
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
            const kategori = selectKategori.value;
            const gaya = KATEGORI[kategori];
            if (!gaya) return;

            previewCard.style.setProperty("--accent", gaya.warna);
            previewIcon.innerHTML = `<i class="fa-solid ${gaya.ikon}"></i>`;
            previewBadge.textContent = kategori;

            // Animasi kecil agar perubahan terasa hidup
            previewIcon.classList.add("pop");
            setTimeout(() => previewIcon.classList.remove("pop"), 250);
        });

        // Nama deck ikut tampil langsung saat diketik
        inputNama.addEventListener("input", () => {
            const nama = inputNama.value.trim();
            previewName.textContent = nama === "" ? "Nama deck Anda" : nama;
            preview.dataset.empty = nama === "" ? "true" : "false";
        });
    }

    /* ALERT BOX + LOCALSTORAGE: Simpan Deck Baru (tambah-deck.html) */
    const formDeck = document.getElementById("namaDeck")?.closest("form");
    if (punyaStore && formDeck) {
        formDeck.addEventListener("submit", (e) => {
            e.preventDefault(); // kita yang mengatur perpindahan halaman setelah data tersimpan

            const nama = document.getElementById("namaDeck").value.trim();
            const kategori = document.getElementById("kategoriDeck").value || "Lainnya";
            const deskripsi = document.getElementById("deskripsiDeck").value.trim();

            if (nama === "") {
                alert("Nama deck tidak boleh kosong!");
                return;
            }
            if (FlashStore.exists(nama)) {
                alert(`Deck bernama "${nama}" sudah ada. Gunakan nama lain!`);
                return;
            }

            const deckBaru = FlashStore.addDeck({ nama, kategori, deskripsi });
            if (!deckBaru) {
                alert("Gagal menyimpan: penyimpanan browser tidak tersedia.");
                return;
            }

            alert(`Deck "${nama}" berhasil disimpan!`);
            window.location.href = "kelola-deck.html";
        });
    }
});