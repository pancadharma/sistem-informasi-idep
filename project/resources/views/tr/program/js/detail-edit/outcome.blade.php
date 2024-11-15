<script>
    $(document).ready(function() {
        let outcomeIndex = {{ $program->outcome->count() }}; // Start index after existing outcomes
        $("#outcomeContainer").on("click", ".addOutcome", function() {
            outcomeIndex++; // Increment the index for new outcome
            const $clone = $("#outcomeTemplate").clone().removeClass("d-none hehe").removeAttr("id").attr("data-outcome-index", outcomeIndex);
            $("#outcomeContainer").append($clone);

            // Set new names and clear values
            $clone.find("textarea").each(function() {
                const $input = $(this);
                const name = $input.attr("name").replace("[]", "[" + outcomeIndex + "]"); // Properly set the new index
                $input.attr("name", name);
                $input.val(""); // Clear value for new outcomes
            });
        });

        $(document).on("click", ".removeButton", function() {
            $(this).closest(".row").remove();
        });
    });

    $(document).ready(function() {
        // Check for the 'tab' query parameter
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');

        // If 'tab' parameter is present, activate the corresponding tab and scroll to it
        if (tab) {
            $(`#custom-tabs-four-${tab}-tab`).tab('show').on('shown.bs.tab', function() {
                $('html, body').animate({
                    scrollTop: $(`#custom-tabs-four-${tab}`).offset().top
                }, 1000); // Adjust the scrolling speed as needed

                $('#deskripsi_outcome').focus();
            });
        }
    });


</script>
