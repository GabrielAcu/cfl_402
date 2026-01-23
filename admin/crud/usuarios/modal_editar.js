document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("modalModificarUsuario");
    const cerrar = document.getElementById("cerrarModificar");

    // Inputs
    const inputId = document.getElementById("mod_id");
    const inputNombre = document.getElementById("mod_nombre");
    const inputRol = document.getElementById("mod_rol");
    const inputPass = document.getElementById("mod_contrasenia");
    const inputPassConf = document.getElementById("mod_contrasenia_conf");

    if (!modal) return;

    // Delegación de eventos para botones editar
    document.body.addEventListener("click", e => {
        const btn = e.target.closest(".btnModificarAlumno"); // Usamos la clase que ya estaba en el HTML
        if (btn) {
            const id = btn.dataset.id;
            console.log("Editando usuario ID:", id);

            // Limpiar campos de contraseña
            inputPass.value = "";
            inputPassConf.value = "";

            // Cargar datos
            fetch(`cargar_datos.php?id=${id}`)
                .then(r => r.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    const u = data.usuario;

                    // Llenar formulario
                    inputId.value = u.id;
                    inputNombre.value = u.nombre;
                    inputRol.value = u.rol;

                    modal.style.display = "flex"; // Flex para centrar (según modal.css nuevo)
                })
                .catch(err => console.error("Error al cargar usuario:", err));
        }
    });

    if (cerrar) cerrar.onclick = () => modal.style.display = "none";

    window.onclick = (e) => {
        if (e.target === modal) modal.style.display = "none";
    };
});
