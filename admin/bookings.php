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
    <title>Admin Panel - Bookings Details</title>
    <?php require('inc/links.php') ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
    .filter-container {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 20px;
        align-items: center;
        gap: 15px; /* Adds spacing between the dropdown and the button */
    }

    .custom-dropdown {
        position: relative;
        display: inline-block;
        width: 200px;
    }

    .dropdown-button {
        width: 100%;
        padding: 8px 12px;
        font-size: 16px;
        border-radius: 8px;
        border: 1px solid #ccc;
        background-color: #f9f9f9;
        color: #333;
        cursor: pointer;
        text-align: left;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 8px;
        max-height: 200px;
        overflow-y: auto;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    .dropdown-menu li {
        padding: 8px 12px;
        list-style-type: none;
        cursor: pointer;
    }

    .dropdown-menu li:hover {
        background-color: #ffb300;
        color: white;
    }

    .custom-dropdown.open .dropdown-menu {
        display: block;
    }

    .dropdown-button.selected {
        background-color: #AD8B3A;
        color: white;
    }

    .dobtn {
        padding: 8px 16px;
        font-size: 16px;
        background-color: #AD8B3A;
        color: white;
        border-radius: 8px;
        border: none;
        display: flex;
        align-items: center;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .dobtn:hover {
        background-color: #cc8a00;
    }

    .dobtn i {
        margin-right: 8px;
        font-size: 18px;
    }

    .dobtn:focus {
        outline: none;
    }
</style>

</head>

<body>
    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4 h-font">Bookings</h3>

                <div class="filter-container">
                    <div class="custom-dropdown">
                        <button class="dropdown-button" id="dropdownButton">Select Time Period</button>
                        <ul class="dropdown-menu" id="dropdownMenu">
                            <li data-value="30days">Last 30 Days</li>
                            <li data-value="90days">Last 90 Days</li>
                            <li data-value="1year">Last 1 Year</li>
                            <li data-value="all">All Time</li>
                        </ul>
                    </div>

                    <button id="downloadReportBtn" class="btn ms-3 dobtn">
                        <i class="fas fa-file-download"></i> Download Report
                    </button>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="table-responsive-lg">
                            <table class="table table-hover border">
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">User Details</th>
                                        <th scope="col">Bus Details</th>
                                        <th scope="col">Amount & Date</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="bus-data"></tbody>
                            </table>
                            <div id="pagination"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/scripts.php'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.22/jspdf.plugin.autotable.min.js"></script>

    <script>
        // Dropdown button click handler
        document.getElementById('dropdownButton').addEventListener('click', function () {
            document.querySelector('.custom-dropdown').classList.toggle('open');
        });

        // Dropdown menu item click handler
        document.querySelectorAll('.dropdown-menu li').forEach(function (item) {
            item.addEventListener('click', function () {
                let value = this.getAttribute('data-value');
                document.getElementById('dropdownButton').textContent = this.textContent;
                document.getElementById('dropdownButton').classList.add('selected');
                document.querySelector('.custom-dropdown').classList.remove('open');
                get_bookings(1, value);
            });
        });

        // Function to fetch bookings
        function get_bookings(page = 1, date_filter = '30days') {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/bookings.php", true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                try {
                    let data = JSON.parse(this.responseText);
                    document.getElementById('bus-data').innerHTML = data.table_data;
                    document.getElementById('pagination').innerHTML = data.pagination;
                } catch (e) {
                    console.error("Error parsing JSON:", e);
                    console.log("Response:", this.responseText);
                }
            };
            xhr.send('get_bookings=true&page=' + page + '&date_filter=' + date_filter);
        }

        // Initial load
        window.onload = function () {
            get_bookings();
        };

        // Download Report button functionality
        document.getElementById('downloadReportBtn').addEventListener('click', function () {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            const headers = ['#', 'User Details', 'Bus Details', 'Amount & Date', 'Status'];
            const rows = [];

            document.querySelectorAll('#bus-data tr').forEach(function (row) {
                const rowData = [];
                row.querySelectorAll('td').forEach(function (cell) {
                    let cellText = cell.innerText.trim();
                    if (cellText.includes('₹')) {
                        cellText = cellText.replace('₹', 'Rs.');
                    }
                    rowData.push(cellText);
                });
                rows.push(rowData);
            });

            doc.autoTable({
                head: [headers],
                body: rows,
                theme: 'striped',
                startY: 20,
                headStyles: {
                    fillColor: [0, 51, 102],
                    textColor: [255, 255, 255]
                },
                bodyStyles: {
                    fontSize: 10
                }
            });

            doc.save('bookings_report.pdf');
        });
    </script>
</body>

</html>
