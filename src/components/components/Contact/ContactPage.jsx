import React from "react";
import ContactForm from "../../components/Contact/ContactForm";
import MapSection from "../../components/Contact/MapSection";
import MenuBackground from "../../assets/bg/resto.jpg";

const ContactPage = () => {
  return (
    <div
      className="min-h-screen flex flex-col items-center justify-center text-black p-6"
      style={{
        backgroundImage: `url(${MenuBackground})`,
        backgroundSize: "cover",
        backgroundPosition: "center",
        backgroundAttachment: "fixed",
      }}
    >
      <h2 className="text-4xl font-bold mb-8 text-grey-700 p-6">Contact Us</h2>

      <div className="w-full max-w-5xl flex flex-col md:flex-row gap-8 bg-black bg-opacity-50 p-6 rounded-lg shadow-lg">
        <ContactForm />
        <MapSection />
      </div>
    </div>
  );
};

export default ContactPage;
