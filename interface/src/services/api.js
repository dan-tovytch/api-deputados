import axios from 'axios';
import base_url from './base_url';

const api = axios.create({
    baseURL: base_url,
    timeout: 10000, // 10 segundos de timeout
    headers: {
        'Content-Type': 'application/json',
    },
});

api.interceptors.request.use(
    (config) => {
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response) {
            console.error('Erro na resposta da Api:', error.response.data);
        } else if (error.request) {
            console.error('sem resposta do servidor:', error.request);
        } else {
            console.error('erro ao configurar requisição:', error.message);
        }
        return Promise.reject(error);
    }
);

export default api;