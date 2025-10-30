document.addEventListener("DOMContentLoaded", function () {
    const username = document.getElementById("username");
    const name = document.getElementById("name");
    const surname = document.getElementById("surname");
    const gmail = document.getElementById("gmail");
    const telephone = document.getElementById("telephone");
    const password = document.getElementById("password");
    const card_no = document.getElementById("card_no");
    const gender = document.getElementById("gender");


    function fillFormFromCurrentUser() {
        const currentUserData = localStorage.getItem('currentUser');

        try {
            const userData = JSON.parse(currentUserData);

            // Los datos están en userData.profile y userData.user
            const profile = userData.profile || {};
            const user = userData.user || {};
            
            console.log("Datos del perfil:", profile);
            console.log("Datos del usuario:", user);
            
            // Llenar el formulario con los datos REALES
            // Del perfil (profile)
            username.value = profile.username || "";
            name.value = profile.name || "";
            surname.value = profile.surname || "";
            gmail.value = profile.gmail || "";
            telephone.value = profile.telephone || "";
            password.value = profile.passwd || "";
            
            // Del usuario (user)
            card_no.value = user.card_no || "";
            gender.value = user.gender || "";
            
            console.log("✅ Formulario llenado correctamente");
            
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