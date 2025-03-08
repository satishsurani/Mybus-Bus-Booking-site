<!-- Footer  -->
<div class="container-fluid bg-white mt-5">
    <div class="row">
        <div class="col-lg-4 p-4">
            <h3 class="h-font fw-bold fs-3 mb-2">MYBUS</h3>
            <p>
                At MYBUS, we offer a seamless travel experience with top-notch amenities and exceptional customer service. Whether for business or leisure, we ensure your journey is comfortable and hassle-free.
            </p>
        </div>
        <div class="col-lg-4 p-4">
            <h5 class="mb-3 h-font">Quick Links</h5>
            <a href="#" class="d-inline-block mb-2 text-dark text-decoration-none">Home</a><br>
            <a href="bus.php" class="d-inline-block mb-2 text-dark text-decoration-none">Bus Booking</a><br>
            <a href="#facilities" class="d-inline-block mb-2 text-dark text-decoration-none">Facilities</a><br>
            <a href="#contactus" class="d-inline-block mb-2 text-dark text-decoration-none">Contact Us</a><br>
        </div>
        <div class="col-lg-4 p-4">
            <h5 class="mb-3 h-font">Follow Us</h5>
            <a href="#" class="d-inline-block mb-3 text-dark text-decoration-none"><i class="bi bi-twitter me-1"></i>Twitter</a><br>
            <a href="#" class="d-inline-block mb-3 text-dark text-decoration-none"><i class="bi bi-facebook me-1"></i>Facebook</a><br>
            <a href="#" class="d-inline-block mb-3 text-dark text-decoration-none"><i class="bi bi-instagram me-1"></i>Instagram</a><br>
        </div>
    </div>
</div>

<h6 class="text-center bg-dark p-3 m-0 h-font">Designed and Developed by MYBUS</h6>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

    <script src='https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js'></script>

    <script>
    function showAlert(type, message) {
        const alertType = type === 'success' ? 'success' : 'danger';

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${alertType} alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow`;
        alertDiv.style.zIndex = "1055";
        alertDiv.innerHTML = `
            <strong>${alertType === 'success' ? 'Success' : 'Error'}:</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.body.appendChild(alertDiv);

        setTimeout(() => alertDiv.remove(), 3000);
    }

    document.getElementById('register_form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('ajax/register.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.text())
            .then(data => {
                console.log('Register Response:', data); 

                if (data.trim() === 'success') {
                    showAlert('success', 'Registration successful!');

                    const modalElement = document.getElementById('registerModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    modalInstance.hide();

                    let fileurl = window.location.href.split('/').pop().split('?').shift();

                    if(fileurl == 'bus_details.php'){
                        window.location = window.location.href;
                    }else {
                        window.location = window.location.pathname;
                    }

                    this.reset();
                } else {
                    showAlert('danger', data);
                }
            })
            .catch(error => {
                console.error('Error during registration:', error);
                showAlert('danger', 'An error occurred. Please try again.');
            });
    });

    document.getElementById('login_form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('ajax/login.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.text())
            .then(data => {
                console.log('Login Response:', data); 

                if (data.trim() === 'success') {
                    showAlert('success', 'Login successful!');

                    const modalElement = document.getElementById('loginModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    modalInstance.hide();

                    let fileurl = window.location.href.split('/').pop().split('?').shift();

                    if(fileurl == 'bus_details.php'){
                        window.location =window.location.href;
                    }else {
                        window.location = window.location.pathname;
                    }

                    this.reset();
                } else {
                    showAlert('danger', data);
                }
            })
            .catch(error => {
                showAlert('danger', 'An error occurred. Please try again.');
            });
    });

    function checkLoginToBook(status, bus_id) 
    {
        if (status) {
            window.location.href = 'confirm_booking.php?id=' + bus_id;
        } else {
            showAlert('danger', 'Please log in to book a bus!');
        }
    }

</script>
    