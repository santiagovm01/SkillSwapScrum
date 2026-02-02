function buscarHabilidades() {
    const texto = document.getElementById("texto").value;
    const categoria = document.getElementById("categoria").value;

    fetch(`../backend/usuario/buscar.php?texto=${texto}&categoria=${categoria}`)
        .then(res => res.json())
        .then(data => {
            let html = "";
            data.forEach(h => {
                html += `
                    <div class="habilidad">
                        <h3>${h.titulo}</h3>
                        <p>${h.descripcion}</p>
                        <p><strong>Categoría:</strong> ${h.categoria}</p>
                        <p><strong>Nivel:</strong> ${h.nivel}</p>
                        <p><strong>Usuario:</strong> ${h.usuario}</p>
                    </div>
                `;
            });
            document.getElementById("resultado").innerHTML = html;
        });
}
function solicitarIntercambio(idHabilidad) {
    fetch("../backend/usuario/intercambio.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `accion=solicitar&id_habilidad=${idHabilidad}`
    })
    .then(res => res.text())
    .then(msg => alert(msg));
}
