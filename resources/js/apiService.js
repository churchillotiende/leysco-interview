import axios from 'axios';

export const apiService = {
  async fetchClientsHistory() {
    try {
      const response = await axios.get('/clients');
      return response.data;
    } catch (error) {
      console.error('Error fetching clients history:', error);
      throw error;
    }
  },

  async fetchInventoryHistory() {
    try {
      const response = await axios.get('/products');
      return response.data;
    } catch (error) {
      console.error('Error fetching admin inventory history:', error);
      throw error;
    }
  },

  async createProduct(product) {
    try {
      const response = await axios.post('/products', product, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token for authentication
        },
      });
      return response.data;
    } catch (error) {
      console.error('Error creating product:', error);
      throw error;
    }
  },

  async updateProduct(productId, productData) {
    try {
      const response = await axios.put(`/products/${productId}`, productData, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token for authentication
        },
      });
      return response.data;
    } catch (error) {
      console.error('Error updating product:', error);
      throw error;
    }
  },

  async deleteProduct(productId) {
    try {
      await axios.delete(`/products/${productId}`, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token for authentication
        },
      });
    } catch (error) {
      console.error('Error deleting product:', error);
      throw error;
    }
  },
};

export const listenForProductUpdates = () => {
  window.Echo.channel('products')
    .listen('.product-added', (event) => {
      console.log('New product added:', event.product);
      
      let products = JSON.parse(localStorage.getItem('products')) || [];
      
      products.push(event.product);
      
      localStorage.setItem('products', JSON.stringify(products));
    });
};

