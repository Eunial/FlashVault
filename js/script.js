/* TOAST / SNACKBAR FUNCTION */
function showToast(message) {
    let container = document.getElementById("toast-container");
    if (!container) {
        container = document.createElement("div");
        container.id = "toast-container";
        document.body.appendChild(container);
    }

    const toast = document.createElement("div");
    toast.className = "toast";
    toast.innerHTML = `<i class="fa-solid fa-circle-info"></i> <span>${message}</span>`;
    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.add("show");
    }, 50);

    setTimeout(() => {
        toast.classList.remove("show");
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}


/* DOM 1: LIVE FLASHCARD FLIPPER */
let isShowingAnswer = false;

function flipSampleCard() {
    const cardBox = document.getElementById("sampleCardContent");
    const cardTag = document.getElementById("sampleCardTag");
    const cardHint = document.getElementById("sampleCardHint");

    // Efek transisi sederhana saat membalik kartu
    cardBox.style.opacity = 0;

    setTimeout(() => {
        isShowingAnswer = !isShowingAnswer;
        if (isShowingAnswer) {
            cardTag.textContent = "JAWABAN";
            cardBox.innerHTML = "Proses pengulangan materi dengan jeda waktu terstruktur untuk memperkuat memori jangka panjang di otak.";
            cardHint.innerHTML = '<i class="fa-solid fa-rotate"></i> Klik untuk kembali ke Pertanyaan';
            showToast("Kartu dibalik ke sisi Jawaban");
        } else {
            cardTag.textContent = "PERTANYAAN (KLIK UNTUK MEMBALIK)";
            cardBox.innerHTML = "Apa fungsi utama dari algoritma Spaced Repetition dalam FlashVault?";
            cardHint.innerHTML = '<i class="fa-solid fa-rotate"></i> Klik kartu untuk melihat jawaban';
            showToast("Kartu dibalik ke sisi Pertanyaan");
        }
        cardBox.style.opacity = 1;
    }, 150);
}


/* DOM 2: DYNAMIC SAMPLE DECK FILTER */
function filterDecks(category, clickedBtn) {
    const buttons = document.querySelectorAll(".filter-btn");
    const decks = document.querySelectorAll(".sample-deck-card");

    // Mengatur tombol aktif
    buttons.forEach(btn => btn.classList.remove("active"));
    clickedBtn.classList.add("active");

    // Memfilter kartu deck berdasarkan kategori menggunakan looping & querySelectorAll
    decks.forEach(deck => {
        const deckCategory = deck.getAttribute("data-category");

        if (category === "all" || deckCategory === category) {
            deck.style.display = "block";
        } else {
            deck.style.display = "none";
        }
    });

    showToast(`Filter diterapkan: ${clickedBtn.textContent.trim()}`);
}


/* DOM 3: MINI-QUIZ WIDGET INSTAN */
function answerQuiz(selectedButton, isCorrect) {
    const options = document.querySelectorAll(".quiz-opt-btn");
    const feedbackBox = document.getElementById("quizFeedback");

    // Nonaktifkan semua tombol setelah dipilih
    options.forEach(opt => {
        opt.style.pointerEvents = "none";
    });

    if (isCorrect) {
        selectedButton.classList.add("correct");
        feedbackBox.innerHTML = '<span style="color: var(--success-color);"><i class="fa-solid fa-circle-check"></i> Benar! Spaced Repetition efektif melatih daya ingat jangka panjang.</span>';
        showToast("Jawaban Anda Benar!");
    } else {
        selectedButton.classList.add("incorrect");
        feedbackBox.innerHTML = '<span style="color: var(--danger-color);"><i class="fa-solid fa-circle-xmark"></i> Kurang tepat. Jawaban yang benar adalah Spaced Repetition.</span>';
        showToast("Jawaban Anda Kurang Tepat");
    }
}


/* PASANG EVENT LISTENER (addEventListener) */
document.addEventListener("DOMContentLoaded", () => {
    // Toast pada link navigasi
    document.querySelectorAll("[data-toast]").forEach(el => {
        el.addEventListener("click", () => showToast(el.dataset.toast));
    });

    // DOM 1: klik kartu untuk membalik
    const flashcard = document.getElementById("liveFlashcard");
    if (flashcard) flashcard.addEventListener("click", flipSampleCard);

    // DOM 2: tombol filter kategori
    document.querySelectorAll(".filter-btn").forEach(btn => {
        btn.addEventListener("click", () => filterDecks(btn.dataset.filter, btn));
    });

    // DOM 3: opsi mini-kuis
    document.querySelectorAll(".quiz-opt-btn").forEach(btn => {
        btn.addEventListener("click", () => answerQuiz(btn, btn.dataset.correct === "true"));
    });

    // muat sampel deck dari JSON (Fetch API + Promise)
    muatSampelDeck();
});


/* EVENT MOUSEOVER / MOUSEOUT: PRATINJAU DECK SAAT DI-HOVER */
function pasangHoverPreview(card) {
    // Bangun panel info dari atribut data-* milik kartu
    const meta = document.createElement("div");
    meta.className = "deck-hover-meta";
    meta.innerHTML = `
        <span class="deck-meta-chip"><i class="fa-solid fa-clone"></i> <span data-isi="kartu"></span> kartu</span>
        <span class="deck-meta-chip"><i class="fa-solid fa-clock"></i> ±<span data-isi="menit"></span> menit</span>
        <span class="deck-meta-chip"><i class="fa-solid fa-signal"></i> <span data-isi="level"></span></span>
    `;
    // Data dari JSON dimasukkan lewat textContent 
    meta.querySelector('[data-isi="kartu"]').textContent = card.dataset.cards;
    meta.querySelector('[data-isi="menit"]').textContent = card.dataset.minutes;
    meta.querySelector('[data-isi="level"]').textContent = card.dataset.level;
    card.appendChild(meta);

    card.addEventListener("mouseover", () => {
        card.classList.add("is-hovered");
    });

    card.addEventListener("mouseout", (e) => {
        // mouseout juga menyala saat kursor pindah antar elemen anak di dalam kartu.
        // Abaikan kasus itu supaya panel tidak berkedip.
        if (card.contains(e.relatedTarget)) return;
        card.classList.remove("is-hovered");
    });
}


/* PROMISE + FETCH API: SAMPEL DECK DARI FILE JSON DI DATA/SAMPLE-DECKS.JSON */
const URL_SAMPEL_DECK = "data/sample-decks.json";

// Promise buatan sendiri: "tunggu sekian milidetik"
const jeda = (ms) => new Promise(resolve => setTimeout(resolve, ms));

// Promise.race: fetch berlomba dengan timer. Siapa lebih dulu selesai, dialah hasilnya.
function fetchDenganTimeout(url, batasMs = 8000) {
    const timeout = new Promise((_, reject) => {
        setTimeout(() => reject(new Error("Waktu permintaan habis (timeout)")), batasMs);
    });
    return Promise.race([fetch(url), timeout]);
}

// Mengembalikan Promise berisi array deck, atau error jika ada yang salah
function ambilSampelDeck() {
    return fetchDenganTimeout(URL_SAMPEL_DECK)
        .then(response => {
            // fetch() TIDAK error untuk status 404/500, jadi harus dicek manual
            if (!response.ok) {
                throw new Error(`Server membalas dengan status ${response.status}`);
            }
            return response.json(); // juga sebuah Promise
        })
        .then(data => {
            if (!data || !Array.isArray(data.decks)) {
                throw new Error("Format data sampel deck tidak valid");
            }
            return data.decks;
        });
}

function setFilterAktif(aktif) {
    document.querySelectorAll(".filter-btn").forEach(btn => {
        btn.disabled = !aktif;
    });
}

function tampilkanSkeleton(grid) {
    grid.innerHTML = "";
    grid.setAttribute("aria-busy", "true");
    for (let i = 0; i < 3; i++) {
        const sk = document.createElement("div");
        sk.className = "skeleton-card";
        sk.innerHTML = '<div class="sk-line sk-badge"></div><div class="sk-line sk-title"></div><div class="sk-line"></div><div class="sk-line sk-short"></div>';
        grid.appendChild(sk);
    }
}

function renderSampelDeck(grid, decks) {
    grid.innerHTML = "";

    decks.forEach(deck => {
        const card = document.createElement("div");
        card.className = "sample-deck-card";
        card.dataset.category = deck.kategori;
        card.dataset.cards = deck.kartu;
        card.dataset.minutes = deck.menit;
        card.dataset.level = deck.level;

        const badge = document.createElement("span");
        badge.className = "deck-category-badge";
        badge.textContent = deck.kategoriLabel;

        const judul = document.createElement("h4");
        judul.textContent = deck.judul;

        const deskripsi = document.createElement("p");
        deskripsi.textContent = deck.deskripsi;

        const tombol = document.createElement("a");
        tombol.className = "btn-primary";
        tombol.href = "dashboard/kuis.html";
        tombol.textContent = "Buka Deck";

        card.append(badge, judul, deskripsi, tombol);
        grid.appendChild(card);
        pasangHoverPreview(card);
    });
}

function tampilkanError(grid, pesan) {
    grid.innerHTML = "";

    const kotak = document.createElement("div");
    kotak.className = "sample-error";

    const ikon = document.createElement("i");
    ikon.className = "fa-solid fa-triangle-exclamation";

    const judul = document.createElement("h4");
    judul.textContent = "Sampel deck gagal dimuat";

    const detail = document.createElement("p");
    detail.textContent = pesan;

    const ulangi = document.createElement("button");
    ulangi.type = "button";
    ulangi.className = "btn-retry";
    ulangi.innerHTML = '<i class="fa-solid fa-rotate-right"></i> Coba lagi';
    ulangi.addEventListener("click", muatSampelDeck);

    kotak.append(ikon, judul, detail, ulangi);
    grid.appendChild(kotak);
}

function pesanErrorRamah(err) {
    // Gagal terhubung (mis. dibuka lewat file:// atau offline) muncul sebagai TypeError
    if (err instanceof TypeError) {
        return "Tidak bisa mengambil data. Pastikan koneksi aktif dan project dibuka lewat Live Server (http://), bukan file://.";
    }
    return err.message;
}

function muatSampelDeck() {
    const grid = document.getElementById("sampleDecksGrid");
    if (!grid) return;

    tampilkanSkeleton(grid);
    setFilterAktif(false);

    // Promise.all: tunggu data DAN jeda minimal 600 ms agar skeleton tidak berkedip
    Promise.all([ambilSampelDeck(), jeda(600)])
        .then(([decks]) => {
            renderSampelDeck(grid, decks);
            setFilterAktif(true);
            showToast(`${decks.length} sampel deck berhasil dimuat`);
        })
        .catch(err => {
            console.error("Gagal memuat sampel deck:", err);
            tampilkanError(grid, pesanErrorRamah(err));
            showToast("Gagal memuat sampel deck");
        })
        .finally(() => {
            grid.removeAttribute("aria-busy"); // jalan baik sukses maupun gagal
        });
}