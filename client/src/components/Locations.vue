<template>
  <div class="container">
    <h2>Locations</h2>

  
    <form @submit.prevent="addLocation" class="form">
      <input v-model="newLocation.name" placeholder="Location Name" required />
      <button type="submit">Add</button>
    </form>

  
    <table>
      <thead>
        <tr>
          <th style="width: 50px;">ID</th>
          <th style="width: 300px;">Name</th>
          <th style="width: 200px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="location in locations" :key="location.id">
          <td>{{ location.id }}</td>
          <td v-if="!location.editing">{{ location.name }}</td>
          <td v-else><input v-model="location.name" /></td>

          <td>
            <button v-if="!location.editing" @click="location.editing = true">Edit</button>
            <button v-else @click="updateLocation(location)">Save</button>
            <button @click="deleteLocation(location.id)">Delete</button>
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
      locations: [],
      newLocation: { name: '' }
    };
  },
  methods: {
    fetchLocations() {
      axios.get('http://localhost:3000/locations', {
        auth: { username: 'demo', password: 'demo123' }
      }).then(res => {
        this.locations = res.data.data.map(l => ({ ...l, editing: false }));
      });
    },
    addLocation() {
      axios.post('http://localhost:3000/location', this.newLocation, {
        auth: { username: 'demo', password: 'demo123' }
      }).then(() => {
        this.fetchLocations();
        this.newLocation.name = '';
      });
    },
    updateLocation(location) {
      axios.put(`http://localhost:3000/location/${location.id}`, {
        name: location.name
      }, {
        auth: { username: 'demo', password: 'demo123' }
      }).then(() => {
        location.editing = false;
        this.fetchLocations();
      });
    },
    deleteLocation(id) {
      axios.delete(`http://localhost:3000/location/${id}`, {
        auth: { username: 'demo', password: 'demo123' }
      }).then(() => {
        this.fetchLocations();
      });
    }
  },
  mounted() {
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

input {
  padding: 8px;
  min-width: 200px;
}

button {
  background-color: #e67e22;
  color: white;
  border: none;
  padding: 8px 12px;
  cursor: pointer;
  transition: background 0.3s;
}

button:hover {
  background-color: #d35400;
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
