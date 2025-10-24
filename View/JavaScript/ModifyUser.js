document.addEventListener("DOMContentLoaded", function() {
    const userList = document.getElementById("userList");
    const username = document.getElementById("username");
    const name = document.getElementById("name");
    const surname = document.getElementById("surname");
    const gmail = document.getElementById("gmail");
    const telephone = document.getElementById("telephone");
    const password = document.getElementById("password");
    const card_no = document.getElementById("card_no");
    const gender = document.getElementById("gender");
    const modifyForm = document.querySelector("form");

    function clearForm() {
        if (username) username.value = "";
        if (name) name.value = "";
        if (surname) surname.value = "";
        if (gmail) gmail.value = "";
        if (telephone) telephone.value = "";
        if (password) password.value = "";
        if (card_no) card_no.value = "";
        if (gender) gender.value = "";
    }

    function collectFormData() {
        const selected = userList ? userList.options[userList.selectedIndex] : null;
        const profileCode = selected ? (selected.dataset.profileCode || selected.value || selected.dataset.id) : "";

        if (!profileCode) {
            alert("Selecciona un usuario para modificar.");
            return null;
        }

        const formData = {
            Profile_code: profileCode
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

    if (modifyForm) {
        modifyForm.addEventListener("submit", async function(e) {
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
                        // Trigger change to refill form with updated data
                        if (userList) {
                            userList.dispatchEvent(new Event("change"));
                        }
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
