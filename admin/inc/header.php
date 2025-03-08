<div class="container-fluid bg-dark p-3 d-flex align-items-center justify-content-between sticky-top">
    <h3 class="'mb-0 h-font">MYBUS Website</h3>
    <a href="logout.php" class="btn btn-light btn-sm">Log Out</a>
</div>

<div class="col-lg-2 bg-dark border-top border-3 border-secondary" id="dashboard-menu">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid flex-lg-column align-items-stretch">

            <h4 class="mt-2 h-font">Admin Panel</h4>
            <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#adminDropdown" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse flex-column mt-2 align-items-stretch" id="adminDropdown">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="bus.php">Buses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="bookings.php">Bookings</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>

