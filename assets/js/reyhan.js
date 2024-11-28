document.addEventListener('DOMContentLoaded', function() {
    const filterMenu = document.getElementById('filterMenu');
    const tableRows = document.querySelectorAll('tbody tr');

    filterMenu.addEventListener('click', function(event) {
      const filter = event.target.textContent.trim();
      tableRows.forEach(row => {
        if (filter === 'All' || row.dataset.status === filter) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });
  });

function updateTitle(event) {
    event.preventDefault(); // Mencegah tindakan default
    const element = event.target; // Elemen yang diklik
    const button = element.closest('.btn-group').querySelector('.dropdown-toggle'); // Tombol dropdown terdekat
    button.textContent = element.textContent; // Perbarui teks tombol
}

