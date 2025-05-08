import './bootstrap';

// Real-time event handlers
const setupRealTimeListeners = () => {
    // Listen for new items
    window.Echo.channel('items')
        .listen('.item.created', (e) => {
            console.log('New item created:', e.item);
            // Add your UI update logic here
            // For example: updateItemsList(e.item);
        })
        .listen('.item.updated', (e) => {
            console.log('Item updated:', e.item);
            // Add your UI update logic here
            // For example: updateItemInList(e.item);
        });
};

// Authentication helpers
const setAuthToken = (token) => {
    if (token) {
        localStorage.setItem('token', token);
        window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    } else {
        localStorage.removeItem('token');
        delete window.axios.defaults.headers.common['Authorization'];
    }
};

// API request helpers
const api = {
    async login(credentials) {
        try {
            const response = await window.axios.post('/api/login', credentials);
            setAuthToken(response.data.access_token);
            return response.data;
        } catch (error) {
            throw error.response?.data || error;
        }
    },

    async register(userData) {
        try {
            const response = await window.axios.post('/api/register', userData);
            setAuthToken(response.data.token);
            return response.data;
        } catch (error) {
            throw error.response?.data || error;
        }
    },

    async logout() {
        try {
            await window.axios.post('/api/logout');
            setAuthToken(null);
        } catch (error) {
            console.error('Logout error:', error);
        }
    },

    async getItems(params = {}) {
        try {
            const response = await window.axios.get('/api/items', { params });
            return response.data;
        } catch (error) {
            throw error.response?.data || error;
        }
    },

    async getItem(id) {
        try {
            const response = await window.axios.get(`/api/items/${id}`);
            return response.data;
        } catch (error) {
            throw error.response?.data || error;
        }
    },

    async createItem(itemData) {
        try {
            const response = await window.axios.post('/api/items', itemData);
            return response.data;
        } catch (error) {
            throw error.response?.data || error;
        }
    },

    async updateItem(id, itemData) {
        try {
            const response = await window.axios.put(`/api/items/${id}`, itemData);
            return response.data;
        } catch (error) {
            throw error.response?.data || error;
        }
    },

    async deleteItem(id) {
        try {
            const response = await window.axios.delete(`/api/items/${id}`);
            return response.data;
        } catch (error) {
            throw error.response?.data || error;
        }
    }
};

// Initialize real-time listeners when the app starts
document.addEventListener('DOMContentLoaded', () => {
    setupRealTimeListeners();
});

// Export the API object for use in other files
window.api = api;
