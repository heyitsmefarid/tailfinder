<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
<link rel="stylesheet" href="style1.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<title>Pet Adoption Portal</title>
</head>
<body 
    data-login-error="<?= $_SESSION['login_error'] ?? '' ?>"
    data-login-success="<?= $_SESSION['login_success'] ?? '' ?>"
    data-role="<?= $_SESSION['role'] ?? '' ?>"
>
<?php
// Clear session variables after passing to JS
unset($_SESSION['login_error'], $_SESSION['login_success']);
?>
<div class="main">

    <!-- SIGN UP FORM -->
    <div class="container a-container" id="a-container">
        <form id="a-form" class="form" method="POST" action="../register.php">
            <h2 class="form_title title">Create Pet Adoption Account</h2>

            <!-- Step 1 -->
            <div class="form-step form-step-active">
                <input class="form__input" type="text" name="last_name" placeholder="Last Name" required>
                <input class="form__input" type="text" name="first_name" placeholder="First Name" required>
                <input class="form__input" type="text" name="middle_initial" placeholder="Middle Initial (Optional)">
                <input class="form__input" type="text" name="contact_number" placeholder="Contact Number" required>
                <button type="button" class="form__button button next-step">Next</button>
            </div>

            <!-- Step 2 -->
            <div class="form-step">
                <input class="form__input" type="text" name="street" placeholder="Street" required>
                <input class="form__input" type="text" name="barangay" placeholder="Barangay" required>
                <input class="form__input" type="text" name="city" placeholder="City" required>
                <input class="form__input" type="text" name="province" placeholder="Province" required>
                <div style="display:flex; gap:10px;">
                    <button type="button" class="form__button button prev-step">Previous</button>
                    <button type="button" class="form__button button next-step">Next</button>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="form-step">
                <input class="form__input" type="email" name="email" placeholder="Email" required>
                <input class="form__input" type="password" name="password" id="password" placeholder="Password" required>
                <input class="form__input" type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
                <div style="display:flex; gap:10px;">
                    <button type="button" class="form__button button prev-step">Previous</button>
                    <button class="form__button button submit" type="submit">SIGN UP</button>
                </div>
            </div>
        </form>
    </div>

    <!-- SIGN IN FORM -->
    <div class="container b-container" id="b-container">
        <form id="b-form" class="form" method="POST" action="../login.php">
            <h2 class="form_title title">Sign in to Adopt Pets</h2>
            <input class="form__input" type="email" name="email" placeholder="Email" required>
            <input class="form__input" type="password" name="password" placeholder="Password" required>
            <button class="form__button button submit" type="submit">SIGN IN</button>
        </form>
    </div>

    <!-- SWITCH PANEL -->
    <div class="switch" id="switch-cnt">
        <div class="switch__circle"></div>
        <div class="switch__circle switch__circle--t"></div>

        <div class="switch__container" id="switch-c1">
            <h2 class="switch__title title">Welcome Back!</h2>
            <p class="switch__description description">To keep connected with us, please login.</p>
            <button class="switch__button button switch-btn">SIGN IN</button>
        </div>

        <div class="switch__container is-hidden" id="switch-c2">
            <h2 class="switch__title title">Hello Friend!</h2>
            <p class="switch__description description">
                Enter your personal details and start your journey to adopt a pet
            </p>
            <button class="switch__button button switch-btn">SIGN UP</button>
        </div>
    </div>

</div>

<script src="main.js"></script>
</body>
</html>
