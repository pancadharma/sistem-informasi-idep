I'll highlight the key differences and additions made to implement pagination, excluding the sample data section:

Added new CSS styles for pagination:

/* Pagination styles */
.pagination {
    display: flex;
    padding-left: 0;
    list-style: none;
    border-radius: 0.25rem;
    justify-content: center;
    margin-top: 1rem;
}

.pagination li {
    margin: 0 2px;
}

.pagination .page-link {
    position: relative;
    display: block;
    padding: 0.5rem 0.75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: #007bff;
    background-color: #fff;
    border: 1px solid #dee2e6;
    cursor: pointer;
}

.pagination .page-link:hover {
    color: #0056b3;
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.pagination .active .page-link {
    z-index: 1;
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}

.pagination .disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    cursor: not-allowed;
    background-color: #fff;
    border-color: #dee2e6;
}

/* Items per page selector */
.items-per-page {
    margin: 1rem 0;
    text-align: right;
}

.items-per-page select {
    padding: 0.375rem 0.75rem;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    margin-left: 0.5rem;
}
Added new HTML elements for pagination:

<!-- Items per page selector -->
<div class="items-per-page">
    Show 
    <select id="itemsPerPage">
        <option value="5">5</option>
        <option value="10" selected>10</option>
        <option value="25">25</option>
        <option value="50">50</option>
    </select>
    entries
</div>
<!-- Pagination container -->
<div id="pagination" class="pagination">
    <!-- Pagination links will be dynamically populated -->
</div>
New JavaScript functionality added:

// New variables for pagination
let currentPage = 1;
let itemsPerPage = 10;
let filteredData = [];

// New event handler for items per page
$('#itemsPerPage').on('change', function() {
    itemsPerPage = parseInt($(this).val());
    currentPage = 1;
    renderTable();
    renderPagination();
});

// New pagination render function
function renderPagination() {
    const totalPages = Math.ceil(filteredData.length / itemsPerPage);
    let paginationHtml = `
        <li class="${currentPage === 1 ? 'disabled' : ''}">
            <span class="page-link" data-page="prev">Previous</span>
        </li>`;

    for (let i = 1; i <= totalPages; i++) {
        paginationHtml += `
            <li class="${currentPage === i ? 'active' : ''}">
                <span class="page-link" data-page="${i}">${i}</span>
            </li>`;
    }

    paginationHtml += `
        <li class="${currentPage === totalPages ? 'disabled' : ''}">
            <span class="page-link" data-page="next">Next</span>
        </li>`;

    $('#pagination').html(paginationHtml);

    // Add click handlers for pagination
    $('.page-link').click(function() {
        const page = $(this).data('page');
        if (page === 'prev' && currentPage > 1) {
            currentPage--;
        } else if (page === 'next' && currentPage < totalPages) {
            currentPage++;
        } else if (typeof page === 'number') {
            currentPage = page;
        }
        renderTable();
        renderPagination();
    });
}

// Modified renderTable function to handle pagination
function renderTable() {
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageData = filteredData.slice(start, end);

    $('#tableBody').empty();
    pageData.forEach(data => {
        // ... existing row rendering code ...
    });
}

// Modified search functionality to reset pagination
$('#search_peserta').on('input', function() {
    const searchTerm = $(this).val().toLowerCase();
    filteredData = sampleData.filter(item => {
        return Object.values(item).some(value => {
            if (Array.isArray(value)) {
                return value.some(v => v.text.toLowerCase().includes(searchTerm));
            }
            return String(value).toLowerCase().includes(searchTerm);
        });
    });
    currentPage = 1; // Reset to first page on search
    renderTable();
    renderPagination();
});
The main changes involve:

Adding pagination UI elements
Adding pagination styles
Implementing pagination logic with items per page
Modifying the table rendering to show only the current page's data
Integrating pagination with existing search and sort functionality
Adding pagination controls and their event handlers
These changes allow the table to display data in pages, with the ability to change how many items are shown per page and navigate between pages.