<template>
  <div class="app-container">
    <h1>WorkOrderCMS</h1>
    <div class="modules">
      <Managers />
      <Dispatchers />
      <Locations />
    </div>
  </div>
</template>

<script>
import Managers from './components/Managers.vue';
import Dispatchers from './components/Dispatchers.vue';
import Locations from './components/Locations.vue';

export default {
  components: { Managers, Dispatchers, Locations }
};
</script>

<style scoped>
.app-container {
  background-color: #f0f4f8;
  min-height: 100vh;
  padding: 40px 20px;
  text-align: center;
  font-family: Arial, sans-serif;
}

h1 {
  color: #2c3e50;
  margin-bottom: 30px;
}

.modules {
  display: flex;
  flex-direction: column;
  gap: 40px;
  align-items: center;
}
</style>
