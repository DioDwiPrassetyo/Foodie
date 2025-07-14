import React from "react";

export default function MenuCategory({
  categories,
  activeCategory,
  setActiveCategory,
}) {
  return (
    <div className="flex space-x-4 mb-4">
      {categories.map((category) => (
        <button
          key={category}
          className={`px-4 py-2 rounded-full font-semibold transition ${
            category === activeCategory
              ? "bg-orange-500 text-white"
              : "bg-gray-700 text-white hover:bg-gray-600"
          }`}
          onClick={() => setActiveCategory(category)}
        >
          {category}
        </button>
      ))}
    </div>
  );
}