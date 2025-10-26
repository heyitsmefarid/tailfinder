<!DOCTYPE html>
<html lang="en">

<?php include 'includes/head.php'; ?>

<body class="sb-nav-fixed">
<?php include 'includes/nav.php'; ?>

<div id="layoutSidenav">
    <?php include 'includes/sidebar.php'; ?>

    <div id="layoutSidenav_content">
        <main class="container-fluid px-4">
            <h1 class="mt-4">Dashboard</h1>
            <hr>

            <!-- 🟩 Summary Cards -->
            <div class="row text-center mb-4">
                <div class="col-md-3"><div class="card bg-primary text-white shadow"><div class="card-body">Total Pets</div><h3 id="totalPets" class="p-2">0</h3></div></div>
                <div class="col-md-3"><div class="card bg-success text-white shadow"><div class="card-body">Adopters</div><h3 id="totalAdopters" class="p-2">0</h3></div></div>
                <div class="col-md-3"><div class="card bg-warning text-white shadow"><div class="card-body">Pending</div><h3 id="pending" class="p-2">0</h3></div></div>
                <div class="col-md-3"><div class="card bg-info text-white shadow"><div class="card-body">Approved</div><h3 id="approved" class="p-2">0</h3></div></div>
            </div>

            <!-- 🐾 Pet Counts -->
            <div class="row text-center mb-4">
                <div class="col-md-4"><div class="card border-primary"><div class="card-body">Dogs</div><h3 id="dogs" class="p-2">0</h3></div></div>
                <div class="col-md-4"><div class="card border-success"><div class="card-body">Cats</div><h3 id="cats" class="p-2">0</h3></div></div>
                <div class="col-md-4"><div class="card border-secondary"><div class="card-body">Others</div><h3 id="others" class="p-2">0</h3></div></div>
            </div>

            <!-- 📊 Charts -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Pets Adopted Per Month</div>
                        <div class="card-body"><canvas id="barChart" height="200"></canvas></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Pet Status Distribution</div>
                        <div class="card-body"><canvas id="pieChart" height="200"></canvas></div>
                    </div>
                </div>
            </div>

            <!-- 📈 Line Chart -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Adoption Requests Over Time</div>
                        <div class="card-body"><canvas id="lineChart" height="200"></canvas></div>
                    </div>
                </div>
            </div>

            <!-- 🧾 Table -->
            <div class="card mb-4">
                <div class="card-header">Recent Activities</div>
                <div class="card-body">
                    <table id="recentTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Pet Name</th>
                                <th>Type</th>
                                <th>Status</th>
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
