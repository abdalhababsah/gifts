/**
 * Status Toggle Functionality with SweetAlert2
 */

// Function to confirm status toggle
function confirmToggleStatus(button) {
    // Get data attributes
    const id = button.getAttribute('data-id');
    const name = button.getAttribute('data-name');
    const url = button.getAttribute('data-url');
    const currentStatus = button.getAttribute('data-status') === '1';
    const newStatus = !currentStatus;
    const actionText = currentStatus ? 'deactivate' : 'activate';
    const statusText = currentStatus ? 'inactive' : 'active';
    const iconType = currentStatus ? 'warning' : 'question';
    const iconColor = currentStatus ? '#f8bb86' : '#87adbd';

    // Show SweetAlert confirmation
    Swal.fire({
        title: `${actionText.charAt(0).toUpperCase() + actionText.slice(1)} ${name}?`,
        text: `Are you sure you want to make this item ${statusText}?`,
        icon: iconType,
        iconColor: iconColor,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, ' + actionText + ' it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create form and submit it
            submitToggleStatusForm(url, id);
        }
    });
}

// Function to submit the form
function submitToggleStatusForm(url, id) {
    // Create a form
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;
    form.style.display = 'none';
    
    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    
    // Add method PATCH
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'PATCH';
    
    // Append inputs to form
    form.appendChild(csrfInput);
    form.appendChild(methodInput);
    
    // Append form to body and submit
    document.body.appendChild(form);
    form.submit();
}
