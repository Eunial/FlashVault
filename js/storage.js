const FlashStore = (() => { //untuk reset data, menambahkan deck, menghapus deck, dsb
    const KEY = "flashvault_decks";
    const USER_KEY = "flashvault_user";

    // Data contoh: dipakai saat pertama kali dibuka atau saat storage rusak
    const SEED = [
        { id: "seed-laravel", nama: "Fundamental Laravel & Blade", kategori: "Web Development", deskripsi: "", jumlahKartu: 45 },
        { id: "seed-rn", nama: "Komponen React Native", kategori: "Mobile Development", deskripsi: "", jumlahKartu: 32 },
        { id: "seed-js", nama: "Vanilla JS DOM Manipulation", kategori: "Frontend", deskripsi: "", jumlahKartu: 28 }
    ];

    const salin = (data) => JSON.parse(JSON.stringify(data));

    function simpan(decks) {
        try {
            localStorage.setItem(KEY, JSON.stringify(decks));
            return true;
        } catch (err) {
            // Mode privat / storage penuh / diblokir browser
            console.warn("FlashStore: gagal menyimpan ke localStorage", err);
            return false;
        }
    }

    function getDecks() {
        try {
            const raw = localStorage.getItem(KEY);
            if (raw === null) {
                simpan(SEED);
                return salin(SEED);
            }
            const data = JSON.parse(raw);
            if (Array.isArray(data)) return data;
        } catch (err) {
            console.warn("FlashStore: data rusak atau tidak bisa dibaca", err);
        }
        return salin(SEED);
    }

    function buatId() {
        return Date.now().toString(36) + Math.random().toString(36).slice(2, 6);
    }

    function addDeck({ nama, kategori, deskripsi }) {
        const decks = getDecks();
        const deck = {
            id: buatId(),
            nama,
            kategori,
            deskripsi: deskripsi || "",
            jumlahKartu: 0,
            dibuat: new Date().toISOString()
        };
        decks.push(deck);
        return simpan(decks) ? deck : null;
    }

    function deleteDeck(id) {
        const decks = getDecks().filter(d => d.id !== id);
        return simpan(decks);
    }

    function renameDeck(id, namaBaru) {
        const decks = getDecks();
        const target = decks.find(d => d.id === id);
        if (!target) return false;
        target.nama = namaBaru;
        return simpan(decks);
    }

    function exists(nama) {
        const cari = nama.trim().toLowerCase();
        return getDecks().some(d => d.nama.trim().toLowerCase() === cari);
    }

    function totalKartu() {
        return getDecks().reduce((jumlah, d) => jumlah + (Number(d.jumlahKartu) || 0), 0);
    }

    function reset() {
        return simpan(SEED);
    }

    /* Profil user (hasil register) 
       Sengaja cuman nyimpan nama sama email. Kata sandi tidak pernah disimpan (next bab dah). */
    function getUser() {
        try {
            const raw = localStorage.getItem(USER_KEY);
            if (raw === null) return null;
            const user = JSON.parse(raw);
            if (user && typeof user.nama === "string" && user.nama.trim() !== "") return user;
        } catch (err) {
            console.warn("FlashStore: data pengguna rusak atau tidak bisa dibaca", err);
        }
        return null;
    }

    function setUser({ nama, email }) {
        try {
            localStorage.setItem(USER_KEY, JSON.stringify({ nama, email: email || "" }));
            return true;
        } catch (err) {
            console.warn("FlashStore: gagal menyimpan data pengguna", err);
            return false;
        }
    }

    return { getDecks, addDeck, deleteDeck, renameDeck, exists, totalKartu, reset, getUser, setUser };
})();