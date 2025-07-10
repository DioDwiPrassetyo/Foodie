import React from "react";

const MapSection = () => {
  return (
    <div className="w-full max-w-lg h-[450px] bg-gray-300 rounded-lg overflow-hidden">
      <iframe
        title="BimBimBox Restaurant Location"
        className="w-full h-full"
        src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d2092.9111533868027!2d-81.15238722107135!3d34.07035374143248!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sbimbimbox%20restaurant!5e1!3m2!1sen!2sus!4v1752029299942!5m2!1sen!2sus"
        allowFullScreen
        loading="lazy"
        referrerPolicy="no-referrer-when-downgrade"
      ></iframe>
    </div>
  );
};

export default MapSection;
