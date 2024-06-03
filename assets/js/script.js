function confirmDelete(stellingID) {
  if (confirm('Weet u zeker dat u deze stelling wilt verwijderen?')) {
      window.location.href = '../php_backend/delete_stelling.php?stellingID=' + stellingID;
  }
}

function confirmDelete2(PartijID) {
  if (confirm('Weet u zeker dat u deze partij wilt verwijderen?')) {
      window.location.href = '../php_backend/delete_partij.php?PartijID=' + PartijID;
  }
}

function confirmLogout() {
  if (confirm('Weet u zeker dat u uit wilt loggen?')) {
      window.location.href = '../php_backend/uitlog.php';
  }
}

// Function to handle search functionality
function searchItems() {
  // Get the search input value and convert it to lowercase for case-insensitive search
  var searchText = document.getElementById('searchInput').value.toLowerCase();

  // Get all table rows
  var rows = document.querySelectorAll('.item');

  // Loop through each row
  rows.forEach(function(row) {
      // Get the text content of the StellingInhoud column and StellingID column, and convert it to lowercase
      var stellingID = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
      var stellingInhoud = row.querySelector('td:nth-child(2)').textContent.toLowerCase();

      // Check if the row contains the search text in StellingID or StellingInhoud column, otherwise hide it
      if (stellingID.includes(searchText) || stellingInhoud.includes(searchText)) {
          row.style.display = '';
      } else {
          row.style.display = 'none';
      }
  });
}

// Add event listener to the search input field
document.getElementById('searchInput').addEventListener('input', searchItems);
