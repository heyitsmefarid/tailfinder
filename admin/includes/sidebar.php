<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="index.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <a class="nav-link" href="manage_users.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Users
                </a>
                <a class="nav-link" href="pet.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-paw"></i></div>
                    Pets
                </a>
                <a class="nav-link" href="adoption_request.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
                    Adoption Requests
                </a>
                <a class="nav-link" href="admin_messages.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-comments"></i></div>
                    Inquiries
                </a>
                <!-- Collapsible Reports Menu -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseReports" aria-expanded="false" aria-controls="collapseReports">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-bar"></i></div>
                    Reports
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseReports" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="report_pets.php">Pets Report</a>
                        <a class="nav-link" href="report_users.php">Users Report</a>
                        <a class="nav-link" href="report_adoptions.php">Adoptions Report</a>
                         <a class="nav-link" href="report_inquiry.php">Inquiry Report</a>
                    </nav>
                </div>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            Admin
        </div>
    </nav>
</div>
