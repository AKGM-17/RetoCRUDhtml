# Sistema de Componentes CRUD - Layout Estático

Sistema completo de navbar y footer con detección automática de usuario y layout estático.

## ✅ Implementado

### Layout Estático
- **Navbar**: Elemento estático (no fijo) en la parte superior
- **Footer**: Elemento estático (no fijo) en la parte inferior
- **Contenido**: Centrado verticalmente entre navbar y footer
- **IDs**: `id="header"` y `id="footer"` en todos los HTML

### Navbars por Tipo de Usuario
- **navbar.html**: Navbar básico por defecto
- **navbar_admin.html**: Para administradores (Panel, Usuarios, Reportes, Configuración)
- **navbar_user.html**: Para usuarios normales (Mi Panel, Perfil, Historial)
- **navbar_piloto.html**: Para pilotos (Misiones, Perfil, Estado)

### Detección Automática de Usuario
```javascript
// El sistema detecta desde localStorage:
const usuario = JSON.parse(localStorage.getItem('usuarioLogueado') || 'null');

if (usuario.tipo === 'admin') {
    // Carga navbar_admin.html
} else if (usuario.tipo === 'user') {
    // Carga navbar_user.html
}
```

## 📁 Archivos

### HTML
- `LogIn.html` - Página de login (sin navbar/footer)
- `WindowAdmin.html` - Panel principal (con navbar/footer)
- `ModifyView.html` - Modificar usuario (con navbar/footer)

### Componentes
- `navbar.html` - Navbar por defecto
- `navbar_admin.html` - Navbar administrador
- `navbar_user.html` - Navbar usuario
- `footer.html` - Footer minimalista

### JavaScript
- `ComponentLoader.js` - Sistema de carga automática

## 🚀 Uso

### 1. Login de usuario
```javascript
// Después de validar credenciales
localStorage.setItem('usuarioLogueado', JSON.stringify({
    nombre: 'Juan Pérez',
    tipo: 'admin', // admin, user, piloto
    email: 'juan@empresa.com'
}));
```

### 2. Logout
```javascript
// Al hacer click en "Cerrar Sesión"
localStorage.removeItem('usuarioLogueado');
window.location.href = 'LogIn.html';
```

### 3. Estructura HTML requerida
```html
<body>
    <header id="header"></header>
    <main>
        <div id="modify"><!-- contenido --></div>
    </main>
    <footer id="footer"></footer>
    <script src="../JavaScript/ComponentLoader.js"></script>
</body>
```

## 🎨 Layout

```
┌─ Header (navbar estático) ──────────────┐
│  CRUD Admin Panel | Panel | Usuarios | Logout  │
├─ Main Content (centrado) ──────────────┤
│  ┌─ Form Container ───────────────────┐ │
│  │  Admin Panel    User Data          │ │
│  │  [Select] [Form Fields]            │ │
│  │  [Modify] [Delete] Buttons         │ │
│  └────────────────────────────────────┘ │
├─ Footer (estático) ─────────────────────┤
│  © 2025 CRUD Admin Panel                │
└─────────────────────────────────────────┘
```

## 📱 Responsive
- Menú hamburguesa para móviles
- Layout adaptativo
- Footer compacto en todas las pantallas

## 🔧 Funcionalidades
- ✅ Carga automática de navbar según usuario
- ✅ Navegación activa (highlight)
- ✅ Menú móvil responsive
- ✅ Logout automático
- ✅ Layout estático (sin position: fixed)
- ✅ Footer minimalista
- ✅ Fallback a navbar por defecto
