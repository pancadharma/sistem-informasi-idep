<script>
	let messages = @json($message ?? session('message') ?? []);
	$doc.ready(function () {
		$(Object.keys(messages)).each(function(index, icon) {
			let $messages_html = $("<div>").addClass("row");

			$(messages[icon]).each(function(index, message_list) {
				let $column	= $("<div>").addClass("col");

				$(Object.keys(message_list)).each(function(index, attribute){
					let texts	= message_list[attribute],
						$ul		= $("<ul>").addClass("list-group");

					$(texts).each(function(index, text) {
						$ul.append($("<li>").addClass("list-group-item bg-transparent p-0 border-0").text(text));
					});
					$column.append($ul);
				});

				$messages_html.append($column);
			});

			Toast.fire({
				icon: icon,
				html: $messages_html.html(),
				position: 'top',
				iconColor: 'white',
				timer: 5000,
				timerProgressBar: true,
				showConfirmButton: false,
				timerProgressBar: true,
				customClass: {
					popup: 'colored-toast',
				},
				didOpen: function(toast) {
					toast.addEventListener('mouseenter', Swal.stopTimer);
					toast.addEventListener('mouseleave', Swal.resumeTimer);
				},
			});
		});
	});
</script>
