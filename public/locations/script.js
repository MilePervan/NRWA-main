const API_URL = 'http://127.0.0.1:8000/api/v1';
let locations = [];
let editingId = null;

document.addEventListener('DOMContentLoaded', () => {
    loadData();

    document.getElementById('addBtn').addEventListener('click', openAddModal);
    document.getElementById('cancelBtn').addEventListener('click', closeModal);
    document.getElementById('saveBtn').addEventListener('click', saveLocation);
    document.getElementById('searchInput').addEventListener('input', searchLocations);
});

function loadData() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `${API_URL}/locations`);
    xhr.onload = function() {
        if (xhr.status === 200) {
            locations = JSON.parse(xhr.responseText).data;
            populateTable();
        }
    };
    xhr.send();
}

function populateTable(filtered = null) {
    const data = filtered || locations;
    const tbody = document.getElementById('locationsTableBody');
    tbody.innerHTML = '';
    data.forEach(loc => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${loc.id}</td>
            <td>${loc.name}</td>
            <td>
                <button class="edit-btn" onclick="openEditModal(${loc.id})">Edit</button>
                <button class="delete-btn" onclick="deleteLocation(${loc.id})">Delete</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function searchLocations() {
    const query = document.getElementById('searchInput').value.toLowerCase();
    const filtered = locations.filter(loc => loc.name.toLowerCase().includes(query));
    populateTable(filtered);
}

// Modal
function openAddModal() {
    editingId = null;
    document.getElementById('modalTitle').innerText = 'Add Location';
    document.getElementById('location_name').value = '';
    document.getElementById('locationModal').style.display = 'block';
}

function openEditModal(id) {
    const loc = locations.find(l => l.id === id);
    if(!loc) return alert('Location not found');
    editingId = id;
    document.getElementById('modalTitle').innerText = 'Edit Location';
    document.getElementById('location_name').value = loc.name;
    document.getElementById('locationModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('locationModal').style.display = 'none';
}

function saveLocation() {
    const name = document.getElementById('location_name').value.trim();
    if(!name) return alert('Name is required');

    const xhr = new XMLHttpRequest();
    if(editingId) xhr.open('PUT', `${API_URL}/locations/${editingId}`);
    else xhr.open('POST', `${API_URL}/locations`);

    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function() {
        if(xhr.status === 200 || xhr.status === 201) {
            closeModal();
            loadData();
        } else alert('Error saving location');
    };
    xhr.send(JSON.stringify({ name }));
}

function deleteLocation(id) {
    if(confirm('Are you sure?')) {
        const xhr = new XMLHttpRequest();
        xhr.open('DELETE', `${API_URL}/locations/${id}`);
        xhr.onload = function() {
            if(xhr.status === 200) loadData();
            else alert('Error deleting location');
        };
        xhr.send();
    }
}
