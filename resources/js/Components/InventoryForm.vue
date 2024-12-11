<script setup>
import { ref, watch } from 'vue';
import { apiService } from '@/apiService';

// Define the props passed to the component
const props = defineProps({
  product: Object, // Product data when editing
});

// Emit event to notify the parent component
const emit = defineEmits(['submit']);

// Reactive form object
const form = ref({
  name: '',
  quantity: '',
  date: '',
});

// Watch for changes in the `product` prop and populate or reset the form accordingly
watch(
  () => props.product, // Watch the product prop
  (product) => {
    if (product) {
      // Populate the form with product data when editing
      form.value = { ...product };
    } else {
      // Reset the form when creating a new product
      form.value = {
        name: '',
        quantity: '',
        date: '',
      };
    }
  },
  { immediate: true } // Run the watcher immediately on component initialization
);

// Handle form submission for both creating and updating products
const submitForm = async () => {
  try {
    if (props.product) {
      // Update an existing product
      await apiService.updateProduct(props.product.id, form.value);
      alert('Product updated successfully');
    } else {
      // Create a new product
      await apiService.createProduct(form.value);
      alert('Product created successfully');
    }

    emit('submit'); // Notify the parent to refresh the data
  } catch (error) {
    alert('Failed to save product: ' + error.response?.data?.message || error.message);
  }
};
</script>

<template>
  <form @submit.prevent="submitForm">
    <div class="mb-4">
      <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
      <input
        type="text"
        id="name"
        v-model="form.name"
        required
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
      />
    </div>

    <div class="mb-4">
      <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
      <input
        type="number"
        id="quantity"
        v-model="form.quantity"
        required
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
      />
    </div>

    <div class="mb-4">
      <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
      <input
        type="date"
        id="date"
        v-model="form.date"
        required
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
      />
    </div>

    <button
      type="submit"
      class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
    >
      Submit
    </button>
  </form>
</template>
