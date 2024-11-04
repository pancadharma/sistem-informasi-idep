<div class="col-md-12" id="donorContainer">
    <div class="row">
        <div class="col-lg-4 form-group">
            <div class="input-group">
                <label for="donor_name" class="input-group small mb-0">Donor Name</label>
                <input type="text" id="donor_name[]" name="donor_name[]" class="form-control" placeholder="Donor Name">
            </div>
        </div>
        <div class="col-lg-4 form-group">
            <div class="input-group">
                <label for="donation_value" class="input-group small mb-0">Donation Value</label>
                <input type="text" id="donation_value[]" name="donation_value[]" class="form-control"
                    placeholder="Donation Value">
            </div>
        </div>
        <div class="col-lg-4 form-group">
            <div class="input-group">
                <label for="donor_email" class="input-group small mb-0">Donor Email</label>
                <input type="email" id="donor_email[]" name="donor_email[]" class="form-control"
                    placeholder="Donor Email">
                <span class="input-group-append">
                    <button type="button" class="ml-2 btn btn-success form-control addDonor btn-flat"><i
                            class="bi bi-plus"></i></button>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row d-none" id="donorTemplate">
    <div class="col-lg-4 form-group">
        <div class="input-group">
            <label for="donor_name" class="input-group small mb-0">Donor Name</label>
            <input type="text" name="donor_name[]" class="form-control" placeholder="Donor Name">
        </div>
    </div>
    <div class="col-lg-4 form-group">
        <div class="input-group">
            <label for="donation_value" class="input-group small mb-0">Donation Value</label>
            <input type="text" name="donation_value[]" class="form-control" placeholder="Donation Value">
        </div>
    </div>
    <div class="col-lg-4 form-group">
        <div class="input-group">
            <label for="donor_email" class="input-group small mb-0">Donor Email</label>
            <input type="email" name="donor_email[]" class="form-control" placeholder="Donor Email">
            <span class="input-group-append">
                <button type="button" class="ml-2 btn btn-danger form-control removeButton btn-flat"><i
                        class="bi bi-trash"></i></button>
            </span>
        </div>
    </div>
</div>

{{--


public function store(Request $request)
{
    // Create the program
    $program = Program::create($request->only(['program_name']));

    // Store the donors
    foreach ($request->input('donor_name') as $index => $donorName) {
        Donor::create([
            'program_id' => $program->id,
            'name' => $donorName,
            'donation_value' => $request->input("donation_value.$index"),
            'email' => $request->input("donor_email.$index"),
        ]);
    }

    return response()->json(['program_id' => $program->id, 'message' => 'Program and donors saved successfully!']);
}



--}}


@push('js')
    <script>
        $(document).ready(function() {
            let donorIndex = 0;

            // Add Donor button click handler
            $("#donorContainer").on("click", ".addDonor", function() {
                donorIndex++;

                // Clone the template
                const $clone = $("#donorTemplate").clone().removeClass("d-none").removeAttr("id").attr(
                    "data-donor-index", donorIndex);

                // Insert the cloned template
                $("#donorContainer").append($clone);

                // Update names with proper indexing
                $clone.find("input").each(function() {
                    const $input = $(this);
                    const name = $input.attr("name").replace("[]", "[" + donorIndex + "]");
                    $input.attr("name", name);
                });

                // Clear any values that might have been cloned
                $clone.find("input").val("");
            });

            // Remove button click handler - using event delegation
            $(document).on("click", ".removeButton", function() {
                $(this).closest(".row").remove();
            });

            // Handle form submission
            $('#programForm').on('submit', function(e) {
                e.preventDefault();

                // Create a new FormData object
                const formData = new FormData(this);

                // Send the form data via AJAX
                $.ajax({
                    url: "{{ route('programs.store') }}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert('Program and donors saved successfully!');
                    },
                    error: function(xhr) {
                        alert('Failed to save program');
                    }
                });
            });
        });
    </script>
@endpush
