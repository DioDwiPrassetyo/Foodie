import React, { useState } from "react";

const ContactForm = () => {
  const [formData, setFormData] = useState({ name: "", email: "", phone: "", foundUs: "" });

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    alert(`Thank you, ${formData.name}! Your message has been sent.`);
  };

  return (
    <div className="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
      <h2 className="text-3xl font-poppins font-semibold mb-4">
        <span className="text-black">Get In</span> <span className="text-[#bf9a22]">Touch</span>
      </h2>
      <p className="text-gray-600 mb-6">We’d love to hear from you! Fill out the form below and we’ll be in touch soon.</p>
      
      <form onSubmit={handleSubmit} className="space-y-4">
        <input type="text" name="name" placeholder="Name*" className="w-full border rounded-md p-2" required onChange={handleChange} />
        <input type="email" name="email" placeholder="Email" className="w-full border rounded-md p-2" onChange={handleChange} />
        <input type="tel" name="phone" placeholder="Phone number*" className="w-full border rounded-md p-2" required onChange={handleChange} />
        <select name="foundUs" className="w-full border rounded-md p-2" onChange={handleChange}>
          <option value="">How did you find us?</option>
          <option value="Google">Google</option>
          <option value="Social Media">Social Media</option>
          <option value="Word of Mouth">Word of Mouth</option>
        </select>
        <button type="submit" className="bg-gray-800 text-[#bf9a22] w-full py-2 rounded-md hover:bg-gray-900">SEND</button>
      </form>

      {/* Contact Info Section */}
      <div className="mt-6 border-t pt-4">
        <p className="text-lg font-poppins font-semibold text-gray-800">Contact Us:</p>
        
        <div className="flex items-center gap-3 mt-2">
          <img src="https://cdn-icons-png.flaticon.com/128/126/126509.png" alt="Phone Icon" className="w-6 h-6" />
          <p className="text-black"><strong>Phone:</strong> +62 812-3456-7890</p>
        </div>

        <div className="flex items-center gap-3 mt-2">
          <img src="https://cdn-icons-png.flaticon.com/128/732/732200.png" alt="Email Icon" className="w-6 h-6" />
          <p className="text-black"><strong>Email:</strong> info@bimbimbox.com</p>
        </div>
      </div>
    </div>
  );
};

export default ContactForm;
