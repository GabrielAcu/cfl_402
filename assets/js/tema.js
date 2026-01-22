const body = document.body;
// Cambiamos la key a 'theme_v2' para ignorar configuraciones viejas y forzar el modo oscuro por defecto
const savedTheme = localStorage.getItem("theme_v2");

// 1. Cargar Preferencia
if (savedTheme === "light") {
  body.classList.add("light-mode");
  updateIcon("light");
} else {
  // Por defecto ejecutamos modo oscuro
  body.classList.remove("light-mode");
  updateIcon("dark");
}

// 2. Evento Click
if (document.getElementById("toggleTheme")) {
  document.getElementById("toggleTheme").addEventListener("click", () => {
    body.classList.toggle("light-mode");

    const isLight = body.classList.contains("light-mode");
    localStorage.setItem("theme_v2", isLight ? "light" : "dark");
    updateIcon(isLight ? "light" : "dark");
  });
}

// 3. Función Iconos (Sol/Luna)
function updateIcon(mode) {
  const themeBtn = document.getElementById("toggleTheme");
  if (!themeBtn) return;

  // Iconos simples por ahora, se pueden cambiar por SVGs
  themeBtn.innerHTML = mode === "light" ? "☀️" : "🌙";
}
