document.addEventListener('DOMContentLoaded', function() {
    const pageSelect = document.getElementById('page-select');
    
    // Set the current page in the dropdown
    const currentPage = window.location.pathname.split('/').pop();
    pageSelect.value = currentPage;

    // Add event listener to switch pages when a different option is selected
    pageSelect.addEventListener('change', function() {
        window.location.href = pageSelect.value;
    });
});
