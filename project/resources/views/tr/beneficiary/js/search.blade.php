<script>
    $(document).ready(function() {
        // Search functionality
        $('#search_peserta').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('#tableBody tr').each(function() {
                const rowText = $(this).find('td').not(':last').text().toLowerCase();
                $(this).toggle(rowText.includes(searchTerm));
            });
        });

        // Sorting functionality
        let sortDirection = 1;
        let lastSortedTh = null;

        $('th').not('[data-dt-order]').css('cursor', 'pointer').click(function() {
            const th = $(this);
            const index = th.index();
            const rows = $('#tableBody tr').get();

            // Update sort direction
            if (lastSortedTh && lastSortedTh[0] === th[0]) {
                sortDirection *= -1;
            } else {
                sortDirection = 1;
                if (lastSortedTh) {
                    lastSortedTh.removeClass('asc desc');
                }
            }
            lastSortedTh = th;

            // Update sort indicator
            th.removeClass('asc desc').addClass(sortDirection === 1 ? 'asc' : 'desc');

            // Sort rows
            rows.sort(function(a, b) {
                const aCell = $(a).find('td').eq(index);
                const bCell = $(b).find('td').eq(index);

                let aValue = aCell.data('nama') ||
                            aCell.data('usia') ||
                            aCell.data('rt') ||
                            aCell.data('rw') ||
                            aCell.text().trim();
                let bValue = bCell.data('nama') ||
                            bCell.data('usia') ||
                            bCell.data('rt') ||
                            bCell.data('rw') ||
                            bCell.text().trim();

                // Handle numeric values
                if (!isNaN(aValue) && !isNaN(bValue)) {
                    return (Number(aValue) - Number(bValue)) * sortDirection;
                }

                // Handle text values
                return aValue.localeCompare(bValue) * sortDirection;
            });

            // Reorder rows in the table
            $('#tableBody').append(rows);
        });
    });
</script>
