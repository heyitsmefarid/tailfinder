lucide.createIcons();

// IMAGE MODAL SCRIPT
const modal = document.getElementById('imgModal');
const modalImg = document.getElementById('modalImg');
const closeModalBtn = document.getElementById('closeModal');
const petImages = Array.from(document.querySelectorAll('.pet-img'));
const prevBtn = modal.querySelector('.prev');
const nextBtn = modal.querySelector('.next');
let currentIndex = 0;

petImages.forEach((img, index) => {
  img.addEventListener('click', () => {
    currentIndex = index;
    modal.classList.remove('hidden');
    setTimeout(() => modal.classList.add('show'), 10);
    modalImg.src = img.dataset.src;
  });
});

function closeModalFunc() {
  modal.classList.remove('show');
  setTimeout(() => {
    modal.classList.add('hidden');
    modalImg.src = '';
  }, 300);
}

closeModalBtn.addEventListener('click', closeModalFunc);
modal.addEventListener('click', e => { if(e.target === modal) closeModalFunc(); });
prevBtn.addEventListener('click', e => { e.stopPropagation(); currentIndex = (currentIndex - 1 + petImages.length) % petImages.length; modalImg.src = petImages[currentIndex].dataset.src; });
nextBtn.addEventListener('click', e => { e.stopPropagation(); currentIndex = (currentIndex + 1) % petImages.length; modalImg.src = petImages[currentIndex].dataset.src; });
document.addEventListener('keydown', e => { if(!modal.classList.contains('show')) return; if(e.key === 'ArrowLeft') prevBtn.click(); if(e.key === 'ArrowRight') nextBtn.click(); if(e.key === 'Escape') closeModalFunc(); });

// ADOPTION MODAL SCRIPT
const adoptBtns = document.querySelectorAll('.adopt-btn');
const adoptModal = document.getElementById('adoptModal');
const adoptModalContent = document.getElementById('adoptModalContent');
const closeAdoptModal = document.getElementById('closeAdoptModal');
const cancelAdoptBtn = document.getElementById('cancelAdoptBtn');

const modalPetImg = document.getElementById('modalPetImg');
const modalPetName = document.getElementById('modalPetName');
const modalPetType = document.getElementById('modalPetType');
const modalPetBreed = document.getElementById('modalPetBreed');
const modalPetAge = document.getElementById('modalPetAge');
const formPetId = document.getElementById('formPetId');
const adoptForm = document.getElementById('adoptForm');

adoptBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        const pet = JSON.parse(btn.dataset.pet);
        modalPetImg.src = "../uploads/" + pet.image;
        modalPetName.textContent = pet.pet_name;
        modalPetType.textContent = pet.type;
        modalPetBreed.textContent = pet.breed;
        modalPetAge.textContent = pet.age;
        formPetId.value = pet.pet_id;

        adoptModal.classList.remove('hidden');
        setTimeout(() => {
            adoptModal.classList.add('flex');
            adoptModalContent.classList.remove('-translate-y-12','scale-95','opacity-0');
        }, 10);
    });
});

function closeAdoptModalFunc() {
    adoptModalContent.classList.add('-translate-y-12','scale-95','opacity-0');
    setTimeout(() => adoptModal.classList.add('hidden'), 300);
}

closeAdoptModal.addEventListener('click', closeAdoptModalFunc);
cancelAdoptBtn.addEventListener('click', closeAdoptModalFunc);
adoptModal.addEventListener('click', e => { if(e.target===adoptModal) closeAdoptModalFunc(); });

// AJAX form submit for adoption request
adoptForm.addEventListener('submit', function(e){
    e.preventDefault();
    const petId = formPetId.value;

    fetch('request_adoption.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `pet_id=${encodeURIComponent(petId)}`
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success'){
            Swal.fire({ icon: 'success', title: 'Request Sent!', text: data.message, confirmButtonText: 'OK' });
            closeAdoptModalFunc();
        } else {
            Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonText: 'OK' });
        }
    })
    .catch(err => {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Try again.', confirmButtonText: 'OK' });
    });
});
