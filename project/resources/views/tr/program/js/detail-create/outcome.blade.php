<script>
    $(document).ready(function() {
    let outcomeIndex = $('#outcomeContainer .row').length;

    // Add Outcome button click handler
    $("#outcomeContainer").on("click", ".addOutcome", function() {
        outcomeIndex++;
        // Clone the template
        const $clone = $("#outcomeTemplate").clone().removeClass("d-none hehe").removeAttr("id").attr("data-outcome-index", outcomeIndex);
        $clone.find("textarea, input").each(function() {
            const nameBase = $(this).attr("name").replace("[]", "");
            $(this).attr("name", nameBase + "[]");
        });
        $("#outcomeContainer").append($clone);
        $clone.find("textarea").val("");
    });
    $(document).on("click", ".removeButton", function() {
        $(this).closest(".row").remove();
    });
});
</script>
