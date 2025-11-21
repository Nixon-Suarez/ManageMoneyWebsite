// Inicializar el gráfico de finanzas
document.addEventListener('DOMContentLoaded', function() {
  const chartCanvas = document.getElementById('financeChart'); 
  const ctx = document.getElementById('financeChart').getContext('2d');

  // Datos para el gráfico
  const monthLabels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
  const incomeData = JSON.parse(chartCanvas.dataset.income);
  const expenseData = JSON.parse(chartCanvas.dataset.expense);
  
  const financeChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: monthLabels,
      datasets: [
        {
          label: 'Ingresos',
          data: incomeData,
          borderColor: '#10b981',
          backgroundColor: 'rgba(16, 185, 129, 0.1)',
          borderWidth: 2,
          tension: 0.2,
          fill: true
        },
        {
          label: 'Gastos',
          data: expenseData,
          borderColor: '#ef4444',
          backgroundColor: 'rgba(239, 68, 68, 0.1)',
          borderWidth: 2,
          tension: 0.2,
          fill: true
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        tooltip: {
          mode: 'index',
          intersect: false,
          callbacks: {
                label: function(context) {
              let label = context.dataset.label || '';
              if (label) {
                label += ': ';
              }
              if (context.parsed.y !== null) {
                label += new Intl.NumberFormat('es-ES', {
                  style: 'currency',
                  currency: 'COP'
                }).format(context.parsed.y);
              }
              return label;
            }
          }
        },
        legend: {
          position: 'top',
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return '$' + value;
            }
          }
        }
      }
    }
  });
});