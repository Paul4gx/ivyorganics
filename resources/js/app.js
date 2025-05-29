import './bootstrap';
// Mobile menu toggle
document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
    const menu = document.getElementById('mobile-menu');
    menu.classList.toggle('hidden');
});

// Cart quantity controls (for cart page)
document.querySelectorAll('.cart-quantity-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('form');
        form.submit();
    });
});

// Initialize Alpine.js for any reactive components
document.addEventListener('DOMContentLoaded', function() {
    // Any initialization code
});