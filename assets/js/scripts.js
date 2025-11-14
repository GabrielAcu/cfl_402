// ====== BUSCADOR DINÁMICO DE CURSOS ======
document.addEventListener("DOMContentLoaded", function () {
  const columna = document.getElementById("columna");
  const texto = document.getElementById("texto");
  const resultado = document.getElementById("resultado");

  function buscar() {
    fetch(`buscar_cursos.php?columna=${columna.value}&texto=${texto.value}`)
      .then((res) => res.text())
      .then((data) => {
        resultado.innerHTML = data;
      });
  }

  // Buscar automáticamente mientras se escribe
  texto.addEventListener("keyup", buscar);
  columna.addEventListener("change", buscar);

  // Cargar lista completa al inicio
  buscar();
});
