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

    function buildUserOption(u) {
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

    function populateList(users) {
        if (!Array.isArray(users)) return;
        userList.innerHTML = "";
        users.forEach(u => userList.appendChild(buildUserOption(u)));
        if (userList.options.length > 0) {
            userList.selectedIndex = 0;
            fillFormFromOption(userList.options[0]);
        }
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

    userList.addEventListener("change", function() {
        const opt = userList.options[userList.selectedIndex];
        fillFormFromOption(opt);
    });

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
            populateList(users);
        })
        .catch(error => console.error("Error fetching users:", error));
});