const inputBuscar = document.getElementById("buscar");
const divResultado = document.getElementById("resultado");
const spinner = document.getElementById("spinner");

// Debounce: espera n ms tras el último tecleo
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

// Escuchar cambios con debounce 300ms
inputBuscar.addEventListener(
  "input",
  debounce((e) => {
    const texto = e.target.value.trim();
    buscarInscripciones(texto);
  }, 300)
);

// ==== Modal Nueva Inscripción ====
document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("modal-inscripcion");
  const abrirBtn = document.querySelector(".btn-agregar");
  const cerrarBtn = document.getElementById("cerrar-modal");
  const cancelarBtn = document.getElementById("cancelar-modal");

  if (abrirBtn && modal) {
    abrirBtn.addEventListener("click", (e) => {
      e.preventDefault();
      modal.style.display = "block";
    });
  }

  [cerrarBtn, cancelarBtn].forEach((btn) => {
    if (btn) {
      btn.addEventListener("click", () => {
        modal.style.display = "none";
      });
    }
  });

  window.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.style.display = "none";
    }
  });
});

// 🪟 Mostrar modal con datos
const modal = document.getElementById("modal-detalle");
const cerrar = document.getElementById("cerrar-detalle");

document.querySelectorAll(".btn-ver").forEach((btn) => {
  btn.addEventListener("click", () => {
    const fila = btn.closest("tr");
    document.getElementById("detalle-id").textContent =
      fila.querySelector(".col-id").textContent;
    document.getElementById("detalle-alumno").textContent =
      fila.querySelector(".col-alumno").textContent;
    document.getElementById("detalle-curso").textContent =
      fila.querySelector(".col-curso").textContent;
    document.getElementById("detalle-fecha").textContent =
      fila.querySelector(".col-fecha").textContent;
    document.getElementById("detalle-observaciones").textContent =
      fila.querySelector(".col-obs").textContent;

    modal.style.display = "block";
  });
});

cerrar.addEventListener("click", () => {
  modal.style.display = "none";
});
window.addEventListener("click", (e) => {
  if (e.target === modal) modal.style.display = "none";
});
