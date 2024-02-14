import { Navigate, Outlet, Link } from "react-router-dom";
import { useStateContext } from "../contexts/ContextProvider";
import { useEffect } from "react";
import axiosClient from "../axios-client";
import IconUser from "../assets/icons/IconUser";
import IconProduct from "../assets/icons/IconProduct";
import IconOrder from "../assets/icons/IconOrder";
import IconReport from "../assets/icons/IconReport";
import Logo from '../assets/images/logo.jpg';

const styles = {
  content: {
    display: "flex",
    justifyContent: "start",
    alignItems: "center",
    margin: "5px 0",
  },
  sideBar: {
    display: "flex",
    flexDirection: "column",
    justifyContent: "center",
    height: "100vh",
    padding: "20px",
    paddingBottom: "30vh",
    // backgroundImage: "linear-gradient(to bottom, #ffffff 0%, #f0f0f0 100%)"
  },
  logoImage: {
    width: '40%',
    height: '40%'
  }
};

const DefaultLayout = () => {
  const { user, token, setUser, setToken, notification } = useStateContext();
  if (!token) {
    return <Navigate to="/login" />;
  }
  const onLogout = (ev) => {
    ev.preventDefault();
    axiosClient.post("/logout").then(() => {
      setUser({});
      setToken(null);
    });

    localStorage.removeItem('ACCESS_TOKEN');
    return window.location.reload();
  };

  useEffect(() => {
    axiosClient.get("/user").then(({ data }) => {
      setUser(data);
    });
  }, []);
  return (
    <div id="defaultLayout">
        {/* <div  style={styles.container}> */}
      <aside style={styles.sideBar}>

        
        <Link to="/users" style={styles.content}>
          <IconUser iconColor="#ffffff" />
          &nbsp; Users
        </Link>

        <Link to="/products" style={styles.content}>
          <IconProduct iconColor="#ffffff" />
          &nbsp; Produst
        </Link>
        {/* <Link to="/laptops" style={styles.content}>
          <IconProduct iconColor="#ffffff" />
          &nbsp; Laptop
        </Link> */}
        <Link to="/orders" style={styles.content}>
          <IconOrder iconColor="#ffffff" />
          &nbsp; Order
        </Link>
        <Link to="/report-graph" style={styles.content}>
          <IconReport iconColor="#ffffff" />
          &nbsp; Report
        </Link>
      </aside>
        {/* </div> */}
      <div className="content">
        <header>
          <div>
          <img src={Logo} alt="Computer Logo" style={styles.logoImage}/>
          </div>
          <div  style={{color: 'white'}}>
            Yoo! <strong style={{color: 'green', marginRight: '30px'}}>{user.name}</strong>
            <a href="#" onClick={onLogout} className="btn-logout" style={{color: 'white', border: '1px solid #b72424', padding: '5px'}}>
              Logout
            </a>
          </div>
        </header>
        <main>
          <Outlet />
        </main>
      </div>
      {notification && <div className="notification">{notification}</div>}
    </div>
  );
};

export default DefaultLayout;
