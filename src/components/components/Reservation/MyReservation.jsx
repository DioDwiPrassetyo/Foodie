import React, { useEffect, useState } from "react";
import axios from "axios";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { motion } from "framer-motion";
import toast from "react-hot-toast";
import Swal from "sweetalert2";
import { FaEdit, FaTrash } from "react-icons/fa";

const MyReservations = () => {
  const [reservations, setReservations] = useState([]);
  const currentUser = JSON.parse(localStorage.getItem("currentUser"));

  const fetchReservations = async () => {
    try {
      const res = await axios.get(`http://localhost/api/reservation.php?id_users=${currentUser.id}`);
      const userReservations = res.data.data.filter(
        (r) => String(r.id_users) === String(currentUser?.id)
      );
      setReservations(userReservations);
    } catch (err) {
      toast.error("Failed to load reservation.");
    }
  };

  useEffect(() => {
    fetchReservations();
  }, []);

  const handleDelete = async (id) => {
  const confirm = await Swal.fire({
    title: "Are you sure you want to delete the reservation?",
    text: "This action cannot be undone!",
    icon: "warning", // <- kecil semua, bukan "Warning"
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, delete!",
    cancelButtonText: "Cancel",
  });

  if (confirm.isConfirmed) {
    try {
      const res = await axios.delete(`http://localhost/api/reservation.php?id=${id}`);
      if (res.data.status === "success") {
        toast.success("Reservation successfully deleted.");
        fetchReservations(); // Refresh data
      } else {
        toast.error("Failed to delete reservation.");
      }
    } catch (err) {
      toast.error("An error occurred while deleting.");
    }
  }
};


  const handleEdit = (r) => {
    const query = new URLSearchParams({
      id: r.id,
      reservation_date: r.reservation_date,
      reservation_time: r.reservation_time,
      total_person: r.total_person,
      email: r.email,
      phone: r.phone,
      message: r.message,
      name: r.name,
      edit: true,
    }).toString();
    window.location.href = `/Reservation?${query}`;
  };

  return (
    <div className="min-h-screen px-4 py-10 bg-gray-100">
      <h2 className="text-3xl font-bold text-center mb-10 text-[#093035]">My Reservations</h2>

      <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 max-w-7xl mx-auto">
        {reservations.length === 0 ? (
          <p className="text-center col-span-full text-gray-600">No reservations yet.</p>
        ) : (
          reservations.map((r) => (
            <motion.div
              key={r.id}
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.4 }}
            >
              <Card className="bg-white shadow-lg rounded-2xl p-4 border border-gray-200">
                <CardContent className="space-y-3 text-sm text-gray-700">
                  <p><strong>Name:</strong> {r.name}</p>
                  <p><strong>Date:</strong> {r.reservation_date}</p>
                  <p><strong>Time:</strong> {r.reservation_time}</p>
                  <p><strong>Number of Guests:</strong> {r.total_person}</p>
                  <p><strong>Email:</strong> {r.email}</p>
                  <p><strong>Phone Number:</strong> {r.phone}</p>
                  {r.message && <p><strong>Note:</strong> {r.message}</p>}

                  <div className="flex justify-end gap-2 pt-2">
                    <Button variant="outline" size="sm" onClick={() => handleEdit(r)} className="flex items-center gap-1">
                      <FaEdit /> Edit
                    </Button>
                    <Button variant="destructive" size="sm" onClick={() => handleDelete(r.id)} className="flex items-center gap-1">
                      <FaTrash /> Delete
                    </Button>
                  </div>
                </CardContent>
              </Card>
            </motion.div>
          ))
        )}
      </div>
    </div>
  );
};

export default MyReservations;
