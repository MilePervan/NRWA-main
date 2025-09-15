const API_URL = 'http://127.0.0.1:8000/api/v1';
let managers = [];
let locations = [];
let dispatchers = [];
let editingManagerId = null;

document.addEventListener('DOMContentLoaded', () => {
    loadData();
});

async function loadData() {
    [managers, locations, dispatchers] = await Promise.all([
        fetch(`${API_URL}/managers`).then(res => res.json()).then(res => res.data),
        fetch(`${API_URL}/locations`).then(res => res.json()).then(res => res.data),
        fetch(`${API_URL}/dispatchers`).then(res => res.json()).then(res => res.data)
    ]);
    populateTable();
}

function populateTable(filteredManagers = null) {
    const data = filteredManagers || managers;
    const tbody = document.getElementById('managerTableBody');
    tbody.innerHTML = '';
    data.forEach(manager => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${manager.id}</td>
            <td>${manager.name}</td>
            <td>${manager.location.name}</td>
            <td>${manager.dispatchers.map(d => d.name).join(', ')}</td>
            <td>
                <button class="edit-btn" onclick="openEditModal(${manager.id})">Edit</button>
                <button class="delete-btn" onclick="deleteManager(${manager.id})">Delete</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function filterTable() {
    const query = document.getElementById('searchInput').value.toLowerCase();
    const filtered = managers.filter(m => m.name.toLowerCase().includes(query));
    populateTable(filtered);
}

// Modal
function openAddModal() {
    editingManagerId = null;
    document.getElementById('modalTitle').textContent = 'Add Manager';
    document.getElementById('manager_name').value = '';
    populateDropdowns();
    document.getElementById('managerModal').style.display = 'block';
}

function openEditModal(id) {
    const manager = managers.find(m => m.id === id);
    editingManagerId = id;
    document.getElementById('modalTitle').textContent = 'Edit Manager';
    document.getElementById('manager_name').value = manager.name;
    populateDropdowns(manager);
    document.getElementById('managerModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('managerModal').style.display = 'none';
}

function populateDropdowns(manager = null) {
    const locationSelect = document.getElementById('manager_location');
    const dispatcherSelect = document.getElementById('manager_dispatchers');

    locationSelect.innerHTML = '<option value="" disabled>Location</option>';
    locations.forEach(loc => {
        const option = document.createElement('option');
        option.value = loc.id;
        option.textContent = loc.name;
        if (manager && manager.location.id === loc.id) option.selected = true;
        locationSelect.appendChild(option);
    });

    dispatcherSelect.innerHTML = '';
    dispatchers.forEach(d => {
        const option = document.createElement('option');
        option.value = d.id;
        option.textContent = d.name;
        if (manager && manager.dispatchers.some(m => m.id === d.id)) option.selected = true;
        dispatcherSelect.appendChild(option);
    });
}

async function saveManager() {
    const name = document.getElementById('manager_name').value;
    const location_id = document.getElementById('manager_location').value;
    const dispatcher_ids = Array.from(document.getElementById('manager_dispatchers').selectedOptions).map(o => parseInt(o.value));
    const payload = { name, location_id, dispatcher_ids };

    if (editingManagerId) {
        await fetch(`${API_URL}/managers/${editingManagerId}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
    } else {
        await fetch(`${API_URL}/managers`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
    }
    closeModal();
    loadData();
}

async function deleteManager(id) {
    if (confirm('Are you sure you want to delete this manager?')) {
        await fetch(`${API_URL}/managers/${id}`, { method: 'DELETE' });
        loadData();
    }
}
