document.addEventListener('DOMContentLoaded', () => {
  fetch('data.php')
    .then(res => res.json())
    .then(data => {
      console.log('✅ Data loaded:', data);

      // Summary
      document.getElementById('totalPets').textContent = data.summary.totalPets;
      document.getElementById('totalAdopters').textContent = data.summary.totalAdopters;
      document.getElementById('available').textContent = data.summary.available;
      document.getElementById('pending').textContent = data.summary.pending;
      document.getElementById('approved').textContent = data.summary.approved;
      document.getElementById('dogs').textContent = data.summary.dogs;
      document.getElementById('cats').textContent = data.summary.cats;
      document.getElementById('others').textContent = data.summary.others;

      // Bar Chart
      const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
      const barData = Array(12).fill(0);
      data.bar.forEach(row => barData[row.month - 1] = row.total);
      new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: { labels: months, datasets: [{ label: 'Adopted Pets', data: barData, backgroundColor: '#3b82f6', borderRadius: 5 }] },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
      });

      // Pie Chart
      new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
          labels: data.pie.map(p => p.status),
          datasets: [{ data: data.pie.map(p => p.total), backgroundColor: ['#22c55e','#3b82f6','#f87171'] }]
        }
      });

      // Line Chart
      new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
          labels: data.line.map(d => d.date),
          datasets: [{ label: 'Requests', data: data.line.map(d => d.total), borderColor: '#16a34a', tension: 0.3 }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
      });

      // Table
      const tbody = document.querySelector('#recentTable tbody');
      tbody.innerHTML = '';
      data.table.forEach(row => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${row.pet_name}</td>
          <td>${row.type}</td>
          <td>${row.pet_status}</td>
          <td>${row.first_name} ${row.last_name}</td>
          <td>${row.application_date}</td>
        `;
        tbody.appendChild(tr);
      });
    })
    .catch(err => console.error('❌ Error loading data:', err));
});
