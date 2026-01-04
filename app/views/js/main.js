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
    //* Categorias Modal
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
    if (e.target.classList.contains("btnOpenEditar")) {

        document.getElementById("modalCategoriaLabel").textContent = "Editar Categoría";
        document.getElementById("btnSubmitCategoria").textContent = "Actualizar";

        document.getElementById("modulo_categoria").value = "actualizar_categoria";

        // Rellenar datos
        document.getElementById("categoria_id").value = e.target.dataset.id;
        document.getElementById("nombre_categoria").value = e.target.dataset.nombre;
        document.getElementById("estado_categoria").value = e.target.dataset.estado;
    }

    //* Gastos modal
    // ---- MODO REGISTRAR ----
    if (e.target.id === "btnOpenRegistrarGastos") {

        document.getElementById("modalGastosLabel").textContent = "Registrar Gastos";
        document.getElementById("btnSubmitGastos").textContent = "Registrar";

        document.getElementById("modulo_gastos").value = "registrar_gastos";
        document.getElementById("gastos_id").value = "";

        document.getElementById("descripcion_gastos").value = "";
        document.getElementById("valor_gastos").value = "";
        document.getElementById("mes_gastos").value = "";
        document.getElementById("anio_gastos").value = "";
        document.getElementById("estado_gastos").value = "";
        document.getElementById("categoria_gastos").value = "";
        document.getElementById("gasto_documento").value = "";
        document.getElementById("descargar_gasto").setAttribute("hidden", true);
    }
    if (e.target.classList.contains("btnOpenEditarGastos")) {

        document.getElementById("modalGastosLabel").textContent = "Editar Gastos";
        document.getElementById("btnSubmitGastos").textContent = "Actualizar";

        document.getElementById("modulo_gastos").value = "actualizar_gastos";

        document.getElementById("gastos_id").value = e.target.dataset.id;
        document.getElementById("descripcion_gastos").value = e.target.dataset.descripcion;
        document.getElementById("valor_gastos").value = e.target.dataset.valor;
        document.getElementById("mes_gastos").value = e.target.dataset.mes;
        document.getElementById("anio_gastos").value = e.target.dataset.anio;
        document.getElementById("estado_gastos").value = e.target.dataset.estado;
        document.getElementById("categoria_gastos").value = e.target.dataset.categoria;
        if(e.target.dataset.adjunto){
            const descargarLink = document.getElementById("descargar_gasto");
            descargarLink.href = e.target.dataset.adjunto;
            descargarLink.removeAttribute("hidden");
        }
    }
});


