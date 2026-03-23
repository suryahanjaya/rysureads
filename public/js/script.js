/*
 * RysuReads - Online Bookstore
 * Main JavaScript File
 */

// Basic interaction: show alert message
function showMessage(message) {
    // Remove existing toast if any
    var existing = document.querySelector('.custom-toast');
    if (existing) existing.remove();

    var toast = document.createElement('div');
    toast.className = 'custom-toast';
    toast.innerHTML = '<div class="toast-content">' + message + '</div>';
    document.body.appendChild(toast);

    // Show toast
    setTimeout(function () {
        toast.classList.add('show');
    }, 10);

    // Auto-remove after 3 seconds
    setTimeout(function () {
        toast.classList.remove('show');
        setTimeout(function () { toast.remove(); }, 300);
    }, 3000);
}

// Attach to "View Details" button - alert interaction
function showItemAlert(itemName) {
    showMessage(itemName + ' - added to cart!');
}

// Search filter for book cards
function filterBooks() {
    var input = document.getElementById('searchInput');
    if (!input) return;

    var query = input.value.toLowerCase().trim();
    var cards = document.querySelectorAll('.book-card-wrapper');

    cards.forEach(function (card) {
        var title = card.querySelector('.card-title');
        var text = title ? title.textContent.toLowerCase() : '';

        if (text.indexOf(query) !== -1) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

// Attach search listener on page load
document.addEventListener('DOMContentLoaded', function () {
    var searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', filterBooks);
    }
});
