// 1. Use strict mode
'use strict';

// 2. Check for jQuery dependency
if (typeof $ === 'undefined') {
    console.error('jQuery is not included. Please include jQuery in your HTML file.');
    // Consider adding a more user-friendly error message or fallback
}

// 3. Encapsulate the entire script in an IIFE
(function ($) {
    // 4. Use let and const instead of var
    let rowCount = 0;
    let editDataForm = null;

    // 5. Use arrow functions for consistency
    const loadSelect2Option = () => {
        // ... (existing code)
    };

    // 6. Simplify updateAgeCheckmarks function
    const updateAgeCheckmarks = usiaCell => {
        const row = usiaCell.closest('tr')[0];
        const age = parseInt(usiaCell.text().trim(), 10);

        const ageRanges = [
            { selector: '.age-0-17', range: [0, 17] },
            { selector: '.age-18-24', range: [18, 24] },
            { selector: '.age-25-59', range: [25, 59] },
            { selector: '.age-60-plus', range: [60, Infinity] },
        ];

        ageRanges.forEach(({ selector, range }) => {
            row.querySelector(selector).innerHTML = age >= range[0] && age <= range[1] ? '<span class="checkmark">✔</span>' : '';
        });
    };

    // 7. Use template literals for string interpolation
    const addRow = data => {
        // ... (existing code)
        const newRow = `
            <tr data-row-id="${rowCount}" class="nowrap">
                <!-- ... (existing HTML) -->
            </tr>
        `;
        // ... (existing code)
    };

    // 8. Use async/await for better readability in AJAX calls
    const fetchDusunData = async params => {
        try {
            const response = await $.ajax({
                url: '{{ route("api.meals.dusun") }}',
                data: params,
                dataType: 'json',
            });
            return {
                results: response.results,
                pagination: response.pagination,
            };
        } catch (error) {
            console.error('Error fetching dusun data:', error);
            return { results: [], pagination: {} };
        }
    };

    // 9. Use event delegation for dynamically added elements
    $(document).on('click', '.edit-btn', function (e) {
        e.preventDefault();
        editRow(this);
        $('#editDataModal').modal('show');
    });

    // 10. Implement error handling
    const saveRow = () => {
        try {
            // ... (existing code)
        } catch (error) {
            console.error('Error saving row:', error);
            // Display user-friendly error message
        }
    };

    // ... (rest of the existing code)

    // 11. Use DOMContentLoaded event instead of document.ready
    document.addEventListener('DOMContentLoaded', () => {
        loadSelect2Option();
        // ... (rest of the initialization code)
    });
})(jQuery);
