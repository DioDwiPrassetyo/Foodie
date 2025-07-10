import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";
import { toast } from "react-toastify";
import { FaEnvelope, FaLock } from "react-icons/fa";

const Login = ({ onLoginSuccess = () => {} }) => {
  const navigate = useNavigate();
  const [form, setForm] = useState({ email: "", password: "" });

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    const toastId = toast.loading("Logging in...");

    try {
      const res = await axios.post("http://localhost/api/login.php", form);

      if (res.data.status === "success") {
        toast.update(toastId, {
          render: "Login berhasil! Selamat datang " + res.data.name,
          type: "success",
          isLoading: false,
          autoClose: 3000,
        });
        localStorage.setItem("currentUser", JSON.stringify(res.data));
        onLoginSuccess();
        navigate("/");
      } else {
        toast.update(toastId, {
          render: "Login gagal: " + res.data.message,
          type: "error",
          isLoading: false,
          autoClose: 3000,
        });
      }
    } catch (err) {
      console.warn("Koneksi gagal, fallback ke localStorage");

      const users = JSON.parse(localStorage.getItem("users")) || [];
      const found = users.find(
        (u) => u.email === form.email && u.password === form.password
      );

      if (found) {
        toast.update(toastId, {
          render: "Login offline berhasil! Selamat datang " + found.name,
          type: "info",
          isLoading: false,
          autoClose: 3000,
        });
        localStorage.setItem("currentUser", JSON.stringify(found));
        onLoginSuccess();
        navigate("/");
      } else {
        toast.update(toastId, {
          render: "Email atau password salah (offline fallback)",
          type: "error",
          isLoading: false,
          autoClose: 3000,
        });
      }
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-300 to-purple-400">
      <div className="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md">
        <h2 className="text-3xl font-bold text-center text-gray-800 mb-6">
          Welcome Back
        </h2>
        <form onSubmit={handleSubmit} className="space-y-5">
          <div className="relative">
            <FaEnvelope className="absolute left-3 top-3 text-gray-400" />
            <input
              type="email"
              name="email"
              value={form.email}
              onChange={handleChange}
              placeholder="Email"
              required
              className="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400"
            />
          </div>
          <div className="relative">
            <FaLock className="absolute left-3 top-3 text-gray-400" />
            <input
              type="password"
              name="password"
              value={form.password}
              onChange={handleChange}
              placeholder="Password"
              required
              className="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400"
            />
          </div>
          <button
            type="submit"
            className="w-full bg-blue-600 hover:bg-blue-700 transition duration-200 text-white py-2 rounded-lg font-semibold shadow-md"
          >
            Login
          </button>
          <p className="text-center text-sm text-gray-600">
            Don’t have an account?{" "}
            <span
              onClick={() => navigate("/register")}
              className="text-blue-600 hover:underline cursor-pointer"
            >
              Create one
            </span>
          </p>
        </form>
      </div>
    </div>
  );
};

export default Login;
