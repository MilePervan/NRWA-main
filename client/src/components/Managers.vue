<template>
  <div class="container">
    <h2>Managers</h2>

    <form @submit.prevent="addManager" class="form">
      <input v-model="newManager.name" placeholder="Name" required />
      <select v-model="newManager.location_id" required>
        <option disabled value="">Select Location</option>
        <option v-for="loc in locations" :key="loc.id" :value="loc.id">
          {{ loc.name }}
        </option>
      </select>
      <button type="submit">Add</button>
    </form>

    <table>
      <thead>
        <tr>
          <th style="width: 50px;">ID</th>
          <th style="width: 200px;">Name</th>
          <th style="width: 200px;">Location</th>
          <th style="width: 200px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="manager in managers" :key="manager.id">
          <td>{{ manager.id }}</td>
          <td v-if="!manager.editing">{{ manager.name }}</td>
          <td v-else>
            <input v-model="manager.name" />
          </td>

          <td v-if="!manager.editing">{{ manager.location }}</td>
          <td v-else>
            <select v-model="manager.location_id">
              <option v-for="loc in locations" :key="loc.id" :value="loc.id">
                {{ loc.name }}
              </option>
            </select>
          </td>

          <td>
            <button v-if="!manager.editing" @click="manager.editing = true">Edit</button>
            <button v-else @click="updateManager(manager)">Save</button>
            <button @click="deleteManager(manager.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      managers: [],
      locations: [],
      newManager: {
        name: '',
        location_id: ''
      }
    };
  },
  methods: {
    fetchManagers() {
      axios.get('http://localhost:3000/managers', {
        auth: { username: 'demo', password: 'demo123' }
      })
      .then(res => {
        this.managers = res.data.data.map(m => ({
          ...m,
          editing: false
        }));
      });
    },
    fetchLocations() {
      axios.get('http://localhost:3000/locations', {
        auth: { username: 'demo', password: 'demo123' }
      })
      .then(res => {
        this.locations = res.data.data;
      });
    },
    addManager() {
      axios.post('http://localhost:3000/manager', this.newManager, {
        auth: { username: 'demo', password: 'demo123' }
      })
      .then(() => {
        this.fetchManagers();
        this.newManager.name = '';
        this.newManager.location_id = '';
      });
    },
    updateManager(manager) {
      axios.put(`http://localhost:3000/manager/${manager.id}`, {
        name: manager.name,
        location_id: manager.location_id
      }, {
        auth: { username: 'demo', password: 'demo123' }
      })
      .then(() => {
        manager.editing = false;
        this.fetchManagers();
      });
    },
    deleteManager(id) {
      axios.delete(`http://localhost:3000/manager/${id}`, {
        auth: { username: 'demo', password: 'demo123' }
      })
      .then(() => {
        this.fetchManagers();
      });
    }
  },
  mounted() {
    this.fetchManagers();
    this.fetchLocations();
  }
};
</script>

<style scoped>
.container {
  max-width: 1000px;
  margin: 20px auto;
  padding: 20px;
}

h2 {
  color: #2c3e50;
  margin-bottom: 20px;
  text-align: center;
}

.form {
  display: flex;
  gap: 20px;
  margin-bottom: 30px;
  justify-content: center;
}

input, select {
  padding: 8px;
  min-width: 150px;
}

button {
  background-color: #3498db;
  color: white;
  border: none;
  padding: 8px 12px;
  cursor: pointer;
  transition: background 0.3s;
}

button:hover {
  background-color: #2980b9;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  border: 1px solid #ddd;
  padding: 12px;
  text-align: left;
}

th {
  background-color: #f4f4f4;
}

tr:nth-child(even) {
  background-color: #f9f9f9;
}
</style>
