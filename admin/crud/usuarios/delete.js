document.addEventListener("DOMContentLoaded", function () {
  const forms = document.querySelectorAll(".confirm-delete");

  forms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      const ok = confirm("¿Seguro que querés eliminar este alumno?");
      if (!ok) e.preventDefault();
    });
  });
});
