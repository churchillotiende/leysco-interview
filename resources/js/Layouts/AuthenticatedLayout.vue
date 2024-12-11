<script setup>
import { ref, onMounted } from 'vue';
import { apiService,listenForProductUpdates } from '@/apiService';
import InventoryForm from '@/Components/InventoryForm.vue';
import Modal from '@/Components/Inventory-Modal.vue';

// Reactive state
const activeTab = ref('clients'); // Default tab
const clientsHistory = ref([]);
const inventoryHistory = ref([]);
const isModalVisible = ref(false);
const editingProduct = ref(null); // To track product being edited

// Fetch data from API
const fetchClientsHistory = async () => {
  try {
    clientsHistory.value = await apiService.fetchClientsHistory();
  } catch (error) {
    console.error('Failed to fetch clients:', error);
  }
};

const fetchInventoryHistory = async () => {
  try {
    inventoryHistory.value = await apiService.fetchInventoryHistory();
  } catch (error) {
    console.error('Failed to fetch inventory:', error);
  }
};

// Fetch all data on component mount
onMounted(() => {
  fetchClientsHistory();
  fetchInventoryHistory();  
  listenForProductUpdates();
});

// Open modal for adding or editing product
const openModal = (product = null) => {
  editingProduct.value = product;
  isModalVisible.value = true;
};

// Handle product submission
const handleProductSubmit = async () => {
  fetchInventoryHistory(); // Refresh inventory after submission
  isModalVisible.value = false;
};

// Handle product deletion
const deleteProduct = async (productId) => {
  try {
    await apiService.deleteProduct(productId);
    alert('Product deleted successfully');
    fetchInventoryHistory();
  } catch (error) {
    alert('Failed to delete product: ' + (error.response?.data?.message || error.message));
  }
};

// Tab labels
const tabViews = {
  clients: 'Clients',
  inventoryHistory: 'Products',
};
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center">
            <span class="text-2xl font-bold text-indigo-600">Inventory App</span>
          </div>
        </div>
      </div>
    </nav>

    <!-- Page Header -->
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900">Inventory Management</h1>
      </div>
    </header>

    <!-- Tabs and Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <!-- Tab Navigation -->
      <div class="flex space-x-4 mb-6">
        <button
          v-for="(label, key) in tabViews"
          :key="key"
          @click="activeTab = key"
          :class="{
            'px-4 py-2 rounded-t-lg font-medium text-sm': true,
            'bg-indigo-100 text-indigo-600': activeTab === key,
            'bg-gray-200 text-gray-700': activeTab !== key,
          }"
        >
          {{ label }}
        </button>
      </div>

      <!-- Tab Content -->
      <div class="bg-white rounded-lg shadow px-6 py-4">
        <!-- Clients History -->
        <div v-if="activeTab === 'clients'">
          <h2 class="text-lg font-medium text-gray-900 mb-4">Clients</h2>
          <table class="min-w-full table-auto border border-gray-200 rounded">
            <thead class="bg-gray-100 text-left text-gray-600">
              <tr>
                <th class="py-2 px-4">Name</th>
                <th class="py-2 px-4">Email</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="client in clientsHistory" :key="client.email" class="border-b border-gray-200">
                <td class="py-2 px-4">{{ client.name }}</td>
                <td class="py-2 px-4">{{ client.email }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Inventory History -->
        <div v-if="activeTab === 'inventoryHistory'">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-medium text-gray-900">Products</h2>
            <button
              @click="openModal()"
              class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded"
            >
              New Product
            </button>
          </div>
          <table class="min-w-full table-auto border border-gray-200 rounded">
            <thead class="bg-gray-100 text-left text-gray-600">
              <tr>
                <th class="py-2 px-4">Item</th>
                <th class="py-2 px-4">Quantity</th>
                <th class="py-2 px-4">Date</th>
                <th class="py-2 px-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in inventoryHistory" :key="item.id" class="border-b border-gray-200">
                <td class="py-2 px-4">{{ item.name }}</td>
                <td class="py-2 px-4">{{ item.quantity }}</td>
                <td class="py-2 px-4">{{ item.date }}</td>
                <td class="py-2 px-4">
                  <button
                    @click="openModal(item)"
                    class="bg-yellow-500 text-white px-2 py-1 rounded"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteProduct(item.id)"
                    class="bg-red-500 text-white px-2 py-1 rounded ml-2"
                  >
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <Modal :isVisible="isModalVisible" :product="editingProduct" @update:isVisible="isModalVisible = $event">
      <template #default="{ product }">
        <InventoryForm :product="product" @submit="handleProductSubmit" />
      </template>
    </Modal>
  </div>
</template>

<style scoped>
/* Add any additional styling as needed */
</style>
