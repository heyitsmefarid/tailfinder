console.log("JS loaded");
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

    const contactInput = formSteps[currentStep].querySelector('input[name="contact_number"]');
    if (contactInput) {
      const contactValue = contactInput.value.trim();
      const phPattern = /^09\d{9}$/;

      if (!phPattern.test(contactValue)) {
        Swal.fire({
          icon: "error",
          title: "Invalid Contact Number",
          text: "Please enter a valid Philippine mobile number (must start with 09 and be 11 digits long).",
          confirmButtonColor: "#3085d6"
        });
        contactInput.style.border = "2px solid red";
        return; 
      } else {
        contactInput.style.border = "";
      }
    }

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

form.addEventListener('submit', async (e) => {
  e.preventDefault();

  const formData = new FormData(form);
  const password = document.getElementById('password').value;
  const confirmPassword = document.getElementById('confirm_password').value;

  if (password !== confirmPassword) {
    Swal.fire({
      icon: "error",
      title: "Passwords do not match",
      text: "Please ensure both passwords are the same."
    });
    return;
  }

  try {
    const response = await fetch('../register.php', {
      method: 'POST',
      body: formData
    });

    const data = await response.json();
    console.log("Signup response:", data);

    if (data.status === 'exists') {
      Swal.fire({
        icon: "warning",
        title: "Email Already Registered",
        text: "This email already exists. Please log in instead.",
        confirmButtonText: "Go to Login",
        confirmButtonColor: "#3085d6"
      }).then(() => {
        window.location.href = "index.php";
      });

    } else if (data.status === 'success') {
      Swal.fire({
        icon: "success",
        title: "Verification Required",
        text: "A verification code has been sent to your email.",
        timer: 2500,
        showConfirmButton: false,
        willClose: () => {
          window.location.href = "../verify.html";
        }
      });

    } else if (data.status === 'error') {
      Swal.fire({
        icon: "error",
        title: "Something went wrong",
        text: data.message || "Please try again later."
      });
    }

  } catch (err) {
    console.error("Signup Error:", err);
    Swal.fire({
      icon: "error",
      title: "Server Error",
      text: "Unable to connect to the server."
    });
  }
});


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
