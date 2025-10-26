document.addEventListener('DOMContentLoaded', () => {
    // SweetAlert on message sent
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('sent') === '1'){
        Swal.fire({
            icon: 'success',
            title: 'Message Sent!',
            text: 'Your message has been sent to the admin.',
            timer: 2500,
            showConfirmButton: false
        });
    }

    // Form validation
    const messageForm = document.getElementById('messageForm');
    messageForm.addEventListener('submit', (e) => {
        const message = document.getElementById('message_content').value.trim();
        if(message === ''){
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please write a message before sending!',
            });
        }
    });
});
