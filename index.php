<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>MYBUS</title>
    <?php require('inc/links.php') ?>

    <style>
        .availability-form {
            margin-top: -50px;
            z-index: 2;
            position: relative;
        }

        @media screen and (max-width:575px) {
            .availability-form {
                margin-top: 25px;
                padding: 0 35px;
            }
        }
    </style>
</head>

<body class='bg-light'>

    <?php
    require('inc/header.php');
    $today = date('Y-m-d');
    ?>

    <!-- Carousel -->

    <div class='container-fluid px-lg-4 mt-4'>
        <div class='swiper swiper-container'>
            <div class='swiper-wrapper'>
                <div class='swiper-slide'>
                    <img src='images/carousel/bus.jpg' class='w-100 d-block' />
                </div>
                <div class='swiper-slide'>
                    <img src='images/carousel/bus.jpg' class='w-100 d-block' />
                </div>
                <div class='swiper-slide'>
                    <img src='images/carousel/bus.jpg' class='w-100 d-block' />
                </div>
                <!-- Add more images as needed -->
            </div>
        </div>
    </div>

    <!-- Check Availability Form -->

    <div class='container availability-form'>
        <div class='row'>
            <div class='col-lg-12 bg-white p-4 rounded shadow'>
                <h5 class='mb-4 h-font'>Check Booking Availability</h5>
                <form action='bus.php'>
                    <div class='row align-items-end'>
                        <div class='col-lg-3 mb-3'>
                            <label for='form-label' style='font-weight:500;'>Source</label>
                            <input type='text' class='form-control shadow-none' name="source" required value="gandhinagar">
                        </div>
                        <div class='col-lg-3 mb-3'>
                            <label for='form-label' style='font-weight:500;'>Destination</label>
                            <input type='text' class='form-control shadow-none' name="destination" required
                                value="morbi">
                        </div>
                        <div class='col-lg-3 mb-3'>
                            <label for='form-label' style='font-weight:500;'>Date</label>
                            <input type='date' min="<?php echo $today; ?>" class='form-control shadow-none' name="date"
                                required value="<?php echo $today; ?>">
                        </div>
                        <div class='col-lg-2 mb-3'>
                            <label for='form-label' style='font-weight:500;'>No. of Passengers</label>
                            <input type='number' min="1" max="9" class='form-control shadow-none' name="passengers"
                                required value="1">
                        </div>
                        <input type="hidden" name="check_availability">
                        <div class='col-lg-1 mb-lg-3 mt-2'>
                            <button type='submit' class='btn text-white shadow-none custom-bg'>Check</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bus Facilities -->

    <h2 id="facilities" class='mt-5 pt-4 mb-4 text-center fw-bold h-font'>OUR BUS FACILITIES</h2>

    <div class='container'>
        <div class='row justify-content-evenly px-lg-0 px-md-0 px-5'>
            <div class='col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3'>
                <img src='images/facilities/recliner.png' alt='' width='80px'>
                <h5 class='mt-3'>Reclining Seats</h5>
            </div>
            <div class='col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3'>
                <img src='images/facilities/IMG_43553.svg' alt='' width='80px'>
                <h5 class='mt-3'>Free WiFi</h5>
            </div>
            <div class='col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3'>
                <img src='images/facilities/IMG_49949.svg' alt='' width='80px'>
                <h5 class='mt-3'>AC Comfort</h5>
            </div>
        </div>
    </div>

    <!-- Testimonials -->

    <h2 class='mt-5 pt-4 mb-4 text-center fw-bold h-font'>CUSTOMER TESTIMONIALS</h2>

    <div class='container mt-5'>
        <div class='swiper swiper-testimonials'>
            <div class='swiper-wrapper mb-5'>
                <div class='swiper-slide bg-white mb-3 px-4'>
                    <div class='profile d-flex align-items-center p-4'>
                        <i class="bi bi-person-circle"></i>
                        <h6 class='m-0 ms-2'>Jane Doe</h6>
                    </div>
                    <p>
                        My journey with MYBUS was amazing! Comfortable seating, fast WiFi, and smooth travel. I’ll
                        definitely be using this service again.
                    </p>
                    <div class='rating mb-3'>
                        <i class='bi bi-star-fill text-warning'></i>
                        <i class='bi bi-star-fill text-warning'></i>
                        <i class='bi bi-star-fill text-warning'></i>
                        <i class='bi bi-star-fill text-warning'></i>
                        <i class='bi bi-star-fill text-warning'></i>
                    </div>
                </div>

                <div class='swiper-slide bg-white mb-3 px-4'>
                    <div class='profile d-flex align-items-center p-4'>
                        <i class="bi bi-person-circle"></i>
                        <h6 class='m-0 ms-2'>John Smith</h6>
                    </div>
                    <p>
                        I had an excellent experience with MYBUS. The bus was on time, the staff was polite, and the
                        ride was very comfortable. Highly recommended!
                    </p>
                    <div class='rating mb-3'>
                        <i class='bi bi-star-fill text-warning'></i>
                        <i class='bi bi-star-fill text-warning'></i>
                        <i class='bi bi-star-fill text-warning'></i>
                        <i class='bi bi-star-fill text-warning'></i>
                    </div>
                </div>
                <div class='swiper-slide bg-white mb-3 px-4'>
                    <div class='profile d-flex align-items-center p-4'>
                        <i class="bi bi-person-circle"></i>
                        <h6 class='m-0 ms-2'>Michael Johnson</h6>
                    </div>
                    <p>
                        My experience with MYBUS was fantastic! The booking process was seamless, the bus was punctual,
                        and the journey was comfortable.
                    </p>
                    <div class='rating mb-3'>
                        <i class='bi bi-star-fill text-warning'></i>
                        <i class='bi bi-star-fill text-warning'></i>
                        <i class='bi bi-star-fill text-warning'></i>
                        <i class='bi bi-star-fill text-warning'></i>
                        <i class='bi bi-star-fill text-warning'></i>
                    </div>
                </div>
                <div class='swiper-slide bg-white mb-3 px-4'>
                    <div class='profile d-flex align-items-center p-4'>
                        <i class="bi bi-person-circle"></i>
                        <h6 class='m-0 ms-2'>Sarah Williams</h6>
                    </div>
                    <p>
                    Traveling with MYBUS was a pleasure! The bus was spacious, clean, and the staff ensured a comfortable trip.
                    </p>
                    <div class='rating mb-3'>
                        <i class='bi bi-star-fill text-warning'></i>
                        <i class='bi bi-star-fill text-warning'></i>
                        <i class='bi bi-star-fill text-warning'></i>
                        <i class='bi bi-star-fill text-warning'></i>
                    </div>
                </div>


                <!-- Add more testimonials as needed -->
            </div>
            <div class='swiper-pagination'></div>
        </div>
    </div>

    <!-- Contact Us Section -->

    <h2 class='mt-5 pt-4 mb-4 text-center fw-bold h-font' id="contactus">CONTACT US</h2>

    <div class='container'>
        <div class='row'>
            <div class=''>
                <div class='bg-white shadow-sm p-4 mx-5'>
                    <h4 class='fw-bold'>Reach Out to Us</h4>
                    <p class='mt-3'>We'd love to hear from you! Whether you have a query or feedback, contact us for
                        more information or assistance.</p>

                    <form action=''>
                        <div class='mb-3'>
                            <label for='exampleFormControlInput1' class='form-label'>Email</label>
                            <input type='email' class='form-control' id='exampleFormControlInput1'
                                placeholder="your-email@example.com">
                        </div>
                        <div class='mb-3'>
                            <label for='exampleFormControlInput1' class='form-label'>Name</label>
                            <input type='text' class='form-control' id='exampleFormControlInput1'
                                placeholder="Your Name">
                        </div>
                        <div class='mb-3'>
                            <label for='exampleFormControlTextarea1' class='form-label'>Message</label>
                            <textarea class='form-control' id='exampleFormControlTextarea1' rows='3'
                                placeholder="Your message here..."></textarea>
                        </div>
                        <div class='text-end'>
                            <button type='submit' class='btn btn-sm custom-bg text-white'>Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/footer.php') ?>

    <script src='https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js'></script>
    <script>
        var swiper = new Swiper(".swiper-container", {
            spaceBetween: 30,
            effect: "fade",
            loop: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            }
        });

        var swiper = new Swiper(".swiper-testimonials", {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: "auto",
            slidesPerView: "4",
            coverflowEffect: {
                rotate: 50,
                stretch: 0,
                depth: 100,
                modifier: 1,
                slideShadows: false,
            },
            pagination: {
                el: ".swiper-pagination",
            },
            loop: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                },
                640: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 3,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
        });
    </script>

</body>

</html>