const editModal = document.getElementById('editModal');

function openEditModal(btn) {
    editModal.style.display = 'block';
    document.getElementById('editBookingID').value = btn.dataset.id;
    document.getElementById('editStart').value = btn.dataset.start;
    document.getElementById('editEnd').value = btn.dataset.end;
    document.getElementById('editPickup').value = btn.dataset.pickup;
    document.getElementById('editAddress').value = btn.dataset.address;
}

function closeEditModal() {
    editModal.style.display = 'none';
}

/* Close modal on outside click */
window.onclick = function (e) {
    if (e.target === editModal) {
        closeEditModal();
    }
};

/* LIVE SEARCH */
document.getElementById('searchInput').addEventListener('keyup', function () {
    const value = this.value.toLowerCase();
    const rows = document.querySelectorAll('#bookingTable tbody tr');

    rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(value)
            ? ''
            : 'none';
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const tableRows = document.querySelectorAll("tbody tr");

    searchInput.addEventListener("keyup", function () {
        const searchValue = this.value.toLowerCase();

        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(searchValue) ? "" : "none";
        });
    });
});
