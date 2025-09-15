const API_URL = 'http://127.0.0.1:8000/api/v1';
let dispatchers = [];
let editingId = null;

$(document).ready(function() {
    loadData();

    $('#searchInput').on('input', function() {
        const query = $(this).val().toLowerCase();
        const filtered = dispatchers.filter(d => d.name.toLowerCase().includes(query));
        populateTable(filtered);
    });

    $('#addBtn').click(openAddModal);
    $('#cancelBtn').click(closeModal);
    $('#saveBtn').click(saveDispatcher);
});

function loadData() {
    $.get(`${API_URL}/dispatchers`, function(res) {
        dispatchers = res.data;
        populateTable();
    });
}

function populateTable(filtered = null) {
    const data = filtered || dispatchers;
    const tbody = $('#dispatchersTableBody');
    tbody.empty();
    data.forEach(d => {
        tbody.append(`
            <tr>
                <td>${d.id}</td>
                <td>${d.name}</td>
                <td>${d.phone}</td>
                <td>
                    <button class="edit-btn" onclick="openEditModal(${d.id})">Edit</button>
                    <button class="delete-btn" onclick="deleteDispatcher(${d.id})">Delete</button>
                </td>
            </tr>
        `);
    });
}

// Modal
function openAddModal() {
    editingId = null;
    $('#modalTitle').text('Add Dispatcher');
    $('#dispatcher_name').val('');
    $('#dispatcher_phone').val('');
    $('#dispatcherModal').show();
}

function openEditModal(id) {
    const dispatcher = dispatchers.find(d => d.id === id);
    if(!dispatcher) return alert('Dispatcher not found');
    editingId = id;
    $('#modalTitle').text('Edit Dispatcher');
    $('#dispatcher_name').val(dispatcher.name);
    $('#dispatcher_phone').val(dispatcher.phone);
    $('#dispatcherModal').show();
}

function closeModal() {
    $('#dispatcherModal').hide();
}

function saveDispatcher() {
    const payload = {
        name: $('#dispatcher_name').val(),
        phone: $('#dispatcher_phone').val()
    };
    if(!payload.name || !payload.phone) return alert('Name and Phone required');

    if(editingId) {
        $.ajax({
            url: `${API_URL}/dispatchers/${editingId}`,
            method: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(payload),
            success: function() { closeModal(); loadData(); }
        });
    } else {
        $.ajax({
            url: `${API_URL}/dispatchers`,
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(payload),
            success: function() { closeModal(); loadData(); }
        });
    }
}

function deleteDispatcher(id) {
    if(confirm('Are you sure?')) {
        $.ajax({
            url: `${API_URL}/dispatchers/${id}`,
            method: 'DELETE',
            success: loadData
        });
    }
}
