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