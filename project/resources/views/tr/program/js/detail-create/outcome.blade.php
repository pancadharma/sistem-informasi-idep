<script>
// $(document).ready(function() {
//     var outcomeIndex = 0; // Initialize your outcomeIndex

//     $("#outcomeContainer").on("click", ".addOutcome", function() {
//         outcomeIndex++;

//         var $template = $("#outcomeTemplate");
//         var $clone = $template
//             .clone()
//             .removeClass("d-none")
//             .removeAttr("id")
//             .attr("data-outcome-index", outcomeIndex)
//             .insertBefore($template);

//         // Update the name attributes
//         $clone
//             .find('[name="outcome_id[]"]').attr("name", "outcome[" + outcomeIndex + "].id").end()
//             .find('[name="program_id[]"]').attr("name", "outcome[" + outcomeIndex + "].program_id").end()
//             .find('[name="deskripsi[]"]').attr("name", "outcome[" + outcomeIndex + "].deskripsi").end()
//             .find('[name="target[]"]').attr("name", "outcome[" + outcomeIndex + "].target").end()
//             .find('[name="indikator[]"]').attr("name", "outcome[" + outcomeIndex + "].indikator").end()
//             .find('[name="nilai_outcome[]"]').attr("name", "outcome[" + outcomeIndex + "].nilai_outcome").end();
//     });
// });

// $(document).ready(function() {
//     var outcomeIndex = 0; // Make sure to initialize this variable

//     $("#outcome_data").on("click", ".addOutcome", function() {
//         outcomeIndex++;

//         var $template = $("#outcomeTemplate"),
//             $clone = $template.clone().removeClass("d-none").attr("id", "outcomeTemplate",outcomeIndex).attr("data-outcome-index", outcomeIndex).insertBefore($template);

//         // Update the name attributes
//         $clone.find('[name="deskripsi[]"]').attr("name", "deskripsi[" + outcomeIndex + "]").end()
//               .find('[name="target[]"]').attr("name", "target[" + outcomeIndex + "]").end()
//               .find('[name="indikator[]"]').attr("name", "indikator[" + outcomeIndex + "]").end()
//               .find('[name="nilai_outcome[]"]').attr("name", "nilai_outcome[" + outcomeIndex + "]").end();
//     }).on("click", ".delOutcome", function() {
//         var $row = $(this).closest(".form-group"),
//             index = $row.attr("data-outcome-index");

//         // Remove the row
//         $row.remove();
//     });
// });
$(document).ready(function () {
  var outcomeIndex = 0; // Ensure this is initialized

  $("#outcomeContainer").on("click", ".addOutcome", function () {
    outcomeIndex++;

    var $template = $("#outcomeTemplate"),
      $clone = $template
        .clone()
        .removeClass("d-none")
        .removeAttr("id")
        .attr("id", "outcomeTemplate_" + outcomeIndex)
        .attr("data-outcome-index", outcomeIndex)
        .insertBefore($template);

    // Update the name and ID attributes
    $clone
      .find('[name="deskripsi[]"]')
      .attr("name", "deskripsi[" + outcomeIndex + "]")
      .attr("id", "deskripsi_" + outcomeIndex)
      .end()
      .find('[name="target[]"]')
      .attr("name", "target[" + outcomeIndex + "]")
      .attr("id", "target_" + outcomeIndex)
      .end()
      .find('[name="indikator[]"]')
      .attr("name", "indikator[" + outcomeIndex + "]")
      .attr("id", "indikator_" + outcomeIndex)
      .end()
      .find('[name="nilai_outcome[]"]')
      .attr("name", "nilai_outcome[" + outcomeIndex + "]")
      .attr("id", "nilai_outcome_" + outcomeIndex)
      .end();
  });

  // Remove button click handler
  $(document).on("click", ".removeButton", function () {
    var $row = $(this).closest(".row"),
      index = $row.attr("data-outcome-index");

    // Remove the row
    $row.remove();
  });
});

</script>
