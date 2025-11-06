/**
 * Component Loader - Carga navbar y footer dinámicamente
 * Layout estático con detección de usuario
 */
console.log("🎯 ComponentLoader.js - ARCHIVO CARGADO");

// Verificar que la clase se define
console.log("🔧 Definiendo clase ComponentLoader...");
class ComponentLoader {
    constructor() {
         console.log("🚀 ComponentLoader - CONSTRUCTOR ejecutado");
        this.init();
    }

    init() {
        document.addEventListener('DOMContentLoaded', () => {
            console.log("📄 DOM completamente cargado");
            this.loadNavbar();
            this.loadFooter();
            this.highlightActiveNav();
        });
    }

    async loadNavbar() {
    // CORREGIR: No hacer doble parse
    const usuarioJSON = localStorage.getItem('currentUser');
    let navbarUrl = 'navbar.html'; // Por defecto
    
    
    if (usuarioJSON) {
        try {
            const usuario = JSON.parse(usuarioJSON); // Solo un parse aquí
            console.log('🔍 Usuario encontrado:', usuario);
            
            // Elegir navbar según tipo de usuario
            // Si no existe 'type', usar 'is_admin' como fallback
            let userType = usuario.type;
            
            // Si no hay 'type', determinar basado en 'is_admin'
            if (!userType) {
                userType = usuario.is_admin ? 'admin' : 'user';
                console.log('🎯 Tipo determinado por is_admin:', userType);
            }
            
            console.log('🎯 Tipo de usuario final:', userType);
            
            switch (userType.toLowerCase()) {
                case 'admin':
                case 'administrador':
                    navbarUrl = 'navbar_admin.html';
                    break;
                case 'user':
                case 'usuario':
                    navbarUrl = 'navbar_user.html';
                    break;
                default:
                    console.warn('⚠️ Tipo de usuario no reconocido:', userType);
                    navbarUrl = 'navbar.html';
            }
            
        } catch (error) {
            console.error('❌ Error parseando usuario:', error);
            this.loadDefaultNavbar();
            return;
        }
    } else {
        console.log('👤 No hay usuario en localStorage');
        // Sin usuario: header simple
        this.loadSimpleHeader();
        return;
    }

    console.log('📄 Cargando navbar:', navbarUrl);
    
    try {
        const response = await fetch(navbarUrl);
        if (!response.ok) throw new Error(`HTTP ${response.status}`);

        const html = await response.text();
        const header = document.getElementById('header');

        if (header) {
            header.innerHTML = html;
            this.addNavbarFunctionality();
        }
    } catch (error) {
        console.error('❌ Error cargando navbar:', error);
        this.loadDefaultNavbar();
    }
}

    async loadSimpleHeader() {
        const header = document.getElementById('header');
        if (header) {
            header.innerHTML = '<div class="login-header"><h3>CRUD User Panel</h3></div>';
        }
    }

    async loadDefaultNavbar() {
        try {
            const response = await fetch('navbar.html');
            const html = await response.text();
            const header = document.getElementById('header');

            if (header) {
                header.innerHTML = html;
                this.addNavbarFunctionality();
            }
        } catch (error) {
            console.error('Error navbar por defecto:', error);
        }
    }

    async loadFooter() {
        if (window.location.pathname.includes('LogIn.html')) {
            return; // Sin footer en login
        }

        try {
            const response = await fetch('footer.html');
            const html = await response.text();
            const footer = document.getElementById('footer');

            if (footer) {
                footer.innerHTML = html;
            }
        } catch (error) {
            console.error('Error cargando footer:', error);
        }
    }

    addNavbarFunctionality() {
        // Menú móvil
        const toggle = document.querySelector('.navbar-toggle');
        const menu = document.querySelector('.navbar-menu');

        if (toggle && menu) {
            toggle.addEventListener('click', () => {
                menu.classList.toggle('active');
                toggle.classList.toggle('active');
            });
        }

        // Cerrar menú en enlaces
        const links = document.querySelectorAll('.nav-link');
        links.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768 && menu && toggle) {
                    menu.classList.remove('active');
                    toggle.classList.remove('active');
                }
            });
        });

        // Logout funcionalidad
        const logoutBtn = document.querySelector('.logout-link');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.logout();
            });
        }
    }

    logout() {
        localStorage.removeItem('currentUser'); // Cambiar de 'usuarioLogueado' a 'currentUser'
        window.location.href = 'LogIn.html';
    }

    highlightActiveNav() {
        const currentPage = window.location.pathname.split('/').pop();
        const links = document.querySelectorAll('.nav-link');

        links.forEach(link => {
            const href = link.getAttribute('href');
            if (href && href.includes(currentPage)) {
                link.classList.add('active');
            }
        });
    }

    navigateTo(page) {
        window.location.href = page;
    }
}

// Inicializar
const componentLoader = new ComponentLoader();
window.ComponentLoader = ComponentLoader;
