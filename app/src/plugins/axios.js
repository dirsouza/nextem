import axios from "axios";

const { VUE_APP_API_HOST, VUE_APP_API_PORT } = process.env;

const config = {
  baseURL: `http://${VUE_APP_API_HOST}:${VUE_APP_API_PORT}/api/`,
  withCredentials: true,
  headers: {
    "Content-Type": "application/json",
    "Access-Control-Allow-Origin": "*"
  }
};

const token = localStorage.getItem("token");

if (token) config.headers["Authorization"] = `Bearer ${token}`;

export default axios.create(config);
