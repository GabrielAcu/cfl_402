document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("modalModificarAlumno");
    const cerrar = document.getElementById("cerrarModificar");

    // Inputs
    const modId = document.getElementById("mod_id_alumno");
    const modNombre = document.getElementById("mod_nombre");
    const modApellido = document.getElementById("mod_apellido");
    const modDni = document.getElementById("mod_dni");
    const modTelefono = document.getElementById("mod_telefono");
    const modCorreo = document.getElementById("mod_correo");
    const modNac = document.getElementById("mod_fecha_nacimiento");
    const modDireccion = document.getElementById("mod_direccion");
    const modLocalidad = document.getElementById("mod_localidad");
    const modCp = document.getElementById("mod_cp");
    const modVehiculo = document.getElementById("mod_vehiculo");
    const modPatente = document.getElementById("mod_patente");
    const modObs = document.getElementById("mod_observaciones");

    if (!modal) return;

    // Delegación: Detectar clic en botón .btnModificarAlumno
    document.body.addEventListener("click", e => {
        const btn = e.target.closest(".btnModificarAlumno");
        if (btn) {
            const id = btn.dataset.id;
            console.log("Editando alumno ID:", id);

            // Cargar datos vía AJAX
            fetch(`cargar_datos.php?id_alumno=${id}`)
                .then(r => r.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    const a = data.alumno;

                    // Llenar formulario
                    modId.value = a.id_alumno;
                    modNombre.value = a.nombre;
                    modApellido.value = a.apellido;
                    modDni.value = a.dni;
                    modTelefono.value = a.telefono;
                    modCorreo.value = a.correo;
                    modNac.value = a.fecha_nacimiento;
                    modDireccion.value = a.direccion;
                    modLocalidad.value = a.localidad;
                    modCp.value = a.cp;
                    modVehiculo.value = a.vehiculo || "";
                    modPatente.value = a.patente || "";
                    modObs.value = a.observaciones || "";

                    // Mostrar Modal
                    modal.style.display = "block";
                })
                .catch(err => console.error("Error al cargar alumno:", err));
        }
    });

    if (cerrar) cerrar.onclick = () => modal.style.display = "none";

    window.onclick = (e) => {
        if (e.target === modal) modal.style.display = "none";
    };
});
