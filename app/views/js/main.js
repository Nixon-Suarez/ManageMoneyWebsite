document.addEventListener('DOMContentLoaded', () => {

  const boton = document.getElementById('btnToggle');
  const sidebar = document.querySelector('#sidebar');
  const content = document.querySelector('.content');

  if (boton && sidebar && content) {
      boton.addEventListener('click', () => {
          sidebar.classList.toggle('sidebar-collapsed');
          content.classList.toggle('content-collapsed');
      });
  }
});

document.addEventListener("click", function(e) {

    // ---- MODO REGISTRAR ----
    if (e.target.id === "btnOpenRegistrar") {

        document.getElementById("modalCategoriaLabel").textContent = "Registrar Categoría";
        document.getElementById("btnSubmitCategoria").textContent = "Registrar";

        document.getElementById("modulo_categoria").value = "registrar_categoria";
        document.getElementById("categoria_id").value = "";

        document.getElementById("nombre_categoria").value = "";
        document.getElementById("estado_categoria").value = "activo";
    }

    // ---- MODO EDITAR ----
    if (e.target.id === "btnOpenEditar") {

        document.getElementById("modalCategoriaLabel").textContent = "Editar Categoría";
        document.getElementById("btnSubmitCategoria").textContent = "Actualizar";

        document.getElementById("modulo_categoria").value = "actualizar_categoria";

        // Rellenar datos
        document.getElementById("categoria_id").value = e.target.dataset.id;
        document.getElementById("nombre_categoria").value = e.target.dataset.nombre;
        document.getElementById("estado_categoria").value = e.target.dataset.estado;
    }
});
