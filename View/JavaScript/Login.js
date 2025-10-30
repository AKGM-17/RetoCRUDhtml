
document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.querySelector("#modify form");
    const usernameInput = document.getElementById("username");
    const passwordInput = document.getElementById("password");
    const loginButton = document.getElementById("logIn");



    function showMessage(message, isError = false) {
        // Crear o actualizar un mensaje de estado
        let messageDiv = document.getElementById("login-message");
        if (!messageDiv) {
            messageDiv = document.createElement("div");
            messageDiv.id = "login-message";
            messageDiv.style.cssText = "margin: 10px 0; padding: 10px; border-radius: 4px; font-weight: bold;";
            loginForm.appendChild(messageDiv);
        }

        messageDiv.textContent = message;
        messageDiv.style.backgroundColor = isError ? "#ffebee" : "#e8f5e8";
        messageDiv.style.color = isError ? "#c62828" : "#2e7d32";
        messageDiv.style.border = isError ? "1px solid #ef5350" : "1px solid #4caf50";
    }

    function hideMessage() {
        const messageDiv = document.getElementById("login-message");
        if (messageDiv) {
            messageDiv.remove();
        }
    }

    async function handleLogin(event) {
        event.preventDefault();
        hideMessage();
        const username = usernameInput.value || usernameInput.textContent || '';
        const password = passwordInput.value || passwordInput.textContent || '';



        if (!username || !password) {
            showMessage("Por favor ingresa usuario y contraseña", true);
            return;
        }

        loginButton.disabled = true;
        loginButton.textContent = "Iniciando sesión...";

        try {
            // Cambiar a URL encoded en lugar de FormData
            const params = new URLSearchParams();
            params.append('username', username);
            params.append('password', password);

            console.log('Sending login request with params:', params.toString());


            const response = await fetch("../../ServidorPHP/Ejemplo%20BD/api/login.php", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: params
            });

            const result = await response.json();
            console.log('Login response:', result);

            if (result.success) {
                showMessage("¡Login exitoso! Redirigiendo...", false);

                // Guardar información del usuario en localStorage
                if (result.is_admin) {
                    const adminData = {
                        type: "admin",
                        profile: result.profile,
                        user: result.user,
                        is_admin: true
                    };
                    localStorage.setItem('currentUser', JSON.stringify(adminData));
                } else {
                    const userData = {
                        type: "user",
                        profile: result.profile,
                        user: result.user,
                        is_admin: false
                    };
                    localStorage.setItem('currentUser', JSON.stringify(userData));
                }

                // Redirigir después de un breve delay
                setTimeout(() => {
                    if (result.is_admin) {
                        window.location.href = "WindowAdmin.html";
                    } else {
                        window.location.href = "ModifyView.html";
                    }
                }, 1000);
            } else {
                console.error('Login failed:', result.error);
                showMessage(result.error || "Error en el login", true);
                passwordInput.value = ""; // Limpiar password por seguridad
                passwordInput.focus();
            }
        } catch (error) {
            console.error("Error en login:", error);
            showMessage("Error de conexión. Intenta nuevamente.", true);
        } finally {
            loginButton.disabled = false;
            loginButton.textContent = "Log In";
        }
    }


    // Manejar submit del formulario
    if (loginForm) {
        loginForm.addEventListener("submit", handleLogin);
    }

    // También manejar click del botón por si acaso
    if (loginButton) {
        loginButton.addEventListener("click", function (e) {
            if (loginForm) {
                e.preventDefault();
                handleLogin(e);
            }
        });
    }

    // Enfocar el campo de usuario al cargar la página
    if (usernameInput) {
        usernameInput.focus();
    }


});
