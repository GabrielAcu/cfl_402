fetch(`procesar_modificacion.php?respuesta=${respuesta}`)
            .then(r => r.json())
            .then(data => {

                const a = data.respuesta;   // ← IMPORTANTE
                console.log("USUARIO:", a);
                alert($respuesta);
                // Construimos el formulario
                let html = `

                `;

                contenido.innerHTML = html;
                modalVer.style.display = "block"; // ← AQUÍ SE ABRE EL MODAL
            })

            .catch(e => console.error("ERROR FETCH:", e));


// Cerrar modal
cerrarVer.onclick = () => modalVer.style.display = "none";

window.onclick = (e) => {
    if (e.target === modalVer) modalVer.style.display = "none";
};
