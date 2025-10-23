
    const userList = document.getElementById("userList");
    const username = document.getElementById("username");
    const name = document.getElementById("name");
    const surname = document.getElementById("surname");
    const gmail = document.getElementById("gmail");
    const telephone = document.getElementById("telephone");
    const password = document.getElementById("password");
    const deleteButton = document.querySelector("button[type='button']");

    function clearForm() {
        if (username) username.value = "";
        if (name) name.value = "";
        if (surname) surname.value = "";
        if (gmail) gmail.value = "";
        if (telephone) telephone.value = "";
        if (password) password.value = "";
    }

    if (deleteButton) {
        deleteButton.addEventListener("click", async function() {
            console.log("userList options:", userList ? userList.options.length : 0, "selectedIndex:", userList ? userList.selectedIndex : -1);
            if (userList && userList.selectedIndex < 0 && userList.options.length > 0) {
                userList.selectedIndex = 0;
            }
            const selected = userList ? userList.options[userList.selectedIndex] : null;
            const profileCode = selected ? (selected.dataset.profileCode || selected.value || selected.dataset.id) : "";
            const id = selected ? (selected.dataset.id || selected.value || selected.dataset.profileCode) : "";
            const uname = selected ? (selected.dataset.username || "") : "";
            console.log("delete identifiers:", { profileCode, id, username: uname });
            if (!profileCode && !id) {
                alert("Selecciona un usuario para borrar.");
                return;
            }

            if (!confirm("¿Seguro que quieres borrar este usuario?")) return;

            try {
                const params = new URLSearchParams();
                if (profileCode) params.append("Profile_code", profileCode);
                if (id) params.append("id", id);
                if (uname) params.append("username", uname);
                const url = "../../ServidorPHP/Ejemplo%20BD/api/borrar_usuario.php?" + params.toString();
                const res = await fetch(url, { method: "GET" });
                const text = await res.text();
                let data = null;
                try { data = JSON.parse(text); } catch { data = { error: "Respuesta inválida" }; }

                if (data && data.ok) {
                    // Remove from list
                    if (selected) {
                        userList.removeChild(selected);
                    }
                    // Select next item if exists
                    if (userList.options.length > 0) {
                        userList.selectedIndex = 0;
                        const next = userList.options[0];
                        // Trigger change to refill form if GetAllUsers.js listener exists
                        userList.dispatchEvent(new Event("change"));
                    } else {
                        clearForm();
                    }
                    alert("Usuario borrado correctamente");
                } else {
                    alert((data && data.error) || "No se pudo borrar");
                }
            } catch (e) {
                console.error(e);
                alert("Error realizando el borrado");
            }
        });
    }
