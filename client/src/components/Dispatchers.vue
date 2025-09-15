<template>
  <div class="container">
    <h2>Dispatchers</h2>

    <!-- Dodavanje novog dispatchera -->
    <form @submit.prevent="addDispatcher" class="form">
      <input v-model="newDispatcher.name" placeholder="Name" required />
      <input v-model="newDispatcher.phone" placeholder="Phone" required />
      <button type="submit">Add</button>
    </form>

    <!-- Tablica dispatchera -->
    <table>
      <thead>
        <tr>
          <th style="width: 50px;">ID</th>
          <th style="width: 200px;">Name</th>
          <th style="width: 150px;">Phone</th>
          <th style="width: 200px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="dispatcher in dispatchers" :key="dispatcher.id">
          <td>{{ dispatcher.id }}</td>
          <td v-if="!dispatcher.editing">{{ dispatcher.name }}</td>
          <td v-else><input v-model="dispatcher.name" /></td>

          <td v-if="!dispatcher.editing">{{ dispatcher.phone }}</td>
          <td v-else><input v-model="dispatcher.phone" /></td>

          <td>
            <button v-if="!dispatcher.editing" @click="dispatcher.editing = true">Edit</button>
            <button v-else @click="updateDispatcher(dispatcher)">Save</button>
            <button @click="deleteDispatcher(dispatcher.id)">Delete</button>
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
      dispatchers: [],
      newDispatcher: { name: '', phone: '' }
    };
  },
  methods: {
    fetchDispatchers() {
      axios.get('http://localhost:3000/dispatchers', {
        auth: { username: 'demo', password: 'demo123' }
      }).then(res => {
        this.dispatchers = res.data.data.map(d => ({ ...d, editing: false }));
      });
    },
    addDispatcher() {
      axios.post('http://localhost:3000/dispatcher', this.newDispatcher, {
        auth: { username: 'demo', password: 'demo123' }
      }).then(() => {
        this.fetchDispatchers();
        this.newDispatcher.name = '';
        this.newDispatcher.phone = '';
      });
    },
    updateDispatcher(dispatcher) {
      axios.put(`http://localhost:3000/dispatcher/${dispatcher.id}`, {
        name: dispatcher.name,
        phone: dispatcher.phone
      }, {
        auth: { username: 'demo', password: 'demo123' }
      }).then(() => {
        dispatcher.editing = false;
        this.fetchDispatchers();
      });
    },
    deleteDispatcher(id) {
      axios.delete(`http://localhost:3000/dispatcher/${id}`, {
        auth: { username: 'demo', password: 'demo123' }
      }).then(() => {
        this.fetchDispatchers();
      });
    }
  },
  mounted() {
    this.fetchDispatchers();
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
  min-width: 150px;
}

button {
  background-color: #27ae60;
  color: white;
  border: none;
  padding: 8px 12px;
  cursor: pointer;
  transition: background 0.3s;
}

button:hover {
  background-color: #1e8449;
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
