<?php
require('inc/essentials.php');
adminLogin();
require('inc/db_config.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Buses</title>
    <?php require('inc/links.php') ?>
</head>

<body>
    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4 h-font">Buses</h3>

                <div class="card boder-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal"
                                data-bs-target="#add-bus">
                                <i class="bi bi-plus-square"></i> Add
                            </button>
                        </div>

                        <div class="table-responsive-lg" style="height: 450px; overflow-y: scroll;">
                            <table class="table table-hover border text-center">
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Source</th>
                                        <th scope="col">Destination</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Arrivaltime</th>
                                        <th scope="col">Departuretime</th>
                                        <th scope="col">Capacity</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="bus-data">
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add bus Model -->
    <div class="modal fade" id="add-bus" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog model-lg">
            <form id="add_bus_form" autocomplete="off">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title" id="staticBackdropLabel">Add Bus</h1>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class=" col-md-6 mb-3">
                                <label for="form-label fw-bold">Bus Name</label>
                                <input type="text" name="name" class="form-control shadow-none" required>
                            </div>

                            <div class=" col-md-6 mb-3">
                                <label for="form-label fw-bold">Source</label>
                                <input type="text" name="source" class="form-control shadow-none" required>
                            </div>

                            <div class=" col-md-6 mb-3">
                                <label for="form-label fw-bold">Destination</label>
                                <input type="text" name="destination" class="form-control shadow-none" required>
                            </div>

                            <div class=" col-md-6 mb-3">
                                <label for="form-label fw-bold">Price</label>
                                <input type="number" min="1" name="price" class="form-control shadow-none" required>
                            </div>

                            <div class=" col-md-6 mb-3">
                                <label for="form-label fw-bold">Arrivaltime</label>
                                <input type="time" name="arrivaltime" class="form-control shadow-none" required>
                            </div>

                            <div class=" col-md-6 mb-3">
                                <label for="form-label fw-bold">Departuretime</label>
                                <input type="time" name="departuretime" class="form-control shadow-none" required>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Capacity</label>
                                <input type="number" min="1" max="52" name="capacity" class="form-control shadow-none"
                                    required>
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-none"
                            data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none">SAVE</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- edit bus Model -->
    <div class="modal fade" id="edit-bus" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog model-lg">
            <form id="edit_bus_form" autocomplete="off">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title" id="staticBackdropLabel">Edit Bus</h1>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <input type="hidden" name="bus_id" class="form-control shadow-none" required>
                            <div class=" col-md-6 mb-3">
                                <label for="form-label fw-bold">Bus Name</label>
                                <input type="text" name="name" class="form-control shadow-none" required>
                            </div>

                            <div class=" col-md-6 mb-3">
                                <label for="form-label fw-bold">Source</label>
                                <input type="text" name="source" class="form-control shadow-none" required>
                            </div>

                            <div class=" col-md-6 mb-3">
                                <label for="form-label fw-bold">Destination</label>
                                <input type="text" name="destination" class="form-control shadow-none" required>
                            </div>

                            <div class=" col-md-6 mb-3">
                                <label for="form-label fw-bold">Price</label>
                                <input type="number" min="1" name="price" class="form-control shadow-none" required>
                            </div>

                            <div class=" col-md-6 mb-3">
                                <label for="form-label fw-bold">Arrivaltime</label>
                                <input type="time" name="arrivaltime" class="form-control shadow-none" required>
                            </div>

                            <div class=" col-md-6 mb-3">
                                <label for="form-label fw-bold">Departuretime</label>
                                <input type="time" name="departuretime" class="form-control shadow-none" required>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Capacity</label>
                                <input type="number" min="1" max="52" name="capacity" class="form-control shadow-none"
                                    required>
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-none"
                            data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none">SAVE</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php require('inc/scripts.php') ?>

    <script>
        // Add bus form submission
        let add_bus_form = document.getElementById('add_bus_form');

        add_bus_form.addEventListener('submit', function (e) {
            e.preventDefault();
            add_bus();
        });

        // Edit bus form submission
        let edit_bus_form = document.getElementById('edit_bus_form');

        edit_bus_form.addEventListener('submit', function (e) {
            e.preventDefault();
            submit_edit_bus();
        });

        // Function to add bus
        function add_bus() {
            // Collect form data
            const name = add_bus_form.elements['name'].value;
            const source = add_bus_form.elements['source'].value;
            const destination = add_bus_form.elements['destination'].value;
            const price = add_bus_form.elements['price'].value;
            const arrivaltime = add_bus_form.elements['arrivaltime'].value; // Time value
            const departuretime = add_bus_form.elements['departuretime'].value; // Time value
            const capacity = add_bus_form.elements['capacity'].value;

            // Prepare parameters for the POST request
            const params = new URLSearchParams();
            params.append('add_bus', '');
            params.append('name', name);
            params.append('source', source);
            params.append('destination', destination);
            params.append('price', price);
            params.append('arrivaltime', arrivaltime);
            params.append('departuretime', departuretime);
            params.append('capacity', capacity);

            // Create a new XMLHttpRequest
            let xhr = new XMLHttpRequest();
            xhr.open('POST', 'ajax/bus.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            // Handle the response
            xhr.onload = function () {
                if (this.responseText == 1) {
                    alert('success', 'Bus added successfully');
                    add_bus_form.reset();
                    get_all_bus();
                    let modalElement = document.getElementById('add-bus');
                    let modal = bootstrap.Modal.getInstance(modalElement);
                    if (!modal) {
                        modal = new bootstrap.Modal(modalElement);
                    }
                    modal.hide();
                } else {
                    alert('error', 'Error adding bus');
                }
            };
            xhr.send(params.toString());
        }

        // Function to submit edited bus details
        function submit_edit_bus() {
            const bus_id = edit_bus_form.elements['bus_id'].value;
            const name = edit_bus_form.elements['name'].value;
            const source = edit_bus_form.elements['source'].value;
            const destination = edit_bus_form.elements['destination'].value;
            const price = edit_bus_form.elements['price'].value;
            const arrivaltime = edit_bus_form.elements['arrivaltime'].value; // Time value
            const departuretime = edit_bus_form.elements['departuretime'].value; // Time value
            const capacity = edit_bus_form.elements['capacity'].value;

            // Prepare parameters for the POST request
            const params = new URLSearchParams();
            params.append('edit_bus', '');
            params.append('bus_id', bus_id);
            params.append('name', name);
            params.append('source', source);
            params.append('destination', destination);
            params.append('price', price);
            params.append('arrivaltime', arrivaltime);
            params.append('departuretime', departuretime);
            params.append('capacity', capacity);

            // Create a new XMLHttpRequest
            let xhr = new XMLHttpRequest();
            xhr.open('POST', 'ajax/bus.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            // Handle the response
            xhr.onload = function () {
                if (this.responseText == 1) {
                    alert('success', 'Bus data edited!');
                    edit_bus_form.reset();
                    get_all_bus();
                    let modalElement = document.getElementById('edit-bus');
                    let modal = bootstrap.Modal.getInstance(modalElement);
                    if (!modal) {
                        modal = new bootstrap.Modal(modalElement);
                    }
                    modal.hide();
                } else {
                    alert('error', 'Error editing bus');
                }
            };

            xhr.send(params.toString());
        }

        // Function to fetch all buses
        function get_all_bus() {
            let xhr = new XMLHttpRequest();
            xhr.open('POST', 'ajax/bus.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function () {
                document.getElementById('bus-data').innerHTML = this.responseText;
            };
            xhr.send('get_all_bus');
        }

        // Function to edit bus details (load data into the edit form)
        function edit_details(id) {
            let xhr = new XMLHttpRequest();
            xhr.open('POST', 'ajax/bus.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            // Handle the response
            xhr.onload = function () {
                let data = JSON.parse(this.responseText);

                edit_bus_form.elements['name'].value = data.bus_name;
                edit_bus_form.elements['source'].value = data.source;
                edit_bus_form.elements['destination'].value = data.destination;
                edit_bus_form.elements['price'].value = data.price;
                edit_bus_form.elements['arrivaltime'].value = data.arrivaltime;
                edit_bus_form.elements['departuretime'].value = data.departuretime;
                edit_bus_form.elements['capacity'].value = data.capacity;
                edit_bus_form.elements['bus_id'].value = data.id;
            };

            xhr.send('get_bus=' + id);
        }

        // Function to remove a bus (via Ajax)
        function remove_bus(bus_id) {
            if (confirm("Are you sure, you want to delete this bus?")) {
                let data = new FormData();
                data.append('bus_id', bus_id);
                data.append('remove_bus', '');

                let xhr = new XMLHttpRequest();
                xhr.open('POST', 'ajax/bus.php', true);

                xhr.onload = function () {
                    if (this.responseText == 1) {
                        alert('success','Bus removed!');
                        get_all_bus();
                    } else {
                        alert('error','Bus removal failed!');
                    } 
                };

                xhr.send(data);
            }
        }

        window.onload = function () {
            get_all_bus(); 
        }


    </script>
</body>

</html>