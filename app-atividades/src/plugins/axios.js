import axios from "axios";

const config = {
  baseURL: "http://127.0.0.1:8000/api/",
  withCredentials: true,
  headers: {
    "Content-Type": "application/json",
    "Access-Control-Allow-Origin": "*"
  }
};

const token = localStorage.getItem("token");

if (token) config.headers["Authorization"] = `Bearer ${token}`;

export default axios.create(config);
