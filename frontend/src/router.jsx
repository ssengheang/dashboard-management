import {Navigate, createBrowserRouter} from "react-router-dom"
import Login from "./views/Login";
import Signup from "./views/Signup";
import Users from "./views/Users";
import Orders from "./views/Orders";
import NotFound from "./views/NotFound";
import DefaultLayout from "./components/DefaultLayout";
import GuestLayout from "./components/GuestLayout";
import Product from "./views/Product";
import UserForm from "./views/UserForm";
import ProductForm from "./views/ProductForm";
import Laptops from "./views/Laptops";
import App from "./App";
import LaptopForm from "./views/LaptopForm"
import ChartDashboard from "./views/ChartDashboard";

const router = createBrowserRouter([
    {
        path: '/',
        element: <DefaultLayout />,
        children: [
            {
                path: '/',
                element: <Navigate to="/users" />
            },
            {
                path: '/products',
                element: <Product />
            },
            {
                path: '/users',
                element: <Users />
            },
            {
                path: '/users/new',
                element: <UserForm key="userCreate"/>
            },
            {
                path: '/users/:id',
                element: <UserForm key="userUpdate"/>
            },
            {
                path: '/products/new',
                element: <ProductForm key="productCreate" />
            },
            {
                path: '/products/:id',
                element: <ProductForm key="productUpdate" />
            },
            {
                path: '/laptops',
                element: <Laptops />
            },
            {
                path: '/laptops/new',
                element: <LaptopForm key="laptopCreate" />
            },
            {
                path: '/laptops/:id',
                element: <LaptopForm key="laptopUpdate" />
            },
            {
                path: '/orders',
                element: <Orders />
            },
            {
                path: '/app',
                element: <App />
            },
            {
                path: '/report-graph',
                element: <ChartDashboard />
            },

        ]
    },
    {
        path: '/',
        element: <GuestLayout />,
        children: [
            {
                path: '/login',
                element: <Login />
            },
            {
                path: '/signup',
                element: <Signup />
            },
        ]
    },
    {
        path: '*',
        element: <NotFound />
    }
])

export default router;
