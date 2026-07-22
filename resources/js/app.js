import 'toastr/build/toastr.min.css';
import toastr from 'toastr';
import Alpine from 'alpinejs';

window.toastr = toastr;

window.Alpine = Alpine;
Alpine.start();

toastr.options = {
	closeButton: true,
	progressBar: true,
	positionClass: 'toast-top-right',
	timeOut: 3500,
	extendedTimeOut: 1200,
	preventDuplicates: true,
	newestOnTop: true,
};

document.addEventListener('DOMContentLoaded', () => {
	const body = document.body;

	const statusMessage = body.dataset.toastStatus;
	const successMessage = body.dataset.toastSuccess;
	const errorMessage = body.dataset.toastError;
	const validationErrors = body.dataset.toastErrors ? JSON.parse(body.dataset.toastErrors) : [];

	if (statusMessage) {
		toastr.success(statusMessage);
	}

	if (successMessage) {
		toastr.success(successMessage);
	}

	if (errorMessage) {
		toastr.error(errorMessage);
	}

	if (Array.isArray(validationErrors) && validationErrors.length > 0) {
		toastr.error(validationErrors.join('<br>'));
	}

	document.querySelectorAll('form').forEach((form) => {
		form.addEventListener('submit', () => {
			if (form.dataset.toastSubmit === 'false') {
				return;
			}

			const submitLabel = form.dataset.toastSubmitting || 'Submitting...';
			toastr.info(submitLabel);
		});
	});
});
