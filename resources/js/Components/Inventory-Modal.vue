<script setup>
const props = defineProps({
  isVisible: Boolean,
  product: Object, // Pass the product object when editing
});

const emit = defineEmits(['update:isVisible', 'submit']);

const hideModal = () => {
  emit('update:isVisible', false); // Close the modal
};
</script>

<template>
  <div
    v-if="isVisible"
    class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50"
  >
    <div class="bg-white p-6 rounded-md shadow-md w-96">
      <!-- Modal Header -->
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">
          {{ product ? 'Edit Product' : 'Add New Product' }}
        </h2>
        <button
          @click="hideModal"
          class="text-gray-500 hover:text-gray-700 focus:outline-none"
        >
          &times;
        </button>
      </div>

      <!-- Slot for Form -->
      <slot :product="product" />

      <!-- Modal Footer -->
      <div class="flex justify-end mt-4">
        <button
          @click="hideModal"
          class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none"
        >
          Cancel
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Adjust styles as necessary for your design */
</style>
