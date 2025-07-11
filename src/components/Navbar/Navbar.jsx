import React, { useEffect, useState } from 'react';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import Logo from '../../assets/logo.png';
import { MdTableRestaurant } from 'react-icons/md';
import { FiChevronDown } from 'react-icons/fi';

const Navbar = () => {
  const location = useLocation();
  const navigate = useNavigate();

  const [currentUser, setCurrentUser] = useState(null);
  const [dropdownOpen, setDropdownOpen] = useState(false);

  useEffect(() => {
    const user = JSON.parse(localStorage.getItem("currentUser"));
    setCurrentUser(user);
  }, [location]);

  const handleLogout = () => {
    localStorage.removeItem("currentUser");
    setCurrentUser(null);
    navigate("/login");
  };

  const handleScrollOrNavigate = (section) => (e) => {
    e.preventDefault();
    if (location.pathname === '/') {
      document.getElementById(section)?.scrollIntoView({ behavior: 'smooth' });
    } else {
      navigate(`/#${section}`);
    }
  };

  return (
    <div className="sticky top-0 z-50 shadow-md bg-[#393E46] dark:bg-gray-900 dark:text-white duration-200">
      <div className="container mx-auto px-4">
        <div className="flex justify-between items-center py-2">
          <Link to="/">
            <img src={Logo} alt="Logo" className="w-10 filter brightness-200" />
          </Link>
          <ul className="hidden sm:flex items-center gap-4">
            <li>
              <Link to="/" className="inline-block py-3 px-3 text-white hover:text-primary">Home</Link>
            </li>
            <li>
              <Link to="/menu" className="inline-block py-3 px-3 text-white hover:text-primary">Menu</Link>
            </li>
            <li>
              <a 
                href="#about" 
                onClick={handleScrollOrNavigate('about')}
                className="inline-block py-3 px-3 text-white hover:text-primary"
              >
                About
              </a>
            </li>
            <li>
              <Link to="/contact" className="inline-block py-3 px-3 text-white hover:text-primary">Contact</Link>
            </li>
            <li>
              <a 
                href="#testimonial" 
                onClick={handleScrollOrNavigate('testimonial')}
                className="inline-block py-3 px-3 text-white hover:text-primary"
              >
                Testimonial
              </a>
            </li>

            {currentUser ? (
              <>
                {/* Reservation Button */}
                <li>
                  <Link
                    to="/reservation"
                    className="inline-flex items-center gap-2 py-2 px-4 bg-green-500 text-white rounded-full hover:bg-green-600 transition"
                  >
                    <MdTableRestaurant className="text-lg" />
                    Reservation
                  </Link>
                </li>

                {/* Dropdown User */}
                <li className="relative">
                  <button
                    onClick={() => setDropdownOpen(!dropdownOpen)}
                    className="flex items-center gap-2 text-white hover:text-yellow-400 transition"
                  >
                    Hi, {currentUser.name} <FiChevronDown />
                  </button>

                  {dropdownOpen && (
                    <div className="absolute right-0 mt-2 w-44 bg-white text-black rounded shadow-md z-50">
                      <Link
                        to="/profile"
                        className="block px-4 py-2 hover:bg-gray-100"
                        onClick={() => setDropdownOpen(false)}
                      >
                        👤 Profile
                      </Link>
                      <Link
                        to="/MyReservation"
                        className="block px-4 py-2 hover:bg-gray-100"
                        onClick={() => setDropdownOpen(false)}
                      >
                        📅 MyReservation
                      </Link>
                      <button
                        onClick={handleLogout}
                        className="w-full text-left px-4 py-2 hover:bg-gray-100"
                      >
                        🚪 Logout
                      </button>
                    </div>
                  )}
                </li>
              </>
            ) : (
              <li>
                <Link to="/login">
                  <button className="flex items-center gap-2 bg-gradient-to-r from-yellow-400 to-orange-400 text-white px-4 py-2 rounded-full shadow hover:opacity-90 duration-300 transition-all">
                    Login <MdTableRestaurant className="inline-block ml-2" />
                  </button>
                </Link>
              </li>
            )}
          </ul>
        </div>
      </div>
    </div>
  );
};

export default Navbar;
