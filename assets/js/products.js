/**
 * Products Page JavaScript
 */

// View toggle functionality
const viewButtons = document.querySelectorAll('.view-btn');
const productsGrid = document.getElementById('productsGrid');

viewButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        viewButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        
        const view = btn.dataset.view;
        if (view === 'list') {
            productsGrid.classList.add('list-view');
            productsGrid.classList.remove('grid-view');
        } else {
            productsGrid.classList.add('grid-view');
            productsGrid.classList.remove('list-view');
        }
    });
});

// Auto-submit filter form on change (optional)
const filterForm = document.getElementById('filterForm');
const filterSelects = filterForm.querySelectorAll('select');

filterSelects.forEach(select => {
    select.addEventListener('change', () => {
        // Optional: Auto-submit on select change
        // filterForm.submit();
    });
});

// Smooth scroll to top on pagination
const paginationLinks = document.querySelectorAll('.pagination a');
paginationLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});

// Product card animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

document.querySelectorAll('.product-card').forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    observer.observe(card);
});

console.log('✨ Products page loaded successfully!');
