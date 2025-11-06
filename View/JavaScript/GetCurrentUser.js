document.addEventListener("DOMContentLoaded", function () {
    const username = document.getElementById("username");
    const name = document.getElementById("name");
    const surname = document.getElementById("surname");
    const gmail = document.getElementById("gmail");
    const telephone = document.getElementById("telephone");
    const password = document.getElementById("password");
    const card_no = document.getElementById("card_no");
    const gender = document.getElementById("gender");

function disableUsernameField() {
        if (username) {
            username.disabled = true;
            username.style.backgroundColor = "#f5f5f5";
            username.style.color = "#666";
            username.title = "Username no se puede modificar";
        }
    }

    function fillFormFromCurrentUser() {
        const currentUserData = localStorage.getItem('currentUser');

        try {
            const userData = JSON.parse(currentUserData);

            // Los datos están en userData.profile y userData.user
            const profile = userData.profile || userData || {};
            const user = userData.user || userData || {};
            
            console.log("Datos del perfil:", profile);
            console.log("Datos del usuario:", user);
            
            // Llenar el formulario con los datos REALES
            // Del perfil (profile)
            username.value = profile.username || profile.user_name || profile.userName || "";
            name.value = profile.name || profile.name_ || profile.firstName || "";
            surname.value = profile.surname || profile.Surname || profile.lastName || "";
            gmail.value = profile.gmail || profile.email || profile.Email || "";
            telephone.value = profile.telephone || profile.Telephone || profile.phone || "";
            password.value = profile.password || profile.passwd || profile.pass || "";
            card_no.value = user.card_no || user.cardNumber || user.card || "";
            gender.value = user.gender || user.gender_ || user.sexo || "";

            // DESHABILITAR username después de llenar el formulario
            disableUsernameField();
            
        } catch (error) {
            console.error("Error al parsear datos del usuario:", error);
            alert("Error al cargar los datos del usuario.");
        }
    }

   function checkLoggedUser() {
        let userData = localStorage.getItem('currentUser');
        
        if (userData) {
            fillFormFromCurrentUser();
        } else {
            console.log("No se encontró usuario logueado en ningún storage");
            // Opcional: redirigir al login o mostrar mensaje
            alert("No hay usuario logueado. Serás redirigido al login.");
            // window.location.href = "login.html"; // Descomenta si quieres redirigir
        }
    }

    checkLoggedUser();
   
   
    window.addEventListener('storage', function(e) {
        if (e.key === 'currentUser') {
            console.log("Usuario cambió, actualizando formulario...");
            checkLoggedUser();
        }
    });
});