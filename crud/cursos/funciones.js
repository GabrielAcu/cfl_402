document.addEventListener("DOMContentLoaded", () => {
  const buscar = document.getElementById("buscar");
  const resultado = document.getElementById("resultado");
  const spinner = document.getElementById("spinner");
  let ultimo = "";

  async function cargarCursos(q = "") {
    spinner.style.display = "block";
    try {
      const res = await fetch(`listar.php?buscar=${encodeURIComponent(q)}`);
      const html = await res.text();
      resultado.innerHTML = html;
    } catch (err) {
      resultado.innerHTML = "<p>Error al cargar los cursos.</p>";
      console.error(err);
    } finally {
      spinner.style.display = "none";
    }
  }

  // Cargar al abrir la página
  cargarCursos();

  // Búsqueda en vivo
  buscar.addEventListener("input", (e) => {
    const q = e.target.value.trim();
    if (q === ultimo) return;
    ultimo = q;
    cargarCursos(q);
  });
});
