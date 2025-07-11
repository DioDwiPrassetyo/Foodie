import React, { useState, useEffect } from "react";
import axios from "axios";

const AddTestimonial = () => {
  let currentUserData;
  try {
    currentUserData = JSON.parse(localStorage.getItem("currentUser"));
  } catch (e) {
    console.error("Gagal parse localStorage:", e);
    currentUserData = null;
  }

  const [name, setName] = useState(currentUserData?.name || "");
  const [message, setMessage] = useState("");
  const [submitted, setSubmitted] = useState(false);
  const [error, setError] = useState("");

  if (!currentUserData || !currentUserData.id || !currentUserData.name) {
    return (
      <div className="min-h-screen flex items-center justify-center text-red-400 bg-gray-900">
        <p>You are not logged in or your user data is incomplete.</p>
      </div>
    );
  }

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (!message.trim()) {
      setError("Testimonials cannot be empty.");
      return;
    }

    const payload = {
      id_users: currentUserData.id,
      name: currentUserData.name,
      message: message,
    };

    try {
      const res = await axios.post("http://localhost/api/testimonial.php", payload, {
        headers: {
          "Content-Type": "application/json",
        },
      });

      if (res.data.status === "success") {
        setSubmitted(true);
        setMessage("");
        setError("");
      } else {
        setError("Failed to save testimonial. " + (res.data.message || ""));
      }
    } catch (err) {
      setError(
        "An error occurred while sending data. " +
          (err.response?.data?.message || err.message)
      );
    }
  };

  return (
    <div className="min-h-screen flex flex-col items-center justify-center bg-gray-900 text-white px-4 py-12">
      <div className="w-full max-w-xl bg-gray-800 p-8 rounded-lg shadow-lg">
        <h1 className="text-3xl font-bold mb-4 text-center text-yellow-400">
          Leave a Testimonial
        </h1>
        <p className="text-center text-gray-300 mb-6">
          Share your experience while dining at our restaurant!
        </p>

        {submitted && (
          <div className="mb-4 text-green-400 text-center">
            Testimonial successfully submitted!
          </div>
        )}
        {error && (
          <div className="mb-4 text-red-400 text-center">{error}</div>
        )}

        <form onSubmit={handleSubmit} className="flex flex-col gap-4">
          <input
            type="text"
            value={name}
            readOnly
            className="p-3 rounded border border-gray-600 bg-gray-700 text-white"
          />

          <textarea
            className="p-4 rounded border border-gray-600 bg-gray-700 text-white resize-none min-h-[120px]"
            placeholder="Write your testimonial..."
            value={message}
            onChange={(e) => setMessage(e.target.value)}
            required
          />

          <button
            type="submit"
            className="bg-yellow-500 text-black font-semibold py-2 px-6 rounded hover:bg-yellow-400 transition"
          >
            Submit
          </button>
        </form>

        <div className="mt-6 text-center">
          <button
            onClick={() => window.location.href = "/AllTestimonials"}
            className="text-yellow-400 underline hover:text-yellow-300 transition"
          >
            See All Testimonial
          </button>
        </div>
      </div>
    </div>
  );
};

export default AddTestimonial;
