// Confirm before canceling adoption request
document.querySelectorAll('.cancel-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        const form = btn.closest('form');

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to cancel this adoption request?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, cancel it!',
            cancelButtonText: 'No'
        }).then((result) => {
            if(result.isConfirmed){
                form.submit(); // submit the form if confirmed
            }
        });
    });
});
