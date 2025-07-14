import React from "react";
import FbLogo from '../../assets/sosmed/fb.png'
import IgLogo from '../../assets/sosmed/ig.png'

const Footer = () => {
  return (
    <footer className="bg-[#393E46] text-[#EDDFD3] py-8 px-16 w-[auto] min-h-[240px] flex flex-col items-center">
      {/* Restaurant Name - Centered Properly */}
      <div className="flex flex-col items-center">
        <h2 className="text-[36px] font-[Piazzolla] text-yellow-500 tracking-wide">
          BIMBIMBOX
        </h2>
        <h3 className="text-[20px] font-[Petrona] text-[#EDDFD3] relative bottom-[4px] tracking-wide">
          RESTAURANT
        </h3>
      </div>

      {/* Description - Slightly Wider but Centered */}
      <p className="font-[Poppins] text-[16px] max-w-[800px] mt-4 text-center">
        At BIMBIMBOX RESTAURANT, we serve authentic dishes crafted with fresh
        ingredients and passion. Enjoy a delightful dining experience with
        expertly prepared meals, where every bite reflects our commitment to
        quality and taste.
      </p>

      {/* Operating Hours - Single line format */}
      <p className="font-[Poppins] text-[16px] mt-5 font-medium text-center">
        Monday - Friday: 10:00 AM - 10:00 PM | Saturday - Sunday: 10:00 AM -
        11:00 PM
      </p>

     {/* Social Media Icons - Centered at the Bottom */}
      <div className="flex gap-6 mt-6">
        <a
          href="https://www.facebook.com"
          target="_blank"
          rel="noopener noreferrer"
        >
          <img
            src={FbLogo}
            alt="Facebook"
            className="w-8 h-8 hover:opacity-80 transition"
          />
        </a>
        <a
          href="https://www.instagram.com"
          target="_blank"
          rel="noopener noreferrer"
        >
          <img
            src={IgLogo}
            alt="Instagram"
            className="w-8 h-8 hover:opacity-80 transition"
          />
        </a>
      </div>
    </footer>
  );
};

export default Footer;