const titulo = document.getElementById("titulo");
const error = document.getElementById("tituloError");
const btn = document.getElementById("publishBtn");

titulo.addEventListener("input", () => {
  if (titulo.value.length < 5) {
    titulo.classList.add("is-invalid");
    btn.disabled = true;
  } else {
    titulo.classList.remove("is-invalid");
    titulo.classList.add("is-valid");
    btn.disabled = false;
  }
});
