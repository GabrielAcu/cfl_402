// ==========================
// 🧭 FUNCIONES DE BÚSQUEDA
// ==========================
const inputBuscar = document.getElementById("buscar");
const divResultado =
  document.getElementById("resultado") ||
  document.querySelector(".tabla-responsive");
const spinner = document.getElementById("spinner");

// --- Debounce ---
function debounce(fn, delay) {
  let timer = null;
  return function (...args) {
    clearTimeout(timer);
    timer = setTimeout(() => fn.apply(this, args), delay);
  };
}

function showSpinner() {
  if (spinner) spinner.style.display = "block";
}
function hideSpinner() {
  if (spinner) spinner.style.display = "none";
}

function buscarInscripciones(texto = "") {
  showSpinner();
  fetch(`buscar_inscripciones.php?texto=${encodeURIComponent(texto)}`)
    .then((res) => {
      if (!res.ok) throw new Error("Respuesta no OK");
      return res.text();
    })
    .then((data) => {
      divResultado.innerHTML = data;
      // 🔁 Reasignar eventos de botones después de actualizar la tabla
      inicializarModalDetalle();
    })
    .catch((err) => {
      console.error(err);
      divResultado.innerHTML = "<p>Error al cargar los datos.</p>";
    })
    .finally(() => {
      hideSpinner();
    });
}

// Cargar todos al iniciar
buscarInscripciones();

// Escuchar cambios con debounce
if (inputBuscar) {
  inputBuscar.addEventListener(
    "input",
    debounce((e) => {
      const texto = e.target.value.trim();
      buscarInscripciones(texto);
    }, 300)
  );
}

// ==========================
// 🪟 MODAL DETALLE INSCRIPCIÓN
// ==========================
function inicializarModalDetalle() {
  const modal = document.getElementById("modal-detalle");
  const cerrar = document.getElementById("cerrar-detalle");

  if (!modal || !cerrar) return;

  document.querySelectorAll(".btn-ver").forEach((btn) => {
    btn.addEventListener("click", () => {
      const fila = btn.closest("tr");
      if (!fila) return;

      document.getElementById("detalle-id").textContent =
        fila.querySelector(".col-id")?.textContent || "";
      document.getElementById("detalle-alumno").textContent =
        fila.querySelector(".col-alumno")?.textContent || "";
      document.getElementById("detalle-curso").textContent =
        fila.querySelector(".col-curso")?.textContent || "";
      document.getElementById("detalle-fecha").textContent =
        fila.querySelector(".col-fecha")?.textContent || "";
      document.getElementById("detalle-observaciones").textContent =
        fila.querySelector(".col-obs")?.textContent || "";

      modal.style.display = "block";
    });
  });

  cerrar.addEventListener("click", () => {
    modal.style.display = "none";
  });

  window.addEventListener("click", (e) => {
    if (e.target === modal) modal.style.display = "none";
  });
}

// Llamamos una vez al cargar la página
document.addEventListener("DOMContentLoaded", inicializarModalDetalle);
