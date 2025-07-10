import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify';
import bgimg from '../../assets/bg/vector1.png';
import Food1 from '../../assets/food/food1.png';
import Food2 from '../../assets/food/food2.png';
import Food3 from '../../assets/food/food3.png';

const ImageList = [
  { id: 1, img: Food1 },
  { id: 2, img: Food2 },
  { id: 3, img: Food3 },
];

const bgImage = {
  backgroundImage: `url(${bgimg})`,
  backgroundRepeat: 'no-repeat',
  backgroundSize: 'contain',
  backgroundPosition: 'right center',
  position: 'absolute',
  top: 0,
  right: 0,
  bottom: 0,
  left: 0,
  zIndex: 0,
  opacity: 0.8,
  pointerEvents: 'none',
};

const Hero = () => {
  const [ImageId, SetImageId] = useState(Food1);
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const navigate = useNavigate();

  useEffect(() => {
    const user = localStorage.getItem("currentUser");
    setIsLoggedIn(!!user);
  }, []);

  const handleReserve = () => {
    if (isLoggedIn) {
      navigate("/reservation");
    } else {
      toast.warning("Silakan login terlebih dahulu untuk melakukan reservasi.");
      navigate("/login");
    }
  };

  return (
    <div
      className="relative min-h-[400px] sm:min-h-[600px] duration-200 flex justify-center items-center text-white"
      style={{ backgroundColor: '#222831' }}
    >
      <div style={bgImage}></div>

      <div className="container pb-8 sm:pb-0 relative z-10">
        <div className="grid grid-cols-1 sm:grid-cols-2">
          {/* Text Section */}
          <div className="flex flex-col justify-center gap-4 pt-12 sm:pt-0 text-center sm:text-left order-2 sm:order-1">
            <h1 className="text-5xl sm:text-6xl lg:text-7xl font-bold">
              BimBimBox Restaurant
            </h1>
            <p className="text-sm sm:text-base text-gray-300">
              At BIMBIMBOX RESTAURANT, we serve authentic dishes crafted with fresh
              ingredients and passion. Enjoy a delightful dining experience with
              expertly prepared meals, where every bite reflects our commitment to
              quality and taste.
            </p>
            <div>
              <button
                onClick={handleReserve}
                className="bg-gradient-to-r from-primary to-secondary text-white px-6 py-3 rounded-full hover:scale-105 duration-200"
              >
                Reserve Now
              </button>
            </div>
          </div>

          {/* Image Section */}
          <div className="order-1 sm:order-2 min-h-[400px] sm:min-h-[450px] flex justify-center items-center relative px-4">
            <div className="flex justify-center items-center h-[320px] sm:h-[480px]">
              <img
                src={ImageId}
                alt="Selected Food"
                className="w-[220px] sm:w-[320px] transition-all duration-300 ml-4 sm:ml-20 mx-auto spin"
              />
            </div>

            <div className="absolute bottom-2 sm:bottom-6 left-1/2 sm:left-auto sm:right-0 sm:top-1/2 transform -translate-x-1/2 sm:translate-x-0 sm:-translate-y-1/2 flex gap-3 sm:flex-col bg-[#473C27]/30 rounded-full px-3 py-2">
              {ImageList.map((item) => (
                <img
                  key={item.id}
                  src={item.img}
                  alt={`Food ${item.id}`}
                  className={`w-14 h-14 sm:w-16 sm:h-16 object-contain rounded-full cursor-pointer border-2 duration-200 ${
                    ImageId === item.img
                      ? 'border-primary scale-105'
                      : 'border-transparent hover:border-white'
                  }`}
                  onClick={() => SetImageId(item.img)}
                />
              ))}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Hero;
