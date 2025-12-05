const body = document.body;
const savedTheme = localStorage.getItem("theme");

if (savedTheme) {
  body.classList.remove("light", "dark");
  body.classList.add(savedTheme);
}

document.getElementById("toggleTheme").addEventListener("click", () => {
  body.classList.toggle("dark");

  const theme = body.classList.contains("dark") ? "dark" : "light";
  localStorage.setItem("theme", theme);
});
