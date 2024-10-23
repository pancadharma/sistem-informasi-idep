{{-- <script>
    $(document).ready(function() {
        let outcomeIndex = 0;

        $("#outcomeContainer").on("click", ".addOutcome", function() {
            outcomeIndex++;

            // Clone the template
            const $clone = $("#outcomeTemplate")
                .clone()
                .removeClass("d-none")
                .removeAttr("id")
                .attr("data-outcome-index", outcomeIndex);

            // Insert after the last outcome container or before template
            $("#outcomeContainer").append($clone);

            // Update names with proper indexing
            $clone.find("textarea").each(function() {
                const $input = $(this);
                const name = $input.attr("name").replace("[]", "[" + outcomeIndex + "]");
                $input.attr("name", name);
            });

            // Clear any values that might have been cloned
            $clone.find("textarea").val("");
        });

        // Remove button click handler - using event delegation
        $(document).on("click", ".removeButton", function() {
            $(this).closest(".hehe").remove();
        });

    });
</script> --}}
<script>
    $(document).ready(function() {
        let outcomeIndex = 0;

        // Add Outcome button click handler
        $("#outcomeContainer").on("click", ".addOutcome", function() {
            outcomeIndex++;

            // Clone the template
            const $clone = $("#outcomeTemplate").clone().removeClass("d-none hehe").removeAttr("id")
                .attr("data-outcome-index", outcomeIndex);

            // Insert the cloned template
            $("#outcomeContainer").append($clone);

            // Update names with proper indexing
            $clone.find("textarea").each(function() {
                const $input = $(this);
                const name = $input.attr("name").replace("[]", "[" + outcomeIndex + "]");
                $input.attr("name", name);
            });

            // Clear any values that might have been cloned
            $clone.find("textarea").val("");
        });

        // Remove button click handler - using event delegation
        $(document).on("click", ".removeButton", function() {
            $(this).closest(".row").remove();
        });
    });
</script>
