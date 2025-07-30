import axios from 'axios';
import { get } from 'react-hook-form';

const API_BASE = "http://127.0.0.1:8000/api";

export const api = {
    register: async (data) =>
        axios.post(`${API_BASE}/register`, data, {
            headers: { "Content-Type": "application/json" },
        }).then((res) => res.data)
            .catch((error) => {
                throw error.response ? error.response.data : error;
            }),

    login: async (data) =>
        axios.post(`${API_BASE}/login`, data, {
            headers: { "Content-Type": "application/json" },
        }).then((res) => res.data)
            .catch((error) => {
                throw error.response ? error.response.data : error;
            }),

    getUser: async (token) =>
        axios.get(`${API_BASE}/user`, {
            headers: { Authorization: `Bearer ${token}` },
        }).then((res) => res.data)
            .catch((error) => {
                throw error.response ? error.response.data : error;
            }),

    getTasks: async (token) =>
        axios.get(`${API_BASE}/tasks`, {
            headers: { Authorization: `Bearer ${token}` },
        }).then((res) => res.data)
            .catch((error) => {
                throw error.response ? error.response.data : error;
            }),

    createTask: async (task, token) =>
        axios.post(`${API_BASE}/tasks`, task, {
            headers: { "Content-Type": "application/json", Authorization: `Bearer ${token}` },
        }).then((res) => res.data)
            .catch((error) => {
                throw error.response ? error.response.data : error;
            }),

    updateTask: async (id, task, token) =>
        axios.put(`${API_BASE}/tasks/${id}`, task, {
            headers: { "Content-Type": "application/json", Authorization: `Bearer ${token}` },
        }).then((res) => res.data)
            .catch((error) => {
                throw error.response ? error.response.data : error;
            }),

    deleteTask: async (id, token) =>
        axios.delete(`${API_BASE}/tasks/${id}`, {
            headers: { Authorization: `Bearer ${token}` },
        }).then((res) => res.data)
            .catch((error) => {
                throw error.response ? error.response.data : error;
            }),
};
