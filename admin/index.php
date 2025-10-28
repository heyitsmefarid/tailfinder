<!DOCTYPE html>
<html lang="en">

<?php include 'includes/head.php'; ?>

<style>
/* ✨ Dashboard visual polish */
.card {
  border-radius: 12px;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
 
  
}
.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 6px 16px rgba(0,0,0,0.15);
}

/* Summary cards layout */
.summary-row .card {
  min-height: 130px;
  
}
.summary-row h3 {
  font-size: 2rem;
  font-weight: 700;
}

/* Charts row layout */
#chartsRow {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: space-between;
}
.chart-card {
  flex: 1 1 calc(33.333% - 20px);
  min-width: 320px;
  display: flex;
  flex-direction: column;
  height: 400px;
}
.chart-card canvas {
  flex: 1;
}
@media (max-width: 992px) {
  .chart-card {
    flex: 1 1 100%;
  }
}
</style>

<body class="sb-nav-fixed">
<?php include 'includes/nav.php'; ?>

<div id="layoutSidenav">
  <?php include 'includes/sidebar.php'; ?>

  <div id="layoutSidenav_content">
    <main class="container-fluid px-4">
      <h1 class="mt-4 fw-bold text-primary">Dashboard</h1>
      <hr>

      <!-- 🟩 Summary Cards -->
      <div class="row text-center mb-4 summary-row g-3">
        <div class="col-md-4 col-lg-2">
          <div class="card bg-primary text-white shadow">
            <div class="card-body fw-semibold">Total Pets</div>
            <h3 id="totalPets" class="p-2">0</h3>
          </div>
        </div>

        <div class="col-md-4 col-lg-2">
          <div class="card bg-success text-white shadow">
            <div class="card-body fw-semibold">Adopters</div>
            <h3 id="totalAdopters" class="p-2">0</h3>
          </div>
        </div>

        <div class="col-md-4 col-lg-2">
          <div class="card bg-warning text-white shadow">
            <div class="card-body fw-semibold">Available</div>
            <h3 id="available" class="p-2">0</h3>
          </div>
        </div>

        <div class="col-md-4 col-lg-2">
          <div class="card bg-danger text-white shadow">
            <div class="card-body fw-semibold">Pending</div>
            <h3 id="pending" class="p-2">0</h3>
          </div>
        </div>

        <div class="col-md-4 col-lg-2">
          <div class="card bg-info text-white shadow">
            <div class="card-body fw-semibold">Approved</div>
            <h3 id="approved" class="p-2">0</h3>
          </div>
        </div>
      </div>

      <!-- 🐾 Pet Counts -->
      <div class="row text-center mb-4 g-3">
        <div class="col-md-4">
          <div class="card border-primary">
            <div class="card-body fw-semibold">Dogs</div>
            <h3 id="dogs" class="p-2 text-primary">0</h3>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card border-success">
            <div class="card-body fw-semibold">Cats</div>
            <h3 id="cats" class="p-2 text-success">0</h3>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card border-secondary">
            <div class="card-body fw-semibold">Others</div>
            <h3 id="others" class="p-2 text-secondary">0</h3>
          </div>
        </div>
      </div>

      <!-- 📊 Charts Row (flex aligned) -->
      <div id="chartsRow" class="mb-4">
        <div class="chart-card card">
          <div class="card-header fw-semibold text-primary">Pets Adopted Per Month</div>
          <div class="card-body"><canvas id="barChart"></canvas></div>
        </div>

        <div class="chart-card card">
          <div class="card-header fw-semibold text-success">Pet Status Distribution</div>
          <div class="card-body"><canvas id="pieChart"></canvas></div>
        </div>

        <div class="chart-card card">
          <div class="card-header fw-semibold text-warning">Adoption Requests Over Time</div>
          <div class="card-body"><canvas id="lineChart"></canvas></div>
        </div>
      </div>

      <!-- 🧾 Table -->
      <div class="card mb-4">
        <div class="card-header fw-semibold text-primary">Recent Activities</div>
        <div class="card-body table-responsive">
          <table id="recentTable" class="table table-striped align-middle">
            <thead class="table-primary">
              <tr>
                <th>Pet Name</th>
                <th>Type</th>
                <th>Pet Status</th>
                <th>Adopter</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>

    </main>
    <?php include 'includes/footer.php'; ?>
  </div>
</div>

<!-- ✅ Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="dashboard.js"></script>
</body>
</html>
