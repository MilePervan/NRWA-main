import axios from 'axios';

const API_URL = 'http://localhost:3000';

// Testni Basic Auth account
const username = 'demo';
const password = 'demo123';
const authHeader = 'Basic ' + btoa(`${username}:${password}`);

export const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Authorization': authHeader
  }
});
