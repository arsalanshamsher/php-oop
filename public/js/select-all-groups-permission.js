document.addEventListener('DOMContentLoaded', function() {
    // Select All functionality for permission groups
    const groupHeaders = document.querySelectorAll('.card-header .form-check-input');

    groupHeaders.forEach(headerCheckbox => {
        headerCheckbox.addEventListener('change', function() {
            const cardBody = this.closest('.card').querySelector('.card-body');
            const checkboxes = cardBody.querySelectorAll('.form-check-input');

            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    });

    // Update header checkbox state based on individual checkboxes
    const permissionCheckboxes = document.querySelectorAll('.card-body .form-check-input');

    permissionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const cardBody = this.closest('.card-body');
            const headerCheckbox = cardBody.closest('.card').querySelector('.card-header .form-check-input');
            const allCheckboxes = cardBody.querySelectorAll('.form-check-input');
            const checkedCount = cardBody.querySelectorAll('.form-check-input:checked').length;

            if (checkedCount === allCheckboxes.length) {
                headerCheckbox.checked = true;
                headerCheckbox.indeterminate = false;
            } else if (checkedCount === 0) {
                headerCheckbox.checked = false;
                headerCheckbox.indeterminate = false;
            } else {
                headerCheckbox.indeterminate = true;
            }
        });
    });
});