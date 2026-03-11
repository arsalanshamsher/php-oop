// Initialize Tooltips
document.addEventListener('DOMContentLoaded', function () {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
});


// Sidebar Toggle
document.getElementById('sidebarToggle').addEventListener('click', function () {
    document.querySelector('.sidebar').classList.toggle('d-none');
});

// Close sidebar on mobile
document.getElementById('closeSidebar')?.addEventListener('click', function () {
    document.querySelector('.sidebar').classList.add('d-none');
});
