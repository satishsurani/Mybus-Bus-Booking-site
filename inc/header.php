<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white px-lg-3 py-lg-2 shadow-sm sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand me-5 fw-bold fs-3 h-font chover" href="index.php">MYBUS</a>
        <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-bold fs-lg-5">
                <li class="nav-item">
                    <a class="nav-link h-font me-2 chover" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link h-font me-2 chover" href="bus.php">buses</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link h-font me-2 chover" href="bookings.php">Bookings</a>
                </li>
            </ul>
            <?php
            if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
                echo <<<data
                    <form method="POST" action="logout.php">
                        <button type="submit" class="btn btn-outline-dark shadow-none me-lg-3 me-2 h-font">Logout</button>
                    </form>
                data;
            } else {
                echo <<<data
                    <div class="d-flex">
                        <button class="btn btn-outline-dark shadow-none me-lg-3 me-2 h-font" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
                        <button class="btn btn-outline-dark shadow-none h-font" data-bs-toggle="modal" data-bs-target="#registerModal">Register</button>
                    </div>
                data;
            }
            ?>
        </div>
    </div>
</nav>

<div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="login_form" method="POST">
                <div class="modal-header">
                    <h1 class="modal-title fs-5"><i class="bi bi-person-circle fs-3 me-2"></i>User Login</h1>
                    <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control shadow-none" required>
                    </div>
                    <div class="mb-4">
                        <label for="pass" class="form-label">Password</label>
                        <input type="password" id="pass" name="pass" class="form-control shadow-none" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-dark shadow-none">Login</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="registerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="register_form" method="POST">
                <div class="modal-header">
                    <h1 class="modal-title fs-5"><i class="bi bi-person-plus fs-3 me-2"></i>User Registration</h1>
                    <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" name="name" class="form-control shadow-none" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="register_email" name="email" class="form-control shadow-none" required>
                    </div>
                    <div class="mb-3">
                        <label for="phonenum" class="form-label">Phone Number</label>
                        <input type="tel" id="phonenum" name="phonenum" class="form-control shadow-none" required>
                    </div>
                    <div class="mb-3">
                        <label for="pass" class="form-label">Password</label>
                        <input type="password" id="register_pass" name="pass" class="form-control shadow-none" required>
                    </div>
                    <div class="mb-3">
                        <label for="cpass" class="form-label">Confirm Password</label>
                        <input type="password" id="cpass" name="cpass" class="form-control shadow-none" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-dark shadow-none">Register</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

