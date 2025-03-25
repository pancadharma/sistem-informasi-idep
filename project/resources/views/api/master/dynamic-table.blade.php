<script>
    // Dynamic Table Search and Sort Functionality
(function($) {
    $.fn.dynamicTableHandler = function(options) {
        // Default settings
        const defaults = {
            searchInputSelector: '#search_input',
            noResultsMessage: 'No data found',
            excludeLastColumn: true,
            onNoResults: null
        };

        // Merge default settings with user-provided options
        const settings = $.extend({}, defaults, options);

        // Return this for each table to allow chaining
        return this.each(function() {
            const $table = $(this);
            const $tbody = $table.find('tbody');
            const $searchInput = $(settings.searchInputSelector);

            // Add no results row if it doesn't exist
            function ensureNoResultsRow() {
                if ($tbody.find('.no-results-row').length === 0) {
                    const columnCount = $tbody.find('tr:first-child td').length;
                    const noResultsRow = `<tr class="no-results-row" style="display:none;">
                        <td colspan="${columnCount}" class="text-center text-muted">
                            ${settings.noResultsMessage}
                        </td>
                    </tr>`;
                    $tbody.append(noResultsRow);
                }
            }

            // Search functionality
            function performSearch() {
                const searchTerm = $searchInput.val().toLowerCase().trim();
                const $rows = $tbody.find('tr').not('.no-results-row');

                let visibleRowsCount = 0;
                $rows.each(function() {
                    const $row = $(this);
                    const rowText = settings.excludeLastColumn
                        ? $row.find('td').not(':last').text().toLowerCase()
                        : $row.text().toLowerCase();

                    const isMatch = searchTerm === '' || rowText.includes(searchTerm);
                    $row.toggle(isMatch);

                    if (isMatch) visibleRowsCount++;
                });

                // Handle no results scenario
                const $noResultsRow = $tbody.find('.no-results-row');
                if (visibleRowsCount === 0) {
                    $noResultsRow.show();
                    if (typeof settings.onNoResults === 'function') {
                        settings.onNoResults();
                    }
                } else {
                    $noResultsRow.hide();
                }
            }

            // Sorting functionality
            function setupSorting() {
                let sortDirection = 1;
                let lastSortedTh = null;

                $table.find('th').not('[data-dt-order]').css('cursor', 'pointer').click(function() {
                    const $th = $(this);
                    const index = $th.index();
                    const $rows = $tbody.find('tr').not('.no-results-row').get();

                    // Update sort direction
                    if (lastSortedTh && lastSortedTh[0] === $th[0]) {
                        sortDirection *= -1;
                    } else {
                        sortDirection = 1;
                        $table.find('th').removeClass('asc desc');
                    }
                    lastSortedTh = $th;

                    // Update sort indicator
                    $th.removeClass('asc desc').addClass(sortDirection === 1 ? 'asc' : 'desc');

                    // Sort rows
                    $rows.sort(function(a, b) {
                        const $aCell = $(a).find('td').eq(index);
                        const $bCell = $(b).find('td').eq(index);

                        // Try to get value from data attributes or text
                        const sortAttributes = ['nama', 'usia', 'rt', 'rw'];
                        let aValue, bValue;

                        for (let attr of sortAttributes) {
                            if (!aValue) aValue = $aCell.data(attr);
                            if (!bValue) bValue = $bCell.data(attr);
                        }

                        // Fallback to text if no data attributes found
                        aValue = aValue || $aCell.text().trim();
                        bValue = bValue || $bCell.text().trim();

                        // Handle numeric values
                        if (!isNaN(aValue) && !isNaN(bValue)) {
                            return (Number(aValue) - Number(bValue)) * sortDirection;
                        }

                        // Handle text values
                        return aValue.localeCompare(bValue) * sortDirection;
                    });

                    // Reorder rows in the table
                    $tbody.empty().append($rows);
                });
            }

            // Initialize
            function init() {
                ensureNoResultsRow();

                // Attach search event
                $searchInput.on('input', performSearch);

                // Setup sorting
                setupSorting();
            }

            // Run initialization
            init();
        });
    };

    // Optional: Auto-initialize tables with a specific class
    $(document).ready(function() {
        $('.dynamic-table').each(function() {
            $(this).dynamicTableHandler({
                searchInputSelector: '#search_input'
            });
        });
    });
})(jQuery);

</script>

<!-- how to user this script
// Basic usage
$('.your-table-class').dynamicTableHandler();

// Advanced usage with options
$('#specific-table').dynamicTableHandler({
    searchInputSelector: '#custom-search-input',
    noResultsMessage: 'No matching records found',
    excludeLastColumn: false,
    onNoResults: function() {
        console.log('No results found');
    }
});

-->
