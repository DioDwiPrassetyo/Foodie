import React, { useState } from "react";
import Footer from "../../components/Footer/Footer";
import Bg from "../../assets/bg/resto.jpg";

const Reservation = () => {
  const [formData, setFormData] = useState({
    date: "",
    time: "",
    guests: "",
    firstName: "",
    lastName: "",
    email: "",
    phone: "",
    comment: "",
  });

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();

    const selectedDate = new Date(formData.date);
    const year = selectedDate.getFullYear();
    const selectedTime = formData.time;

    if (year < 2025) {
      alert("Tanggal harus mulai dari tahun 2025.");
      return;
    }

    if (selectedTime < "10:00" || selectedTime > "22:00") {
      alert("Waktu harus antara pukul 10 pagi dan 10 malam.");
      return;
    }

    alert("Reservation submitted!");
  };

  return (
    <div className="flex flex-col min-h-screen">
      {/* Background + Konten */}
      <div
        className="flex-grow flex flex-col items-center justify-center px-4 py-10"
        style={{
          backgroundImage: `url(${Bg})`,
          backgroundSize: "cover",
          backgroundPosition: "center",
          backgroundAttachment: "fixed",
        }}
      >
        <h2 className="text-4xl font-bold mb-8 text-white text-center">Book a Table</h2>

        {/* Kotak konten di tengah */}
        <div className="w-full max-w-6xl mx-auto bg-white bg-opacity-80 p-8 rounded-lg shadow-lg">
          <form
            onSubmit={handleSubmit}
            className="flex flex-col md:flex-row gap-8 w-full text-black justify-center"
          >
            {/* Kiri */}
            <div className="w-full md:w-1/2 flex flex-col gap-4">
              <h2 className="text-3xl font-semibold text-[#093035] font-[Poppins] tracking-wide">
                Reservation
              </h2>

              <div className="flex gap-4">
                <div className="w-full">
                  <label className="block text-gray-700 text-sm font-semibold mb-1">Date</label>
                  <input
                    type="date"
                    name="date"
                    value={formData.date}
                    onChange={handleChange}
                    className="w-full border border-gray-300 rounded-md p-2"
                    required
                    min="2025-01-01"
                  />
                </div>
                <div className="w-full">
                  <label className="block text-gray-700 text-sm font-semibold mb-1">Time</label>
                  <input
                    type="time"
                    name="time"
                    value={formData.time}
                    onChange={handleChange}
                    className="w-full border border-gray-300 rounded-md p-2"
                    required
                    min="10:00"
                    max="22:00"
                  />
                </div>
              </div>

              <div>
                <label className="block text-gray-700 text-sm font-semibold mb-1">Number of Guests</label>
                <input
                  type="number"
                  name="guests"
                  value={formData.guests}
                  onChange={handleChange}
                  className="w-full border border-gray-300 rounded-md p-2"
                  required
                  min="1"
                />
              </div>

              <div className="flex gap-4">
                <div className="w-full">
                  <label className="block text-gray-700 text-sm font-semibold mb-1">First Name</label>
                  <input
                    type="text"
                    name="firstName"
                    value={formData.firstName}
                    onChange={handleChange}
                    className="w-full border border-gray-300 rounded-md p-2"
                    required
                  />
                </div>
                <div className="w-full">
                  <label className="block text-gray-700 text-sm font-semibold mb-1">Last Name</label>
                  <input
                    type="text"
                    name="lastName"
                    value={formData.lastName}
                    onChange={handleChange}
                    className="w-full border border-gray-300 rounded-md p-2"
                    required
                  />
                </div>
              </div>
            </div>

            {/* Kanan */}
            <div className="w-full md:w-1/2 flex flex-col gap-4 mt-2 md:mt-10">
              <div className="flex gap-4">
                <div className="w-full">
                  <label className="block text-gray-700 text-sm font-semibold mb-1">Email Address</label>
                  <input
                    type="email"
                    name="email"
                    value={formData.email}
                    onChange={handleChange}
                    className="w-full border border-gray-300 rounded-md p-2"
                    required
                  />
                </div>
                <div className="w-full">
                  <label className="block text-gray-700 text-sm font-semibold mb-1">Phone Number</label>
                  <input
                    type="tel"
                    name="phone"
                    value={formData.phone}
                    onChange={handleChange}
                    className="w-full border border-gray-300 rounded-md p-2"
                    required
                  />
                </div>
              </div>

              <div>
                <label className="block text-gray-700 text-sm font-semibold mb-1">Comment</label>
                <textarea
                  name="comment"
                  value={formData.comment}
                  onChange={handleChange}
                  className="w-full h-20 border border-gray-300 rounded-md p-2"
                />
              </div>

              <button
                type="submit"
                className="bg-[#393E46] text-[#D4AF37] font-medium font-[Poppins] py-3 px-4 rounded-lg hover:bg-[#2F343A] transition text-lg self-start"
              >
                BOOK NOW
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
};

export default Reservation;
