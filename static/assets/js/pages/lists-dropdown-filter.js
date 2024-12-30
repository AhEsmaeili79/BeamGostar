document.addEventListener('DOMContentLoaded', function() {
    // Handle search functionality in individual dropdowns
    const searchInputs = document.querySelectorAll('.dropdown-header input');

    searchInputs.forEach(input => {
        input.addEventListener('keyup', function() {
            // Find the closest dropdown to this search input
            const dropdown = input.closest('.dropdown-menu');
            const dropdownItems = dropdown.querySelectorAll('.dropdown-item');

            const value = input.value.toLowerCase();
            
            // Filter the dropdown items based on the search input
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

    // Handle Select/Deselect All buttons
    const selectAllButtons = document.querySelectorAll('.select-all');
    selectAllButtons.forEach(button => {
        button.addEventListener('click', function() {
            const dropdown = button.closest('.dropdown-menu');
            const checkboxes = dropdown.querySelectorAll('input[type="checkbox"]');
            const areAllChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = !areAllChecked;
            });
        });
    });

    // Clear filters button functionality
    document.getElementById('clearFilters').addEventListener('click', function() {
        document.getElementById('filterFirstName').value = '';
        document.getElementById('filterLastName').value = '';
        document.getElementById('filterNationalCode').value = '';
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => checkbox.checked = false);
        document.querySelectorAll('.dropdown-header input').forEach(input => input.value = '');
    });

    // Dropdown toggle visibility and close when clicked outside
    const dropdowns = document.querySelectorAll('.dropdown');

    dropdowns.forEach(dropdown => {
        const dropdownButton = dropdown.querySelector('.dropdown-toggle');
        const dropdownMenu = dropdown.querySelector('.dropdown-menu');
        
        // Toggle dropdown visibility when the button is clicked
        dropdownButton.addEventListener('click', function(event) {
            event.stopPropagation();  // Prevent click from closing dropdown immediately
            const isOpen = dropdownMenu.classList.contains('show');
            // Close all dropdowns first
            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('show'));
            // If dropdown wasn't open, open it
            if (!isOpen) {
                dropdownMenu.classList.add('show');
            }
        });

        // Close dropdown if clicked outside of the dropdown
        document.addEventListener('click', function(event) {
            if (!dropdown.contains(event.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    });
});
