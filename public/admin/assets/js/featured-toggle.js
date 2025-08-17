/**
 * Featured Toggle Functionality with SweetAlert2
 */

// Function to confirm featured toggle
function confirmToggleFeatured(button) {
    // Get data attributes
    const id = button.getAttribute('data-id');
    const name = button.getAttribute('data-name');
    const url = button.getAttribute('data-url');
    const isFeatured = button.getAttribute('data-featured') === '1';
    const actionText = isFeatured ? 'remove from featured' : 'add to featured';
    const statusText = isFeatured ? 'not featured' : 'featured';
    const iconType = isFeatured ? 'warning' : 'question';
    const iconColor = isFeatured ? '#f8bb86' : '#f8d486';

    // Show SweetAlert confirmation
    Swal.fire({
        title: `${isFeatured ? 'Remove from' : 'Add to'} featured products?`,
        text: `Are you sure you want to make "${name}" ${statusText}?`,
        icon: iconType,
        iconColor: iconColor,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `Yes, ${actionText}!`,
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create form and submit it
            submitToggleFeaturedForm(url);
        }
    });
}

// Function to submit the form
function submitToggleFeaturedForm(url) {
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
