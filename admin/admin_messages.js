// Initialize datatables
$(document).ready(function() {
    $('#datatablesSimple').DataTable();
});

// SweetAlert for response success
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('responded') === '1'){
        Swal.fire({
            icon: 'success',
            title: 'Response Sent!',
            text: 'Your reply has been sent to the user.',
            timer: 2500,
            showConfirmButton: false
        });
    }
});
