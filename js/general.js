document.addEventListener("DOMContentLoaded", function() {
  const searchInput = document.getElementById('search');
  const table = document.querySelector('table');
  const tableRows = Array.from(document.querySelectorAll('table tbody tr')); // Select all rows in tbody
  const filterElement = document.querySelector('.filter');
  const filterIcon = filterElement.querySelector('i');

  searchInput.addEventListener('input', function() {
    const query = searchInput.value.toLowerCase();

    tableRows.forEach(row => {
      const cells = row.querySelectorAll('td');
      const match = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(query));

      if (match) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  });

  filterElement.addEventListener('click', function() {
    const tbody = table.querySelector('tbody');
    const reversedRows = tableRows.reverse();

    reversedRows.forEach(row => tbody.appendChild(row));

    if (filterIcon.classList.contains('fa-arrow-down-wide-short')) {
      filterIcon.classList.remove('fa-arrow-down-wide-short');
      filterIcon.classList.add('fa-arrow-up-wide-short');
    } else {
      filterIcon.classList.remove('fa-arrow-up-wide-short');
      filterIcon.classList.add('fa-arrow-down-wide-short');
    }
  });
});