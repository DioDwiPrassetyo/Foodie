import React, { useEffect, useState } from "react";
import axios from "axios";

const AllTestimonials = () => {
  const [testimonials, setTestimonials] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    const fetchTestimonials = async () => {
      try {
        const res = await axios.get("http://localhost/api/testimonial.php");
        if (res.data.status === "success") {
          setTestimonials(res.data.data || []);
        } else {
          setError("Gagal memuat data testimonial.");
        }
      } catch (err) {
        setError("Terjadi kesalahan saat mengambil data.");
      } finally {
        setLoading(false);
      }
    };

    fetchTestimonials();
  }, []);

  return (
    <div className="min-h-screen bg-gray-900 text-white px-4 py-12">
      <div className="max-w-5xl mx-auto">
        <h1 className="text-4xl font-bold text-center mb-8 text-yellow-400">
          All Testimonials
        </h1>

        {loading ? (
          <p className="text-center text-gray-300">Loading testimonials...</p>
        ) : error ? (
          <p className="text-center text-red-400">{error}</p>
        ) : testimonials.length === 0 ? (
          <p className="text-center text-gray-400">No testimonials yet.</p>
        ) : (
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            {testimonials.map((item) => (
              <div
                key={item.id}
                className="bg-gray-800 rounded-lg p-6 shadow hover:shadow-lg transition"
              >
                <h3 className="text-xl font-semibold text-yellow-300 mb-2">
                  {item.name}
                </h3>
                <p className="text-gray-200">{item.message}</p>
              </div>
            ))}
          </div>
        )}

        <div className="mt-10 text-center">
          <button
            onClick={() => window.history.back()}
            className="mt-4 px-6 py-2 bg-yellow-500 text-black rounded hover:bg-yellow-400 transition"
          >
            Back
          </button>
        </div>
      </div>
    </div>
  );
};

export default AllTestimonials;
