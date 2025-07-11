import React, { useEffect, useState } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";
import {
  FaUserCircle,
  FaEnvelope,
  FaPhone,
  FaCalendarAlt,
  FaClock,
  FaSignOutAlt,
} from "react-icons/fa";

const Profile = () => {
  const navigate = useNavigate();
  const [user, setUser] = useState(null);
  const [editing, setEditing] = useState(false);
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [phone, setPhone] = useState("");
  const [selectedFile, setSelectedFile] = useState(null);
  const loginTime =
    localStorage.getItem("loginTime") || new Date().toLocaleString();

  useEffect(() => {
    const currentUser = JSON.parse(localStorage.getItem("currentUser"));
    if (!currentUser || !currentUser.id) {
      navigate("/login");
      return;
    }

    const fetchUser = async () => {
      try {
        const res = await axios.get(
          `http://localhost/api/users.php?id=${currentUser.id}`
        );
        setUser(res.data.data);
        setName(res.data.data.name);
        setEmail(res.data.data.email);
        setPhone(res.data.data.phone || "");
      } catch (err) {
        console.error("Failed to retrieve user data", err);
      }
    };

    fetchUser();
  }, [navigate]);

  const handleLogout = () => {
    localStorage.removeItem("currentUser");
    navigate("/login");
  };

  const handleUpdate = async () => {
    if (!user?.id) {
      alert("User ID not found");
      return;
    }

    const formData = new FormData();
    formData.append("id", user.id);
    formData.append("name", name);
    formData.append("email", email);
    formData.append("phone", phone);
    if (selectedFile) {
      formData.append("profile_picture", selectedFile);
    }

    try {
      const res = await axios.post("http://localhost/api/users.php", formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });

      if (res.data.status === "success") {
        setUser(res.data.data);
        setEditing(false);
        localStorage.setItem("currentUser", JSON.stringify(res.data.data));
        alert("Profil berhasil diperbarui");
      } else {
        alert("Gagal update profil: " + res.data.message);
      }
    } catch (error) {
      alert("Gagal update profil: " + error.message);
    }
  };

  if (!user) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gray-100">
        <p className="text-xl text-gray-700">Loading profile...</p>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-100 py-10 px-4">
      <div className="max-w-3xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <div className="bg-gradient-to-r from-gray-800 to-gray-600 px-6 py-8 text-white text-center">
          {user.profile_picture ? (
            <img
              src={`http://localhost/api/${user.profile_picture}`}
              alt="Profile"
              className="w-24 h-24 mx-auto mb-4 rounded-full object-cover border-4 border-white"
            />
          ) : (
            <FaUserCircle className="text-7xl mx-auto mb-4" />
          )}
          <h2 className="text-3xl font-bold">{user.name}</h2>
          <p className="text-sm opacity-90">{user.email}</p>
        </div>

        <div className="px-8 py-6">
          <h3 className="text-xl font-semibold text-gray-700 mb-4">
            Account Information
          </h3>
          {editing ? (
            <div className="space-y-4">
              <input
                type="text"
                className="w-full p-2 border rounded"
                value={name}
                onChange={(e) => setName(e.target.value)}
              />
              <input
                type="email"
                className="w-full p-2 border rounded"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
              />
              <input
                type="text"
                className="w-full p-2 border rounded"
                value={phone}
                onChange={(e) => setPhone(e.target.value)}
              />
              <input
                type="file"
                onChange={(e) => setSelectedFile(e.target.files[0])}
              />
              <div className="flex gap-4 mt-4">
                <button
                  onClick={handleUpdate}
                  className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                >
                  Save
                </button>
                <button
                  onClick={() => setEditing(false)}
                  className="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
                >
                  Cancel
                </button>
              </div>
            </div>
          ) : (
            <ul className="text-gray-600 space-y-3">
              <li className="flex items-center gap-3">
                <FaUserCircle />
                <span>
                  <strong>Name:</strong> {user.name}
                </span>
              </li>
              <li className="flex items-center gap-3">
                <FaEnvelope />
                <span>
                  <strong>Email:</strong> {user.email}
                </span>
              </li>
              <li className="flex items-center gap-3">
                <FaPhone />
                <span>
                  <strong>Phone Number:</strong> {user.phone || "-"}
                </span>
              </li>
              <li className="flex items-center gap-3">
                <FaCalendarAlt />
                <span>
                  <strong>Login Date:</strong> {loginTime.split(",")[0]}
                </span>
              </li>
              <li className="flex items-center gap-3">
                <FaClock />
                <span>
                  <strong>Login Time:</strong> {loginTime.split(",")[1]}
                </span>
              </li>
              <li>
                <button
                  onClick={() => setEditing(true)}
                  className="mt-3 bg-yellow-500 px-4 py-2 rounded text-black hover:bg-yellow-400"
                >
                  Edit Profile
                </button>
              </li>
            </ul>
          )}
        </div>

        <div className="px-8 py-4 flex justify-end">
          <button
            onClick={handleLogout}
            className="flex items-center gap-2 bg-red-500 text-white px-5 py-2 rounded-full hover:bg-red-600"
          >
            <FaSignOutAlt /> Logout
          </button>
        </div>
      </div>
    </div>
  );
};

export default Profile;
