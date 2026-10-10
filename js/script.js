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
function filterDecks(category) {
    const buttons = document.querySelectorAll(".filter-btn");
    const decks = document.querySelectorAll(".sample-deck-card");

    // Mengatur tombol aktif
    buttons.forEach(btn => btn.classList.remove("active"));
    event.target.classList.add("active");

    // Memfilter kartu deck berdasarkan kategori menggunakan looping & querySelectorAll
    decks.forEach(deck => {
        const deckCategory = deck.getAttribute("data-category");

        if (category === "all" || deckCategory === category) {
            deck.style.display = "block";
        } else {
            deck.style.display = "none";
        }
    });

    showToast(`Filter diterapkan: ${event.target.textContent}`);
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