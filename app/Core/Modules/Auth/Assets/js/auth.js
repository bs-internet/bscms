/**
 * Authentication Scripts
 */
document.addEventListener('DOMContentLoaded', function () {

    // Password Toggle
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');

    togglePasswordButtons.forEach(button => {
        button.addEventListener('click', function () {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Loading State on Form Submit
    const loginForm = document.querySelector('form[action="/admin/login"]');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            const submitButton = document.getElementById('loginButton');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.querySelector('.btn-text').style.display = 'none';
                submitButton.querySelector('.btn-spinner').style.display = 'inline';
            }
        });
    }

    // Caps Lock Warning
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        let capsLockWarning = null;

        passwordInput.addEventListener('keyup', function (e) {
            const isCapsLock = e.getModifierState && e.getModifierState('CapsLock');

            if (isCapsLock) {
                if (!capsLockWarning) {
                    capsLockWarning = document.createElement('div');
                    capsLockWarning.className = 'caps-lock-warning';
                    capsLockWarning.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Caps Lock açık';
                    passwordInput.parentElement.appendChild(capsLockWarning);
                }
            } else {
                if (capsLockWarning) {
                    capsLockWarning.remove();
                    capsLockWarning = null;
                }
            }
        });
    }

});
