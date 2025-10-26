console.log("JS loaded"); // Debug

// ---------------------- MULTI-STEP SIGNUP ----------------------
const form = document.getElementById('a-form');
const nextBtns = document.querySelectorAll('.next-step');
const prevBtns = document.querySelectorAll('.prev-step');
const formSteps = document.querySelectorAll('.form-step');
let currentStep = 0;

nextBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    const inputs = formSteps[currentStep].querySelectorAll('input[required]');
    let allFilled = true;
    inputs.forEach(input => {
      if (!input.value.trim()) {
        allFilled = false;
        input.style.border = "2px solid red";
        if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('error-message')) {
          const error = document.createElement('span');
          error.textContent = 'This field is required';
          error.classList.add('error-message');
          input.after(error);
        }
      } else {
        input.style.border = "";
        if (input.nextElementSibling && input.nextElementSibling.classList.contains('error-message')) {
          input.nextElementSibling.remove();
        }
      }
    });
    if (allFilled) {
      formSteps[currentStep].classList.remove('form-step-active');
      currentStep++;
      formSteps[currentStep].classList.add('form-step-active');
    }
  });
});

prevBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    formSteps[currentStep].classList.remove('form-step-active');
    currentStep--;
    formSteps[currentStep].classList.add('form-step-active');
  });
});

// ---------------------- SIGNUP PASSWORD VALIDATION ----------------------
form.addEventListener('submit', (e) => {
  const password = document.getElementById('password').value;
  const confirmPassword = document.getElementById('confirm_password').value;
  if (password !== confirmPassword) {
    e.preventDefault();
    Swal.fire({
      icon: "error",
      title: "Passwords do not match",
      text: "Please ensure both passwords are the same."
    });
  }
});

// ---------------------- SIGN IN / SIGN UP SWITCH ----------------------
const switchCtn = document.querySelector("#switch-cnt");
const switchC1 = document.querySelector("#switch-c1");
const switchC2 = document.querySelector("#switch-c2");
const aContainer = document.querySelector("#a-container");
const bContainer = document.querySelector("#b-container");
const switchBtn = document.querySelectorAll(".switch__button");
let isAContainerVisible = true;

switchBtn.forEach(btn => {
  btn.addEventListener("click", () => {
    aContainer.classList.toggle("slide-left");
    bContainer.classList.toggle("slide-right");
    setTimeout(() => {
      if (isAContainerVisible) {
        aContainer.classList.add("is-hidden");
        bContainer.classList.remove("is-hidden");
      } else {
        bContainer.classList.add("is-hidden");
        aContainer.classList.remove("is-hidden");
      }
      isAContainerVisible = !isAContainerVisible;
      switchC1.classList.toggle("is-hidden");
      switchC2.classList.toggle("is-hidden");
    }, 600);
  });
});

// ---------------------- LOGIN ERROR / SUCCESS SWEETALERT ----------------------
document.addEventListener('DOMContentLoaded', () => {
  const body = document.body;
  const loginError = body.dataset.loginError;
  const loginSuccess = body.dataset.loginSuccess;
  const role = body.dataset.role;

  if (loginError) {
    let title, text;
    if (loginError === "wrongpass") {
      title = "Incorrect Password";
      text = "The password you entered is wrong. Please try again.";
    } else if (loginError === "notfound") {
      title = "Account Not Found";
      text = "No account with this email exists. Please create an account.";
    }
    Swal.fire({
      icon: "error",
      title: title,
      text: text,
      showClass: { popup: 'animate__animated animate__fadeInDown' },
      hideClass: { popup: 'animate__animated animate__fadeOutUp' }
    });
  }

  if (loginSuccess === "1") {
    Swal.fire({
      title: 'Welcome Back!',
      html: `<div class="loader"></div> Redirecting...`,
      timer: 2000,
      timerProgressBar: true,
      showConfirmButton: false,
      didOpen: () => Swal.showLoading(),
      willClose: () => {
        if (role === "Admin") {
          window.location.href = "admin/index.php";
        } else {
          window.location.href = "adopter/home.php";
        }
      }
    });
  }
});
