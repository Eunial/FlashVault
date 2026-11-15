/* Pop-up: Alert Box untuk validasi form pendaftaran */

document.addEventListener("DOMContentLoaded", () => {
    const formDaftar = document.getElementById("regPassword")?.closest("form");
    if (!formDaftar) return;

    formDaftar.addEventListener("submit", (e) => {
        const nama = document.getElementById("regNama").value.trim();
        const email = document.getElementById("regEmail").value.trim();
        const password = document.getElementById("regPassword").value;

        if (nama === "") {
            e.preventDefault();
            alert("Nama lengkap tidak boleh kosong!");
            return;
        }
        if (password.length < 8) {
            e.preventDefault();
            alert("Kata sandi minimal 8 karakter!");
            return;
        }
    });
});