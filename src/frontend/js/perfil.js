/**
 * perfil.js
 * Funcionalidad para gestionar el perfil del usuario
 * Incluye edición de datos y ubicación
 */

document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const formPerfil = document.getElementById('formPerfil');
    const formUbicacion = document.getElementById('formUbicacion');
    const modalEdicion = document.getElementById('modalEdicion');
    const btnCerrarModal = document.querySelector('.cerrar-modal');
    const btnObtenerUbicacion = document.getElementById('btnObtenerUbicacion');
    const indicadorUbicacion = document.getElementById('indicadorUbicacion');
    
    // Cargar datos del usuario al iniciar
    cargarDatosUsuario();
    cargarUbicacion();
    
    // Event Listeners
    if (formPerfil) {
        formPerfil.addEventListener('submit', guardarPerfil);
    }
    
    if (formUbicacion) {
        formUbicacion.addEventListener('submit', guardarUbicacion);
    }
    
    if (btnObtenerUbicacion) {
        btnObtenerUbicacion.addEventListener('click', obtenerUbicacionActual);
    }
    
    if (btnCerrarModal) {
        btnCerrarModal.addEventListener('click', cerrarModal);
    }
    
    // Cerrar modal al hacer clic fuera de él
    if (modalEdicion) {
        window.addEventListener('click', function(e) {
            if (e.target === modalEdicion) {
                cerrarModal();
            }
        });
    }
    
    // Toggle visibilidad de ubicación
    const checkboxMostrarUbicacion = document.getElementById('mostrar_ubicacion');
    if (checkboxMostrarUbicacion) {
        checkboxMostrarUbicacion.addEventListener('change', function() {
            guardarVisibilidadUbicacion(this.checked);
        });
    }
});

/**
 * Cargar datos del usuario desde el servidor
 */
function cargarDatosUsuario() {
    fetch('../backend/usuario/gestionar.php', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const usuario = data.data;
            
            // Llenar campos del formulario de perfil
            document.getElementById('nombre').value = usuario.nombre || '';
            document.getElementById('email').value = usuario.email || '';
            document.getElementById('biografia').value = usuario.biografia || '';
            document.getElementById('habilidades').value = usuario.habilidades || '';
            
            // Llenar campos de ubicación
            document.getElementById('ciudad').value = usuario.ciudad || '';
            document.getElementById('pais').value = usuario.pais || '';
            document.getElementById('latitud').value = usuario.latitud || '';
            document.getElementById('longitud').value = usuario.longitud || '';
            
            // Checkbox de visibilidad
            const checkboxMostrar = document.getElementById('mostrar_ubicacion');
            if (checkboxMostrar) {
                checkboxMostrar.checked = usuario.mostrar_ubicacion == 1;
            }
            
            // Actualizar indicador de ubicación
            actualizarIndicadorUbicacion(usuario.latitud, usuario.longitud);
        } else {
            mostrarNotificacion(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarNotificacion('Error al cargar los datos', 'error');
    });
}

/**
 * Cargar ubicación específica
 */
function cargarUbicacion() {
    fetch('../backend/usuario/ubicacion.php', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const ubicacion = data.data;
            document.getElementById('ciudad').value = ubicacion.ciudad || '';
            document.getElementById('pais').value = ubicacion.pais || '';
            document.getElementById('latitud').value = ubicacion.latitud || '';
            document.getElementById('longitud').value = ubicacion.longitud || '';
            
            const checkboxMostrar = document.getElementById('mostrar_ubicacion');
            if (checkboxMostrar) {
                checkboxMostrar.checked = ubicacion.mostrar_ubicacion == 1;
            }
            
            actualizarIndicadorUbicacion(ubicacion.latitud, ubicacion.longitud);
        }
    })
    .catch(error => {
        console.error('Error al cargar ubicación:', error);
    });
}

/**
 * Guardar datos del perfil
 */
function guardarPerfil(e) {
    e.preventDefault();
    
    const datos = {
        nombre: document.getElementById('nombre').value,
        biografia: document.getElementById('biografia').value,
        habilidades: document.getElementById('habilidades').value
    };
    
    // Validación básica
    if (datos.nombre.length < 2) {
        mostrarNotificacion('El nombre debe tener al menos 2 caracteres', 'error');
        return;
    }
    
    fetch('../backend/usuario/gestionar.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarNotificacion('Perfil actualizado correctamente', 'success');
            cerrarModal();
        } else {
            mostrarNotificacion(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarNotificacion('Error al guardar el perfil', 'error');
    });
}

/**
 * Guardar ubicación del usuario
 */
function guardarUbicacion(e) {
    e.preventDefault();
    
    const datos = {
        latitud: document.getElementById('latitud').value || null,
        longitud: document.getElementById('longitud').value || null,
        ciudad: document.getElementById('ciudad').value,
        pais: document.getElementById('pais').value,
        mostrar_ubicacion: document.getElementById('mostrar_ubicacion').checked
    };
    
    fetch('../backend/usuario/ubicacion.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarNotificacion('Ubicación guardada correctamente', 'success');
            actualizarIndicadorUbicacion(datos.latitud, datos.longitud);
        } else {
            mostrarNotificacion(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarNotificacion('Error al guardar la ubicación', 'error');
    });
}

/**
 * Obtener ubicación actual del usuario usando Geolocation API
 */
function obtenerUbicacionActual() {
    const btn = document.getElementById('btnObtenerUbicacion');
    const textoOriginal = btn.innerHTML;
    btn.innerHTML = '<span class="cargando"></span> Obteniendo...';
    btn.disabled = true;
    
    if (!navigator.geolocation) {
        mostrarNotificacion('Tu navegador no soporta geolocalización', 'error');
        btn.innerHTML = textoOriginal;
        btn.disabled = false;
        return;
    }
    
    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            // Llenar campos
            document.getElementById('latitud').value = lat.toFixed(6);
            document.getElementById('longitud').value = lng.toFixed(6);
            
            // Intentar obtener ciudad y país (requiere API de geocodificación)
            // Por ahora, dejamos que el usuario lo ingrese manualmente
            mostrarNotificacion('Ubicación obtenida. Puedes completar ciudad y país.', 'success');
            
            btn.innerHTML = textoOriginal;
            btn.disabled = false;
            
            // Actualizar mapa si existe
            if (typeof actualizarMapa === 'function') {
                actualizarMapa(lat, lng);
            }
        },
        function(error) {
            let mensaje = 'Error al obtener ubicación';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    mensaje = 'Permiso de ubicación denegado';
                    break;
                case error.POSITION_UNAVAILABLE:
                    mensaje = 'Ubicación no disponible';
                    break;
                case error.TIMEOUT:
                    mensaje = 'Tiempo agotado';
                    break;
            }
            mostrarNotificacion(mensaje, 'error');
            btn.innerHTML = textoOriginal;
            btn.disabled = false;
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
}

/**
 * Guardar visibilidad de ubicación
 */
function guardarVisibilidadUbicacion(mostrar) {
    const datos = {
        latitud: document.getElementById('latitud').value || null,
        longitud: document.getElementById('longitud').value || null,
        ciudad: document.getElementById('ciudad').value,
        pais: document.getElementById('pais').value,
        mostrar_ubicacion: mostrar
    };
    
    fetch('../backend/usuario/ubicacion.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const estado = mostrar ? 'visible' : 'oculta';
            mostrarNotificacion(`Tu ubicación ahora está ${estado} para otros usuarios`, 'success');
        } else {
            mostrarNotificacion(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

/**
 * Actualizar indicador visual de ubicación
 */
function actualizarIndicadorUbicacion(lat, lng) {
    const indicador = document.getElementById('indicadorUbicacion');
    if (indicador) {
        if (lat && lng) {
            indicador.classList.add('activa');
            indicador.title = 'Ubicación configurada';
        } else {
            indicador.classList.remove('activa');
            indicador.title = 'Sin ubicación';
        }
    }
}

/**
 * Abrir modal de edición
 */
function abrirModal() {
    const modal = document.getElementById('modalEdicion');
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Cerrar modal de edición
 */
function cerrarModal() {
    const modal = document.getElementById('modalEdicion');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

/**
 * Mostrar notificación toast
 */
function mostrarNotificacion(mensaje, tipo) {
    // Crear elemento de notificación
    const notificacion = document.createElement('div');
    notificacion.className = `notificacion ${tipo}`;
    notificacion.innerHTML = `
        <span>${mensaje}</span>
        <button onclick="this.parentElement.remove()">&times;</button>
    `;
    
    // Estilos inline para la notificación
    Object.assign(notificacion.style, {
        position: 'fixed',
        top: '20px',
        right: '20px',
        padding: '15px 20px',
        borderRadius: '5px',
        color: 'white',
        zIndex: '9999',
        display: 'flex',
        alignItems: 'center',
        gap: '10px',
        animation: 'slideIn 0.3s ease',
        maxWidth: '350px'
    });
    
    if (tipo === 'success') {
        notificacion.style.backgroundColor = '#28a745';
    } else if (tipo === 'error') {
        notificacion.style.backgroundColor = '#dc3545';
    } else {
        notificacion.style.backgroundColor = '#17a2b8';
    }
    
    // Agregar al DOM
    document.body.appendChild(notificacion);
    
    // Auto eliminar después de 5 segundos
    setTimeout(() => {
        if (notificacion.parentElement) {
            notificacion.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notificacion.remove(), 300);
        }
    }, 5000);
}

/**
 * Eliminar ubicación
 */
function eliminarUbicacion() {
    if (!confirm('¿Estás seguro de que quieres eliminar tu ubicación?')) {
        return;
    }
    
    fetch('../backend/usuario/ubicacion.php', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Limpiar campos
            document.getElementById('latitud').value = '';
            document.getElementById('longitud').value = '';
            document.getElementById('ciudad').value = '';
            document.getElementById('pais').value = '';
            document.getElementById('mostrar_ubicacion').checked = false;
            
            actualizarIndicadorUbicacion(null, null);
            mostrarNotificacion('Ubicación eliminada', 'success');
        } else {
            mostrarNotificacion(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarNotificacion('Error al eliminar ubicación', 'error');
    });
}

// Agregar estilos de animación
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .cargando {
        display: inline-block;
        width: 12px;
        height: 12px;
        border: 2px solid #fff;
        border-top-color: transparent;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);

