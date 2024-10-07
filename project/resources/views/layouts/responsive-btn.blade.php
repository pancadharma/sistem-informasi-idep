@push('css')
    <style>

        [class^="view-"] {
            display: inline-flex !important;
            justify-content: center !important;
            gap: 5px !important;
            flex-direction: column !important;
        }

        [class^="edit-"] {
            display: inline-flex !important;
            justify-content: center !important;
            gap: 5px !important;
            flex-direction: column !important;
        }

        td.vertical button[data-action="edit"], td.vertical button[data-action="view"] {
            flex-direction: column;
            align-items: center;
        }
        [class^="view-"]{
            display: inline-flex!important;
            justify-content: center!important;
            gap: 5px!important;
            flex-direction: column!important;
        }
        .button-container {
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        .button-container.vertical {
            flex-direction: column;
            align-items: center;
        }

        .button-container.vertical .btn {
            width: 100%;
            margin-bottom: 5px;
        }

    </style>
@endpush
@push('js')
<script>
$(document).ready(function() {
    function adjustButtonLayout() {
        $('.button-container').each(function() {
            var $container = $(this);
            var $table = $container.closest('table');
            var tableWidth = $table.width();
            var containerWidth = $container.width();

            if (containerWidth > tableWidth) {
                $container.addClass('vertical');
            } else {
                $container.removeClass('vertical');
            }
        });
    }

    // Call the function on page load
    adjustButtonLayout();

    // Call the function whenever the table structure changes
    $(window).on('resize', adjustButtonLayout);
    $('table').on('columnVisibility.dt', adjustButtonLayout);
});



</script>
@endpush
