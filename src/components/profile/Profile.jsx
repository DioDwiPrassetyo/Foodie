import React from "react";
import { useState } from "react";
import { FaUserCircle } from "react-icons/fa";

const Profile = ({ onLogout }) => {
  const [open, setOpen] = useState(false);
  const user = JSON.parse(localStorage.getItem("currentUser"));

  const handleLogout = () => {
    localStorage.removeItem("currentUser");
    onLogout();
  };

  return (
    <div className="relative">
      <button
        onClick={() => setOpen(!open)}
        className="text-gray-700 text-3xl"
        title={user?.nama || "User"}
      >
        <FaUserCircle />
      </button>
      {open && (
        <div className="absolute right-0 mt-2 w-40 bg-white border rounded shadow-md z-50">
          <div className="p-2 border-b text-sm text-gray-700">
            {user?.nama || "Profil"}
          </div>
          <button
            onClick={handleLogout}
            className="w-full text-left p-2 text-sm text-red-600 hover:bg-gray-100"
          >
            Logout
          </button>
        </div>
      )}
    </div>
  );
};

export default Profile;
