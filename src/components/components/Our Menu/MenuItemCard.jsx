import React from "react";

export default function MenuItemCard({ name, price, image }) {
  return (
    <div className="bg-white bg-opacity-80 rounded-lg shadow-md overflow-hidden w-40">
      <img src={image} alt={name} className="w-full h-32 object-cover" />
      <div className="p-2 text-center">
        <h3 className="font-bold text-gray-800">{name}</h3>
        <p className="text-gray-600">{price}</p>
      </div>
    </div>
  );
}