import SideBar from "./SideBar";
import NavBar from "./NavBar";
import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import Validation from "./Validation";
import axios from "axios";


axios.interceptors.request.use(
  (config) => {


    config.headers['X-User-OS'] = window.navigator.platform;
    config.headers['X-User-Browser'] = identificarNavegador(navigator.userAgent);
    config.headers.Authorization = `Bearer ` + sessionStorage.getItem("myToken");
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

function identificarNavegador(userAgent) {
  if (userAgent.includes("Edg") || userAgent.includes("Edge"))  {
      return "Microsoft Edge";
    } else if (userAgent.includes("Firefox")) {
      return "Mozilla Firefox";
    } else if (userAgent.includes("OPR") || userAgent.includes("Opera")) {
      return "Opera";
  } else if (userAgent.includes("Chrome")) {
      return "Google Chrome";

  } else if (userAgent.includes("Safari")) {
      return "Safari";
  } else {
      return "Otro Navegador ";
  }
}

export default function Layout({ children }) {

  const [sidebarOpen, setSidebarOpen] = useState(sessionStorage.getItem("sidebarOpen") == "true");
  const [data, setData] = useState({});
  const [aprove, setAprove] = useState(false);
  const validation = Validation();

  useEffect(() => {
    sessionStorage.setItem("sidebarOpen", sidebarOpen);
  }, [sidebarOpen]);

  useEffect(() => {
    validation
      .ValidationTokenPage()
      .then((data) => {
        setAprove(true);
        setData(data);
      })
      .catch((error) => {
        console.error(error);
        //navigatetoUrl("/");
      });
  }, []);

  if (!aprove) {
    return <h1>Cargando ...</h1>;
  }

  return (
    <div className="flex h-full  ">
      <SideBar sidebarController={[sidebarOpen, setSidebarOpen]} user={data} />

      <div className="flex flex-col w-full h-full min-h-screen justify-between bg-gray-200">
        <div className="">
          <NavBar sidebarController={[sidebarOpen, setSidebarOpen]} user={data} />

          {children}
        </div>
        <footer className="bg-white dark:bg-gray-700  text-center  flex justify-between py-2 px-5">
          <p className=""> Universidad BTC. Todos los derechos reservados.</p>
        </footer>
      </div>
    </div>
  );
}
