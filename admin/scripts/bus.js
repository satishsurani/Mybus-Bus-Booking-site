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

    // Handle the response and update the table
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

        edit_bus_form.elements['name'].value = data.name;
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

// Function to remove a bus
function remove_bus(bus_id) {
    if (confirm("Are you sure, you want to delete this bus?")) {
        let data = new FormData();
        data.append('bus_id', bus_id);
        data.append('remove_image', '');
        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'ajax/bus.php', true);

        xhr.onload = function () {
            if (this.responseText == 1) {
                alert('success', 'Bus removed!');
                get_all_bus();
            } else {
                alert('error', 'Bus removal failed!', 'image-alert');
            }
        };
        xhr.send(data);
    }
}

// Fetch buses when the page loads
window.onload = function () {
    get_all_bus();
};
