// src/components/ui/button.jsx
import React from "react";

export const Button = ({ children, className = "", ...props }) => {
  return (
    <button
      className={`bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition ${className}`}
      {...props}
    >
      {children}
    </button>
  );
};
