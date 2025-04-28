import "./bootstrap";
import "flowbite";
// import Alpine from "alpinejs";

// window.Alpine = Alpine;

// Alpine.start();
import Swal from "sweetalert2";
window.Swal = Swal;

window.confirmAlert = function (message, confirmButtonText, callback) {
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: message || "Data akan dihapus secara permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: confirmButtonText || "Ya, hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            // Callback function to execute after confirmation
            if (typeof callback === "function") {
                callback();
            }
        }
    });
};
window.feedback = function (title, message, icon) {
    let timerInterval;
    Swal.fire({
        title: title,
        html: message,
        icon: icon,
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false,
        willClose: () => {
            clearInterval(timerInterval);
        },
    }).then((result) => {
        /* Read more about handling dismissals below */
        if (result.dismiss === Swal.DismissReason.timer) {
            console.log("I was closed by the timer");
        }
    });
};

window.rupiah = function (angka) {
    const numberString = angka.toString();
    const sisa = numberString.length % 3;
    let rupiah = numberString.substr(0, sisa);
    const ribuan = numberString.substr(sisa).match(/\d{3}/g);

    if (ribuan) {
        const separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
    }
    return "Rp " + rupiah + ",00";
};

window.confirmRejectWithReason = function (
    message,
    confirmButtonText,
    callback
) {
    Swal.fire({
        title: "Apakah Anda yakin ingin menolak?",
        text: message || "Masukkan alasan penolakan:",
        input: "textarea", // ✅ Inputan alasan
        inputPlaceholder: "Tulis alasan penolakan di sini...",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: confirmButtonText || "Tolak",
        cancelButtonText: "Batal",
        inputValidator: (value) => {
            if (!value) {
                return "Alasan wajib diisi!";
            }
        },
    }).then((result) => {
        if (result.isConfirmed) {
            // Callback function after input
            if (typeof callback === "function") {
                callback(result.value); // Pass alasan ke callback
            }
        }
    });
};
