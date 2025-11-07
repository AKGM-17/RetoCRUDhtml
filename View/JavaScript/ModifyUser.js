document.addEventListener("DOMContentLoaded", function () {
    const userList = document.getElementById("userList");
    const username = document.getElementById("username");
    const name = document.getElementById("name");
    const surname = document.getElementById("surname");
    const gmail = document.getElementById("gmail");
    const telephone = document.getElementById("telephone");
    const password = document.getElementById("password");
    let card_no = document.getElementById("card_no");
    let gender = document.getElementById("gender");
    const modifyForm = document.querySelector("form");

    




    //Funcion para cargar la lista de usuarios y actualizar el formulario en ModifyViewAdmin.html
    function reloadUserListAndUpdateForm(profileCode) {
        fetch("../../ServidorPHP/Ejemplo%20BD/api/listar_User.php")
            .then(response => response.text())
            .then(text => {
                let users = [];
                try {
                    const data = JSON.parse(text);
                    if (data && data.error) {
                        console.error(data.error);
                        return;
                    }
                    if (Array.isArray(data)) {
                        users = data;
                    } else if (Array.isArray(data.users)) {
                        users = data.users;
                    } else if (data) {
                        users = [data];
                    }
                } catch (_) {
                    // Handle concatenated JSON objects from API
                    const parts = text
                        .replace(/}\s*{/g, '}|||{')
                        .split('|||')
                        .map(t => { try { return JSON.parse(t); } catch { return null; } })
                        .filter(Boolean);
                    users = parts;
                }

                // Update the user list
                userList.innerHTML = "";
                let selectedOption = null;
                users.forEach(u => {
                    const option = createUserOption(u);
                    userList.appendChild(option);

                    // Find the updated user
                    const userProfileCode = u.Profile_code || u.profile_Code || u.profile_code || u.id || "";
                    if (userProfileCode.toString() === profileCode.toString()) {
                        selectedOption = option;
                    }
                });

                // Select the updated user and fill form
                if (selectedOption) {
                    userList.value = selectedOption.value;
                    fillFormFromOption(selectedOption);
                }

            })
            .catch(error => console.error("Error reloading users:", error));
    }

    function createUserOption(u) {
        const option = document.createElement("option");
        const profileCode = u.Profile_code || u.profile_Code || u.profile_code || "";
        const id = u.id || profileCode || u.card_no || "";
        const display = u.username || u.name || u.surname || u.gmail || profileCode || id || "";
        option.value = profileCode || id || u.username || u.name || "";
        option.textContent = display;
        option.dataset.id = id || "";
        option.dataset.profileCode = profileCode || "";
        option.dataset.username = u.username || "";
        option.dataset.name = u.name || "";
        option.dataset.surname = u.surname || "";
        option.dataset.gmail = u.gmail || "";
        option.dataset.telephone = u.telephone || "";
        option.dataset.password = u.password || "";
        option.dataset.card_no = u.card_no || "";
        option.dataset.gender = u.gender || "";
        return option;
    }
    
    

    function collectFormData() {
        const selected = userList ? userList.options[userList.selectedIndex] : null;
        const profileCode = selected ? (selected.dataset.profileCode || selected.value || selected.dataset.id) : "";
        const userData = localStorage.getItem('currentUser') ? JSON.parse(localStorage.getItem('currentUser')) : null;
        const profile = userData ? userData.profile || {} : {};
        const user = userData ? userData.user || {} : {};
        // CORREGIDO: Buscar Profile_code en las propiedades correctas
        const localStorageProfileCode = profile.Profile_code || profile.user_code || profile.id || user.Profile_code || user.id || "";


        if (!profileCode && window.location.pathname.includes('WindowAdmin.html')) {
            alert("Selecciona un usuario para modificar.");

        } else if (!localStorageProfileCode) {
            alert("No hay un usuario logueado.");
            return;
        }

        const formData = {
            Profile_code: profileCode || localStorageProfileCode
        };

        // Incluir solo los campos que no estén vacíos
        if (username && username.value.trim() !== "") formData.username = username.value.trim();
        if (name && name.value.trim() !== "") formData.name = name.value.trim();
        if (surname && surname.value.trim() !== "") formData.surname = surname.value.trim();
        if (gmail && gmail.value.trim() !== "") formData.gmail = gmail.value.trim();
        if (telephone && telephone.value.trim() !== "") formData.telephone = telephone.value.trim();
        if (password && password.value.trim() !== "") formData.password = password.value.trim();
        if (card_no && card_no.value.trim() !== "") formData.card_no = card_no.value.trim();
        if (gender && gender.value.trim() !== "") formData.gender = gender.value.trim();
    
        return formData;
    }



    function fillFormFromOption(opt) {
        if (!opt) return;
        username.value = opt.dataset.username || "";
        name.value = opt.dataset.name || "";
        surname.value = opt.dataset.surname || "";
        gmail.value = opt.dataset.gmail || "";
        telephone.value = opt.dataset.telephone || "";
        password.value = opt.dataset.password || "";
        card_no.value = opt.dataset.card_no || "";
        gender.value = opt.dataset.gender || "";
        
    
    }

    // Event listener para cuando cambie la selección del dropdown
    if (userList) {
        userList.addEventListener("change", function () {
            const opt = userList.options[userList.selectedIndex];
            fillFormFromOption(opt);
        });
    }

    if (modifyForm) {
        modifyForm.addEventListener("submit", async function (e) {
            e.preventDefault(); // Prevenir el envío normal del formulario

            const formData = collectFormData();
            if (!formData) return;

            // Verificar que al menos un campo esté lleno (además del Profile_code)
            const dataFields = Object.keys(formData).filter(key => key !== 'Profile_code');
            if (dataFields.length === 0) {
                alert("Completa al menos un campo para modificar.");
                return;
            }

            if (!confirm("¿Seguro que quieres modificar este usuario?")) return;

            try {
                const params = new URLSearchParams();
                Object.keys(formData).forEach(key => {
                    params.append(key, formData[key]);
                });

                const url = "../../ServidorPHP/Ejemplo%20BD/api/modificar_usuario.php";
                const res = await fetch(url, {
                    method: "POST",
                    body: params
                });

                const text = await res.text();

                let data = null;
                try {
                    data = JSON.parse(text);

                    if (data && data.ok) {
                        alert("Usuario modificado correctamente");
                        // Reload user list and update form with fresh data

                        const currentProfileCode = formData.Profile_code;
                        reloadUserListAndUpdateForm(currentProfileCode);
                        
                    } else {
                        alert((data && data.error) || "No se pudo modificar el usuario");
                    }
                } catch (parseError) {
                    alert("Error del servidor");
                }

            } catch (e) {
                alert("Error modificando el usuario");
            }
        });
    }
});
