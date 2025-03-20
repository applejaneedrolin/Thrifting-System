function togglePasswordBtnOnSignIn() {
    const signInPasswordField = document.getElementById("sign-in-password");
    const toggleBtnIcon = document.querySelector("button[onclick='togglePasswordBtnOnSignIn()'] i");

    if (signInPasswordField.type === "password") {
        signInPasswordField.type = "text";
        toggleBtnIcon.classList.remove("fa-eye");
        toggleBtnIcon.classList.add("fa-eye-slash");
    } else {
        signInPasswordField.type = "password";
        toggleBtnIcon.classList.remove("fa-eye-slash");
        toggleBtnIcon.classList.add("fa-eye");
    }
}
function togglePasswordBtnOnSignUp() {
    const signUpPasswordField = document.getElementById("sign-up-password");
    const toggleBtnIcon = document.querySelector("button[onclick='togglePasswordBtnOnSignUp()'] i");

    if (signUpPasswordField.type === "password") {
        signUpPasswordField.type = "text";
        toggleBtnIcon.classList.remove("fa-eye");
        toggleBtnIcon.classList.add("fa-eye-slash");
    } else {
        signUpPasswordField.type = "password";
        toggleBtnIcon.classList.remove("fa-eye-slash");
        toggleBtnIcon.classList.add("fa-eye");
    }
}
function togglePasswordBtnOnProfile() {
    const signUpPasswordField = document.getElementById("profile-password");
    const toggleBtnIcon = document.querySelector("button[onclick='togglePasswordBtnOnProfile()'] i");

    if (signUpPasswordField.type === "password") {
        signUpPasswordField.type = "text";
        toggleBtnIcon.classList.remove("fa-eye");
        toggleBtnIcon.classList.add("fa-eye-slash");
    } else {
        signUpPasswordField.type = "password";
        toggleBtnIcon.classList.remove("fa-eye-slash");
        toggleBtnIcon.classList.add("fa-eye");
    }
}