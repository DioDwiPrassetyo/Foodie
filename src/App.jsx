import React, { useRef, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route, useLocation } from 'react-router-dom';
import { ToastContainer } from 'react-toastify';
import "react-toastify/dist/ReactToastify.css"
import{ Toaster } from 'react-hot-toast';


import Navbar from './components/Navbar/Navbar';
import Hero from './components/Hero/Hero';
import Menu from './components/Menu/Menu';
import MenuPage from './components/Our Menu/MenuPage';
import About from './components/About/AboutUs';
import Testimonial from './components/Testimonial/Testimonial';
import Reservation from './components/Reservation/Reservation';
import ContactPage from './components/Contact/ContactPage';
import Footer from './components/Footer/Footer';
import Login from './components/Auth/Login';
import Register from './components/Auth/Register';
import Profile from './components/profile/Profile';
import MyReservation from './components/Reservation/MyReservation';
import AddTestimonials from './components/Testimonial/AddTestimonials';
import AllTestimonials from './components/Testimonial/AllTestimonials';

const HomeContent = () => {
  const aboutRef = useRef(null);
  const testimonialRef = useRef(null);
  const location = useLocation();

  useEffect(() => {
    const hash = location.hash;
    if (hash === '#about') {
      aboutRef.current?.scrollIntoView({ behavior: 'smooth' });
    } else if (hash === '#testimonial') {
      testimonialRef.current?.scrollIntoView({ behavior: 'smooth' });
    }
  }, [location]);

  return (
    <>
      <Hero />
      <Menu />
      <div ref={aboutRef} id="about">
        <About />
      </div>
      <div ref={testimonialRef} id="testimonial">
        <Testimonial />
      </div>
    </>
  );
};

const App = () => {
  return (
    <Router>
      <div className="bg-[#222831]">
        <Navbar />
        
        <Routes>
          <Route path="/" element={<HomeContent />} />
          <Route path="/menu" element={<MenuPage />} />
          <Route path="/reservation" element={<Reservation />} />
          <Route path="/contact" element={<ContactPage />} />
          <Route path="/about" element={<About />} />
          <Route path="/testimonial" element={<Testimonial />} />
          <Route path="/login" element={<Login />} />
          <Route path="/Register" element={<Register />} />
          <Route path="/Profile" element={<Profile />} />
          <Route path="/MyReservation" element={<MyReservation />} />
          <Route path="/AddTestimonials" element={<AddTestimonials />} />
          <Route path="/AllTestimonials" element={<AllTestimonials />} />
        </Routes>

        <Toaster
          position="top-center"
          toastOptions={{
          style: {
            background: '#333',
            color: '#fff',
            fontSize: '1rem',
          },
          success: {
            iconTheme: {
              primary: '#D4AF37',
              secondary: '#fff',
            },
          },
  }}
/>
        <ToastContainer position="top-right" autoClose={3000} />

        <Footer />
      </div>
    </Router>
  );
};

export default App;
