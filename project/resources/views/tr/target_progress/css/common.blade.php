<style>
	.card-header.border-bottom-0.card-header.p-0.pt-1.navigasi {
		position: sticky;
		z-index: 100;
		top: 0;
	}
	.mw-100-px{
		min-width: 100px!important;
	}
	.mw-200-px{
		min-width: 200px!important;
	}
	.mw-250-px{
		min-width: 250px!important;
	}
	.mw-300-px{
		min-width: 300px!important;
	}
	.input-group{
		flex-wrap: nowrap;
	}
	.card-body .bg-25-warning{
		background-color: #fff1d0 !important;
	}
	.card-body .bg-25-success{
		background-color: #d8ead2 !important;
	}
	
	.target-progress-status, .target-progress-risk{
		background-color: #e9eaed;
		width: 100%;
		position: relative;
		display: block;
		white-space: nowrap;
		padding: 2px 6px;
		border-radius: 4px;
	}
	.target-progress-status.selected,
	.target-progress-risk.selected{
		padding-top: 0;
		padding-bottom: 0;
		font-size: inherit;
		line-height: inherit;
		z-index: 0;
	}
	.select2-selection__clear{
		z-index: 1;
	}
	
	.target-progress-status.opt-unset,
	.target-progress-risk.opt-unset{
		background-color: var(--light);
		color: var(--gray);
	}
	.target-progress-status.opt-to_be_conducted{
		background-color: #743802;
		color: #FFF;
	}
	.target-progress-status.opt-completed{
		background-color: #0f744b;
		color: #FFF;
	}
	.target-progress-status.opt-ongoing{
		background-color: #ffe59f;
		color: inherit;
	}
	
	.target-progress-risk.opt-none{
		background-color: #c2e1f2;
		color: #537ab8;
	}
	.target-progress-risk.opt-medium{
		background-color: #ffe59f;
		color: inherit;
	}
	.target-progress-risk.opt-high{
		background-color: #b00202;
		color: #FFF;
	}
	
	/* Data Table */
	div.dt-scroll-body {
		border-bottom-color: unset;
		border-bottom-width: unset;
		border-bottom-style: unset;
	}

	/* Toast */
	.colored-toast.swal2-icon-success {
		background-color: var(--success) !important;
	}

	.colored-toast.swal2-icon-error {
		background-color: var(--danger) !important;
	}

	.colored-toast.swal2-icon-warning {
		background-color: var(--warning) !important;
	}

	.colored-toast.swal2-icon-info {
		background-color: var(--primary) !important;
	}

	.colored-toast.swal2-icon-question {
		background-color: var(--info) !important;
	}

	.colored-toast .swal2-title {
		color: white;
	}

	.colored-toast .swal2-close {
		color: white;
	}

	.colored-toast .swal2-html-container {
		color: white;
	}
</style>
