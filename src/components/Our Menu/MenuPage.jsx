import React, { useState } from "react";
import MenuCategory from "../../components/Our Menu/MenuCategory";
import MenuItemCard from "../../components/Our Menu/MenuItemCard";
import MenuBackground from "../../assets/bg/menubg.jpeg"

// Import semua gambar
import patbingsu from "../../assets/allmenu/patbingsu.jpg";
import kimbap from "../../assets/allmenu/kimbap.jpg";
import kimchi from "../../assets/allmenu/kimchi.jpg";
import bibimbap from "../../assets/allmenu/bibimbap.jpg";
import bulgogi from "../../assets/allmenu/bulgogi.jpg";
import jajangmyeon from "../../assets/allmenu/jajangmyeon.jpg";
import gochujang from "../../assets/allmenu/gochujang.jpg";
import tteokbokki from "../../assets/allmenu/Tteokbokki.jpg";
import miyeokguk from "../../assets/allmenu/miyeokguk.jpg";
import haemultang from "../../assets/allmenu/haemultang.jpg";
import samgyetang from "../../assets/allmenu/samgyetang.jpg";
import dalgona from "../../assets/allmenu/dalgona.jpg";
import barleyTea from "../../assets/allmenu/barley_tea.jpg";
import soju from "../../assets/allmenu/soju.jpg";
import makgeolli from "../../assets/allmenu/makgeolli.jpg";
import omijaTea from "../../assets/allmenu/omija_tea.jpg";
import sikhye from "../../assets/allmenu/sikhye.jpg";
import sujeonggwa from "../../assets/allmenu/sujeonggwa.jpg";
import bungeoppang from "../../assets/allmenu/bungeoppang.jpg";
import dasik from "../../assets/allmenu/dasik.jpg";

const menuItems = {
  Dessert: [
    { name: "Patbingsu", price: "8.00$", originalPriceKRW: "₩11,000", image: patbingsu }, 
    { name: "Bungeoppang", price: "1/1.00$", originalPriceKRW: "₩1375", image: bungeoppang},
    { name: "Dasik", price: "3/6.00$", originalPriceKRW: "₩7,000", image: dasik},

  ],
  "Main Course": [
    { name: "Kimbap", price: "4.50$", originalPriceKRW: "₩4,000", image: kimbap }, 
    { name: "Kimchi", price: "3.50$", originalPriceKRW: "₩4,800", image: kimchi }, 
    { name: "Bibimbap", price: "6.50$", originalPriceKRW: "₩9,000", image: bibimbap },
    { name: "Bulgogi", price: "15.00$", originalPriceKRW: "₩20,000", image: bulgogi }, 
    { name: "Jajangmyeon", price: "6.00$", originalPriceKRW: "₩8,500", image: jajangmyeon }, 
    { name: "Gochujang", price: "4.50$", originalPriceKRW: "₩6,000", image: gochujang }, 
    { name: "Tteokbokki", price: "4.50$", originalPriceKRW: "₩6,000", image: tteokbokki }, 
    { name: "Miyeokguk", price: "7.50$", originalPriceKRW: "₩10,000", image: miyeokguk }, 
    { name: "Haemultang", price: "25.00$", originalPriceKRW: "₩35,000", image: haemultang }, 
    { name: "Samgyetang", price: "14.00$", originalPriceKRW: "₩18,000", image: samgyetang }, 
  ],
  Drink: [
    { name: "Dalgona", price: "4.00$", originalPriceKRW: "₩5,500", image: dalgona }, 
    { name: "Barley Tea", price: "2.00$", originalPriceKRW: "₩2,800", image: barleyTea }, 
    { name: "Soju", price: "5.50$", originalPriceKRW: "₩7,500", image: soju }, 
    { name: "Makgeolli", price: "7.00$", originalPriceKRW: "₩9,500", image: makgeolli }, 
    { name: "Omija Tea", price: "4.00$", originalPriceKRW: "₩5,500", image: omijaTea }, 
    { name: "Sikhye", price: "3.00$", originalPriceKRW: "₩4,000", image: sikhye }, 
    { name: "Sujeonggwa", price: "3.00$", originalPriceKRW: "₩4,000", image: sujeonggwa }, 
  ],
};

export default function MenuPage() {
  const [activeCategory, setActiveCategory] = useState("Main Course");

   return (
    <div
      className="min-h-screen flex flex-col items-center justify-center text-white"
      style={{
        backgroundImage: `url(${MenuBackground})`, // Menggunakan template literal
        backgroundSize: "cover",
        backgroundPosition: "center",
        backgroundAttachment: "fixed",
      }}
    >
      <h1 className="text-3xl font-bold mb-4 bg-orange-500 px-4 py-2 rounded-full">
        OUR MENU
      </h1>

      <MenuCategory
        categories={Object.keys(menuItems)}
        activeCategory={activeCategory}
        setActiveCategory={setActiveCategory}
      />

      <div className="flex justify-center mt-4 flex-wrap gap-4">
        {menuItems[activeCategory].map((item, index) => (
          <MenuItemCard key={index} {...item} />
        ))}
      </div>
    </div>
  );
}