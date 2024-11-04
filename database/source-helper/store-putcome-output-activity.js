<script>
// if we are using summernote to fill the value of the textarea for the activity
// we need to initialize it

$(document).ready(function () {
	// Initialize Summernote
	$("textarea.summernote").summernote({
		height: 100,
		width: "100%",
		toolbar: [
			["style", ["bold", "italic"]],
			["color", ["color"]],
			["paragraph", ["paragraph"]],
			["view", ["fullscreen", "codeview"]],
		],
		inheritPlaceholder: true,
	});

	// Submit form via AJAX
	$("#formAddOutput").submit(function (event) {
		event.preventDefault();

		// Get Summernote data
		let summernoteData = {};
		$("textarea.summernote").each(function () {
			let textareaName = $(this).attr("name");
			summernoteData[textareaName] = $(this).summernote("code");
		});

		// Combine Summernote data with form data
		let formData = $(this).serializeArray();
		$.each(summernoteData, function (name, value) {
			formData.push({
				name: name,
				value: value
			});
		});

		$.ajax({
			type: "POST",
			url: $(this).attr("action"),
			data: formData,
			success: function (response) {
				console.log("Form submitted successfully:", response);
				// Handle successful response
			},
			error: function (xhr, status, error) {
				console.error("Error submitting form:", error);
				// Handle error response
			},
		});
	});

	// Reset modal content when closed
	$("#modalAddOutput").on("hidden.bs.modal", function () {
		$(this).find("form")[0].reset();
		$("#tbody-no-activity").removeClass("hide").html(`
            <tr>
              <td colspan="4" class="text-center" id="no-activity">
                {{ __('cruds.activity.no_selected') }}
              </td>
            </tr>
        `);
		$("#activity_output_list").find("tbody.data-activity").remove();

		// Reset Summernote content
		$("textarea.summernote").summernote("reset");
	});
});

// store data without summernote

$(document).ready(function() {
    $('#formAddOutput').submit(function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Collect the main form data
        let formData = $(this).serializeArray();

        // Remove deskripsi[], indikator[], and target[] from formData
        formData = formData.filter(item => !["deskripsi[]", "indikator[]", "target[]"].includes(item.name));

        // Collect output activity data
        let activityData = [];
        $('#activity_output_list tbody.data-activity').each(function() {
            let activity = {};
            activity.deskripsi = $(this).find('textarea[name="deskripsi[]"]').val();
            activity.indikator = $(this).find('textarea[name="indikator[]"]').val();
            activity.target = $(this).find('textarea[name="target[]"]').val();
            activityData.push(activity);
        });

        // Append activity data to form data
        formData.push({ name: 'activities', value: JSON.stringify(activityData) });

        // Log the final payload
        console.log(formData); // Debug log to check data

        // Send the data via AJAX
        $.ajax({
            type: 'POST',
            url: 'your-server-endpoint', // Replace with your server endpoint
            data: formData,
            success: function(response) {
                console.log('Form submitted successfully:', response);
                // Handle successful response
                // Reset the form and modal if needed
                $('#formAddOutput')[0].reset();
                $('#tbody-no-activity').removeClass('hide').html(`
                    <tr>
                      <td colspan="4" class="text-center" id="no-activity">
                        {{ __('cruds.activity.no_selected') }}
                      </td>
                    </tr>
                `);
                $('#activity_output_list').find('tbody.data-activity').remove();
                $('#modalAddOutput').modal('hide');
            },
            error: function(xhr, status, error) {
                console.error('Error submitting form:', error);
                // Handle error response
            }
        });
    });

    // Event delegation to handle removing activity rows
    $('#activity_output_list').on('click', '.remove-activity', function(e) {
        e.preventDefault();
        var activityId = $(this).closest('tbody').data('body-id');
        console.log('Removing tbody with id:', activityId); // Debug log
        $(`#has-activity-${activityId}`).remove();

        // Check if there are no more activity rows and show the no-activity message
        if ($('#activity_output_list tbody.data-activity').length === 0) { 
            $('#tbody-no-activity').removeClass('hide').html(`
                <tr>
                  <td colspan="4" class="text-center" id="no-activity">
                    {{ __('cruds.activity.no_selected') }}
                  </td>
                </tr>
            `);
        }
    });

    // Reset modal content when closed
    $('#modalAddOutput').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        $('#tbody-no-activity').removeClass('hide').html(`
            <tr>
              <td colspan="4" class="text-center" id="no-activity">
                {{ __('cruds.activity.no_selected') }}
              </td>
            </tr>
        `);
        $('#activity_output_list').find('tbody.data-activity').remove();
    });
});

</script>