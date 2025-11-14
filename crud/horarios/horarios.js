// funciones para manejo del listado y búsqueda en vivo
function initHorarios() {
  const search = document.getElementById("search");
  const tbody = document.getElementById("resultado-body");
  const noResults = document.getElementById("no-results");

  let lastQuery = "";

  async function fetchAndRender(q = "") {
    if (q === lastQuery) return;
    lastQuery = q;

    try {
      const res = await fetch("buscar_horarios.php?q=" + encodeURIComponent(q));
      const text = await res.text();
      tbody.innerHTML = text;
      noResults.classList.toggle("hidden", text.trim().length !== 0);
    } catch (e) {
      console.error("Error cargando horarios", e);
      tbody.innerHTML = '<tr><td colspan="6">Error al cargar datos.</td></tr>';
      noResults.classList.add("hidden");
    }
  }

  // carga inicial
  fetchAndRender("");

  // búsqueda con debounce
  let timer;
  search.addEventListener("input", (e) => {
    clearTimeout(timer);
    timer = setTimeout(() => {
      fetchAndRender(e.target.value.trim());
    }, 220);
  });
}
