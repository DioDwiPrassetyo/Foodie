import React, { useState, useEffect, useCallback } from "react";
import axios from "axios";
import Footer from "../../components/Footer/Footer";
import Bg from "../../assets/bg/resto.jpg";
import toast from "react-hot-toast";
import Swal from "sweetalert2";
import { useSearchParams } from "react-router-dom";

const Reservation = () => {
  const currentUser = JSON.parse(localStorage.getItem("currentUser"));
  const [searchParams] = useSearchParams();

  const [formData, setFormData] = useState({
    date: "",
    time: "",
    guests: "",
    firstName: "",
    lastName: "",
    email: currentUser?.email || "",
    phone: currentUser?.phone || "",
    comment: "",
    id: null,
  });

  const [reservations, setReservations] = useState([]);

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const fetchReservations = useCallback(async () => {
    try {
      const res = await axios.get(`http://localhost/api/reservation.php?id_users=${currentUser.id}`);
      const userReservations = res.data.data.filter(
        (r) => String(r.id_users) === String(currentUser?.id)
      );
      setReservations(userReservations);
    } catch (err) {
      toast.error("Failed to retrieve reservation data.");
    }
  }, [currentUser?.id]);

  useEffect(() => {
    if (currentUser?.id) fetchReservations();
  }, [currentUser?.id, fetchReservations]);

  // ✅ Cek apakah edit mode
  useEffect(() => {
    const isEdit = searchParams.get("edit") === "true";
    if (isEdit) {
      const fullName = searchParams.get("name") || "";
      const [firstName, ...rest] = fullName.split(" ");
      const lastName = rest.join(" ");

      setFormData({
        id: searchParams.get("id"),
        date: searchParams.get("reservation_date") || "",
        time: searchParams.get("reservation_time") || "",
        guests: searchParams.get("total_person") || "",
        comment: searchParams.get("message") || "",
        email: searchParams.get("email") || currentUser?.email || "",
        phone: searchParams.get("phone") || currentUser?.phone || "",
        firstName,
        lastName,
      });

      window.scrollTo({ top: 0, behavior: "smooth" });
    }
  }, [searchParams]);

  const handleSubmit = async (e) => {
    e.preventDefault();

    const selectedDate = new Date(formData.date);
    const year = selectedDate.getFullYear();
    if (year < 2025 || formData.time < "10:00" || formData.time > "22:00") {
      toast.error("Minimum date is 2025 and time is 10:00 - 22:00");
      return;
    }

    const reservationData = {
      id_users: currentUser?.id,
      name: `${formData.firstName} ${formData.lastName}`,
      email: formData.email,
      phone: formData.phone,
      reservation_date: formData.date,
      reservation_time: formData.time,
      total_person: formData.guests,
      message: formData.comment,
    };

    try {
      let res;
      if (formData.id) {
        reservationData.id = formData.id;
        res = await axios.put("http://localhost/api/reservation.php", reservationData);
      } else {
        res = await axios.post("http://localhost/api/reservation.php", reservationData);
      }

      if (res.data.status === "success") {
        toast.success(formData.id ? "Reservation updated!" : "Reservation successful!");

        // Reset form
        setFormData({
          date: "",
          time: "",
          guests: "",
          firstName: "",
          lastName: "",
          email: currentUser.email,
          phone: currentUser.phone,
          comment: "",
          id: null,
        });

        fetchReservations();

        // Redirect jika update
        if (formData.id) {
          setTimeout(() => {
            window.location.href = "/MyReservation";
          }, 1000);
        }
      } else {
        toast.error("Failed to save data");
      }
    } catch (err) {
      toast.error("An error occurred while saving.");
    }
  };

  return (
    <div className="flex flex-col min-h-screen">
      <div
        className="flex-grow flex flex-col items-center justify-center px-4 py-10"
        style={{
          backgroundImage: `url(${Bg})`,
          backgroundSize: "cover",
          backgroundPosition: "center",
          backgroundAttachment: "fixed",
        }}
      >
        <h2 className="text-4xl font-bold mb-8 text-white text-center drop-shadow-[2px_2px_4px_rgba(0,0,0,0.8)]">
          Book a Table
        </h2>

        {/* FORM */}
        <div className="w-full max-w-6xl mx-auto bg-white bg-opacity-90 p-8 rounded-lg shadow-lg">
          <form onSubmit={handleSubmit} className="flex flex-col md:flex-row gap-8 text-black">
            {/* LEFT */}
            <div className="w-full md:w-1/2 flex flex-col gap-4">
              <h3 className="text-2xl font-semibold text-[#093035]">
                {formData.id ? "Edit Reservation" : "Make a Reservation"}
              </h3>
              <div className="flex gap-4">
                <input type="date" name="date" value={formData.date} onChange={handleChange} className="w-full border p-2" required />
                <input type="time" name="time" value={formData.time} onChange={handleChange} className="w-full border p-2" required />
              </div>
              <input type="number" name="guests" value={formData.guests} onChange={handleChange} placeholder="Guests" className="w-full border p-2" required />
              <div className="flex gap-4">
                <input type="text" name="firstName" value={formData.firstName} onChange={handleChange} placeholder="First Name" className="w-full border p-2" required />
                <input type="text" name="lastName" value={formData.lastName} onChange={handleChange} placeholder="Last Name" className="w-full border p-2" required />
              </div>
            </div>

            {/* RIGHT */}
            <div className="w-full md:w-1/2 flex flex-col gap-4 mt-2 md:mt-10">
              <div className="flex gap-4">
                <input type="email" name="email" value={formData.email} onChange={handleChange} className="w-full border p-2" placeholder="Email" required />
                <input type="tel" name="phone" value={formData.phone} onChange={handleChange} className="w-full border p-2" placeholder="Phone" required />
              </div>
              <textarea name="comment" value={formData.comment} onChange={handleChange} className="w-full border p-2 h-24" placeholder="Comment" />
              <button type="submit" className="bg-[#393E46] text-[#D4AF37] py-3 px-4 rounded-lg hover:bg-[#2F343A] transition text-lg">
                {formData.id ? "Update Reservation" : "Book Now"}
              </button>
            </div>
          </form>
        </div>

        {/* LINK KE MyReservation */}
        <div className="w-full max-w-4xl mt-10 bg-white bg-opacity-90 p-6 rounded-lg shadow text-center">
          <p className="mb-4 text-[#093035] text-lg">Want to see a list of your reservations?</p>
          <button
            onClick={() => window.location.href = "/MyReservation"}
            className="bg-[#393E46] text-[#D4AF37] py-3 px-6 rounded-lg hover:bg-[#2F343A] transition text-lg"
          >
            View My Reservations
          </button>
        </div>
      </div>
    </div>
  );
};

export default Reservation;
