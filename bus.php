<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYBUS - Buses</title>
    <?php require('inc/links.php') ?>
</head>

<body class="bg-light">

    <?php
    require('inc/header.php');

    $source_default = '';
    $destination_default = '';
    $date_default = '';
    $passengers_default = '';

    if (isset($_GET['check_availability'])) {
        $frm_data = filteration($_GET);
        $source_default = isset($frm_data['source']) ? $frm_data['source'] : '';
        $destination_default = isset($frm_data['destination']) ? $frm_data['destination'] : '';
        $date_default = isset($frm_data['date']) ? $frm_data['date'] : '';
        $passengers_default = isset($frm_data['passengers']) ? $frm_data['passengers'] : '';
        $_SESSION['user'] = [
            "passengers" => $passengers_default,
            "date" => $date_default
        ];
    }

    

    ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">OUR BUSES</h2>
        <div class="h-line bg-dark"></div>
    </div>

    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-3 col-md-12 mb-4 mb-lg-0 ps-4">
                <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow">
                    <div class="container-fluid flex-lg-column align-items-stretch">

                        <h4 class="mt-2 h-font">FILTERS</h4>
                        <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse"
                            data-bs-target="#filterDropdown" aria-controls="navbarNav" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse flex-column mt-2 align-items-stretch mx-2"
                            id="filterDropdown">

                            <div class="border bg-light p-3 rounded mb-3">
                                <h5 class="mb-3 h-font d-flex justify-content-between align-items-center"
                                    style="font-size: 18px;">
                                    <span>CHECK AVAILABILITY</span>
                                    <button id="chk_avail_btn" onclick="chk_chk_avail_clear()"
                                        class="btn shadow-none btn-sm text-secondary d-none">Reset</button>
                                </h5>
                                <label for="form-label">Source</label>
                                <input type="text" class="form-control shadow-none mb-3" id="source"
                                    onchange="chk_avail_filter()" value="<?php echo $source_default ?>">

                                <label for="form-label">Destination</label>
                                <input type="text" class="form-control shadow-none" id="destination"
                                    onchange="chk_avail_filter()" value="<?php echo $destination_default ?>">
                            </div>

                            <div class="border bg-light p-3 rounded mb-3">
                                <h5 class="mb-3 h-font d-flex justify-content-between align-items-center"
                                    style="font-size: 18px;">
                                    <span>Date</span>
                                </h5>
                                <div class="">
                                    <div class="">
                                        <label for="form-label">Date</label>
                                        <input type="date" class="form-control shadow-none mb-3" id="date"
                                            onchange="chk_avail_filter()" value="<?php echo $date_default ?>">
                                    </div>
                                    <div>
                                        <label for="form-label">No. of passengers</label>
                                        <input type="number" onchange="chk_avail_filter()" id="passengers"
                                            class="form-control shadow-none mb-3" max="9"
                                            value="<?php echo $passengers_default ?>">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </nav>
            </div>

            <div class="col-lg-9 col-md-12 px-4" id="bus-data">

            </div>

        </div>
    </div>

    <script>
        let bus_data = document.getElementById('bus-data');
        let source = document.getElementById('source');
        let destination = document.getElementById('destination');
        let chk_avail_btn = document.getElementById('chk_avail_btn');
        let date = document.getElementById('date');
        let passengers = document.getElementById('passengers');

        function fetch_bus() {
            let chk_avail = JSON.stringify({
                source: source.value,
                destination: destination.value,
                date: date.value,
                passengers: passengers.value
            });

            let xhr = new XMLHttpRequest();
            xhr.open("GET", "ajax/bus.php?fetch_bus&chk_avail=" + chk_avail, true);

            xhr.onprogress = function () {
                bus_data.innerHTML = `<div class="spinner-border d-block text-info mb-3 mx-auto" id="loader"><span class="visually-hidden">Loading...</span></div>`;
            };

            xhr.onload = function () {
                bus_data.innerHTML = this.responseText;
            };

            xhr.onerror = function () {
                console.error("Request failed.");
            };

            xhr.send();
        }

        function chk_avail_filter() {
            // Check if any of the fields (source, destination, date, passengers) are empty
            if (source.value === '' || destination.value === '' || date.value === '' || passengers.value === '') {
                bus_data.innerHTML = `<h3 class='text-center text-danger'>Please Fill the information!</h3>`;
                chk_avail_btn.classList.add('d-none');
            } else {
                chk_avail_btn.classList.remove('d-none');
                fetch_bus();
            }
        }

        function chk_avail_clear() {
            source.value = '';
            destination.value = '';
            date.value = '';
            passengers.value = '';
            chk_avail_btn.classList.add('d-none');
            bus_data.innerHTML = '';  // Clear the bus data area
        }

        window.onload = function () {
            fetch_bus();
        };



    </script>

    <?php require('inc/footer.php'); ?>

</body>

</html>