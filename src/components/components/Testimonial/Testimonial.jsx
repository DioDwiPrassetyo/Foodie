import React, { useEffect, useState } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";
import Slider from "react-slick";
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";

const dummyTestimonials = [
  {
    id: "d1",
    name: "Siomaygoreng",
    text: "Enaknyaooooooo",
    img: "https://picsum.photos/seed/1/100/100",
  },
  {
    id: "d2",
    name: "Miegorengkuah",
    text: "Makanannya Enak, jadi nagih mau bubarin tempatnya",
    img: "https://picsum.photos/seed/2/100/100",
  },
  {
    id: "d3",
    name: "Kurang5Ribu",
    text: "Minumannya pas banget coy rasanya",
    img: "https://picsum.photos/seed/3/100/100",
  },
];

const Testimonial = () => {
  const navigate = useNavigate();
  const [dbTestimonials, setDbTestimonials] = useState([]);

  const fetchTestimonials = async () => {
    try {
      const res = await axios.get("http://localhost/api/testimonial.php"); // Ambil semua data
      if (res.data.status === "success") {
        setDbTestimonials(res.data.data);
      }
    } catch (error) {
      console.error("Gagal mengambil testimonial:", error);
    }
  };

  useEffect(() => {
    fetchTestimonials();
  }, []);

  const allTestimonials = [
    ...dbTestimonials.map((t) => ({
      id: t.id,
      name: t.name,
      text: t.message,
      img: `https://picsum.photos/seed/${t.id}/100/100`, // Bisa diganti dari DB kalau punya avatar
    })),
    ...dummyTestimonials,
  ];

  const settings = {
    dots: true,
    arrows: false,
    infinite: true,
    speed: 500,
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 3000,
    cssEase: "linear",
    pauseOnHover: true,
    pauseOnFocus: true,
  };

  return (
    <div className="py-16 px-4 bg-[#222831] text-white">
      <div className="text-center mb-8 max-w-xl mx-auto">
        <h2 className="text-4xl font-bold mb-2">Testimonial</h2>
        <p className="text-base text-gray-300 mb-4">
          Send us your testimonial about your experience dining at our restaurant. Gamsahabnida!!
        </p>
        <button
          onClick={() => navigate("/AddTestimonials")}
          className="bg-yellow-500 text-black px-4 py-2 rounded hover:bg-yellow-400 transition"
        >
          + Add Testimonial
        </button>
      </div>

      <div className="max-w-2xl mx-auto mt-8">
        <Slider {...settings}>
          {allTestimonials.map(({ id, name, text, img }) => (
            <div key={id} className="px-4">
              <div className="bg-gray-100 text-black rounded-xl shadow-md p-6 text-center relative min-h-[240px]">
                <img
                  src={img}
                  alt={name}
                  className="w-20 h-20 rounded-full mx-auto mb-4 object-cover"
                />
                <p className="text-sm text-yellow-700 italic mb-4">"{text}"</p>
                <h3 className="text-lg font-semibold">{name}</h3>
                <p className="text-5xl text-gray-300 absolute top-2 right-4 font-serif">“</p>
              </div>
            </div>
          ))}
        </Slider>
      </div>
    </div>
  );
};

export default Testimonial;
