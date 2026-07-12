
const modeToggleBtn = document.getElementById('modeToggleBtn');
const registrationForm = document.getElementById('registrationForm');
const fullName = document.getElementById('fullName');
const emailAddr = document.getElementById('emailAddr');
const phoneNumber = document.getElementById('phoneNumber');
const deptSelect = document.getElementById('deptSelect');
const passField = document.getElementById('passField');
const confirmPassField = document.getElementById('confirmPassField');
const nameError = document.getElementById('nameError');
const emailError = document.getElementById('emailError');
const phoneError = document.getElementById('phoneError');
const genderError = document.getElementById('genderError');
const deptError = document.getElementById('deptError');
const passError = document.getElementById('passError');
const confirmPassError = document.getElementById('confirmPassError');


modeToggleBtn.addEventListener('click', function() {
    document.body.classList.toggle('dark');
    
    if (document.body.classList.contains('dark')) {
        modeToggleBtn.innerText = "Switch to Light Mode";
    } else {
        modeToggleBtn.innerText = "Switch to Dark Mode";
    }
});


registrationForm.addEventListener('submit', function(event) {
    event.preventDefault();

    nameError.innerText = "";
    emailError.innerText = "";
    phoneError.innerText = "";
    genderError.innerText = "";
    deptError.innerText = "";
    passError.innerText = "";
    confirmPassError.innerText = "";

    let isFormValid = true;

    if (fullName.value.trim() === "") {
        nameError.innerText = "This field is required. Please fill it before submit.";
        isFormValid = false;
    }

    if (emailAddr.value.trim() === "") {
        emailError.innerText = "This field is required. Please fill it before submit.";
        isFormValid = false;
    } else if (!emailAddr.value.includes('@')) {
        emailError.innerText = "Invalid format: Email must contain an '@' symbol.";
        isFormValid = false;
    }

    if (phoneNumber.value.trim() === "") {
        phoneError.innerText = "This field is required. Please fill it before submit.";
        isFormValid = false;
    }

    const genderSelection = document.querySelector('input[name="userGender"]:checked');
    if (!genderSelection) {
        genderError.innerText = "Please select an option before submit.";
        isFormValid = false;
    }

    if (deptSelect.value === "") {
        deptError.innerText = "Please select your department branch before submit.";
        isFormValid = false;
    }

    if (passField.value === "") {
        passError.innerText = "This field is required. Please fill it before submit.";
        isFormValid = false;
    }

    if (confirmPassField.value === "") {
        confirmPassError.innerText = "This field is required. Please fill it before submit.";
        isFormValid = false;
    } else if (passField.value !== confirmPassField.value) {
        confirmPassError.innerText = "Validation Warning: Passwords do not match.";
        isFormValid = false;
    }
    if (isFormValid) {
        alert("Registration successfully processed without errors!");
        registrationForm.reset();
    }
});