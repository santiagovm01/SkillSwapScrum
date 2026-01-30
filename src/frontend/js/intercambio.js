document.getElementById("form-publicar").addEventListener("submit", async (e) => {
  e.preventDefault();

  const titulo = document.getElementById("titulo");
  const descripcion = document.getElementById("descripcion");

  const errorTitulo = document.getElementById("error-titulo");
  const errorDescripcion = document.getElementById("error-descripcion");

  let valido = true;

  // RESET visual
  [titulo, descripcion].forEach(campo => {
    campo.classList.remove("input-error", "input-ok");
  });
  errorTitulo.textContent = "";
  errorDescripcion.textContent = "";

  // VALIDACIONES
  if (titulo.value.trim() === "") {
    titulo.classList.add("input-error");
    errorTitulo.textContent = "El título es obligatorio";
    valido = false;
  } else {
    titulo.classList.add("input-ok");
  }

  if (descripcion.value.trim().length < 10) {
    descripcion.classList.add("input-error");
    errorDescripcion.textContent = "Mínimo 10 caracteres";
    valido = false;
  } else {
    descripcion.classList.add("input-ok");
  }

  if (!valido) return;

  // DATOS A ENVIAR
  const datos = {
    titulo: titulo.value.trim(),
    descripcion: descripcion.value.trim()
  };

  try {
    const response = await fetch("/backend/publicar.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify(datos)
    });

    const resultado = await response.json();

    if (resultado.ok) {
      alert("✅ Publicado correctamente");
      e.target.reset();
    } else {
      alert("❌ Error: " + resultado.mensaje);
    }

  } catch (error) {
    alert("❌ Error de conexión con el servidor");
  }
});
