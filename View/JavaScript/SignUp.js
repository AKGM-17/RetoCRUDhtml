document.addEventListener("DOMContentLoaded", function () {
    const signupForm = document.getElementById("signupForm");
    const username = document.getElementById("username");
    const name = document.getElementById("name");
    const surname = document.getElementById("surname");
    const gmail = document.getElementById("gmail");
    const telephone = document.getElementById("telephone");
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirmPassword");
    const card_no = document.getElementById("card_no");
    const gender = document.getElementById("gender");
    const submitBtn = signupForm.querySelector('button[type="submit"]');

    function showMessage(message, isError = false) {
        let messageDiv = document.getElementById("signup-message");
        if (!messageDiv) {
            messageDiv = document.createElement("div");
            messageDiv.id = "signup-message";
            messageDiv.style.cssText = "margin: 10px 0; padding: 10px; border-radius: 4px; font-weight: bold;";
            signupForm.appendChild(messageDiv);
        }

        messageDiv.textContent = message;
        messageDiv.style.backgroundColor = isError ? "#ffebee" : "#e8f5e8";
        messageDiv.style.color = isError ? "#c62828" : "#2e7d32";
        messageDiv.style.border = isError ? "1px solid #ef5350" : "1px solid #4caf50";
    }

    function hideMessage() {
        const messageDiv = document.getElementById("signup-message");
        if (messageDiv) {
            messageDiv.remove();
        }
    }

    function validateForm() {
        hideMessage();

        // Validar contraseñas
        if (password.value !== confirmPassword.value) {
            showMessage("Las contraseñas no coinciden", true);
            confirmPassword.focus();
            return false;
        }

        // Validar longitud de contraseña
        if (password.value.length < 4) {
            showMessage("Password must be at least 4 characters long", true);
            password.focus();
            return false;
        }

        // Validar email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(gmail.value)) {
            showMessage("Please enter a valid email", true);
            gmail.focus();
            return false;
        }

        // Validar teléfono (si se proporciona)
        if (telephone.value && !/^\d+$/.test(telephone.value)) {
            showMessage("The phone number must contain only numbers", true);
            telephone.focus();
            return false;
        }

        return true;
    }

    async function handleSignUp(event) {
        event.preventDefault();
        
        if (!validateForm()) {
            return;
        }

        submitBtn.disabled = true;
        submitBtn.textContent = "Creando cuenta...";

        try {
            const formData = {
                username: username.value.trim(),
                name: name.value.trim(),
                surname: surname.value.trim(),
                gmail: gmail.value.trim(),
                telephone: telephone.value.trim() || null,
                password: password.value,
                card_no: card_no.value.trim(),
                gender: gender.value
            };

            console.log('Enviando datos de registro:', formData);

            const response = await fetch("../../ServidorPHP/Ejemplo%20BD/api/SignUp.php", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();
            console.log('Respuesta del registro:', result);

            if (result.success) {
                showMessage("Account created successfully. Redirecting to login...", false);
                
                // Limpiar formulario
                signupForm.reset();
                
                // Redirigir al login después de 2 segundos
                setTimeout(() => {
                    window.location.href = "LogIn.html";
                }, 2000);
                
            } else {
                showMessage(result.error || "Error al crear la cuenta", true);
            }
        } catch (error) {
            console.error("Error en el registro:", error);
            showMessage("Error de conexión. Try again.", true);
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = "Sign Up";
        }
    }

    // Event listeners
    if (signupForm) {
        signupForm.addEventListener("submit", handleSignUp);
    }

    // Validación en tiempo real de confirmación de contraseña
    if (confirmPassword) {
        confirmPassword.addEventListener("input", function() {
            if (password.value !== confirmPassword.value) {
                confirmPassword.style.borderColor = "#ef5350";
            } else {
                confirmPassword.style.borderColor = "#4caf50";
            }
        });
    }

    // Enfocar el primer campo al cargar
    if (username) {
        username.focus();
    }
});