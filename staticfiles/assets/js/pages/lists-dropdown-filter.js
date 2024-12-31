// JavaScript to handle multi-select and search functionality inside dropdowns
document.addEventListener('DOMContentLoaded', function() {
    const searchInputs = document.querySelectorAll('.dropdown-header input');
    const dropdownItems = document.querySelectorAll('.dropdown-item');
    const selectAllButtons = document.querySelectorAll('.select-all');
    
    searchInputs.forEach(input => {
        input.addEventListener('keyup', function() {
            const value = input.value.toLowerCase();
            dropdownItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(value)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    dropdownItems.forEach(item => {
        item.addEventListener('click', function() {
            const checkbox = item.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked;
        });
    });
    
    // Handle Select/Deselect All functionality
    selectAllButtons.forEach(button => {
        button.addEventListener('click', function() {
            const dropdown = button.closest('.dropdown-menu');
            const checkboxes = dropdown.querySelectorAll('input[type="checkbox"]');
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
        });
    });

    // Clear Filters functionality
    document.getElementById('clearFilters').addEventListener('click', function() {
        // Clear text inputs
        document.querySelectorAll('.form-control-sm').forEach(input => input.value = '');
        
        // Uncheck all checkboxes
        document.querySelectorAll('.form-check-input').forEach(input => input.checked = false);
    });
});
