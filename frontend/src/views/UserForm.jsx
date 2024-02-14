import { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import axiosClient from "../axios-client";
import { useStateContext } from "../contexts/ContextProvider";

const UserForm = ({userId, onClose}) => {
  const { id } = useParams();
  const navigate = useNavigate();
  const [loading, setLoading] = useState(false);
  const [errors, setErrors] = useState(null);
  const { setNotification } = useStateContext();
  const [user, setUser] = useState({
    id: null,
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
  });
  const onSubmit = (ev) => {
    ev.preventDefault();
    if (userId) {
      axiosClient
        .put(`/users/${userId}`, user)
        .then(() => {
          // TODO show notification
          onClose();
          window.location.reload();
          setNotification("User was successfully updated");
        //   navigate("/users");
        })
        .catch((err) => {
          const response = err.response;
          if (response && response.status === 422) {
            setErrors(response.data.errors);
          }
        })
        .finally(() => {setLoading(false)});
        
    } else {
      axiosClient
        .post(`/users`, user)
        .then(() => {
          // TODO show notification
          onClose();
          window.location.reload();
          setNotification("User was successfully created");
          setLoading(true);
        })
        .catch((err) => {
          const response = err.response;
          if (response && response.status === 422) {
            setErrors(response.data.errors);
          }
        }).finally(() => {setLoading(false)});
    }
  };
  if (userId) {
    useEffect(() => {
      setLoading(true);
      axiosClient
        .get(`/users/${userId}`)
        .then(({ data }) => {
          setLoading(false);
          setUser(data);
        })
        .catch(() => {
          setLoading(false);
        });
    }, []);
  }
  return (
    <>
      {/* {userId && <h1>Update User: {user.name}</h1>} */}
      {!userId && <h1>New User</h1>}
      <div className="card animated fadeInDown modal-backdrop">
        <div className="modal">
          {loading && <div className="text-center">Loading...</div>}
          {errors && (
            <div className="alert">
              {Object.keys(errors).map((key) => (
                <p key={key}>{errors[key][0]}</p>
              ))}
            </div>
          )}
          {!loading && (
            <form onSubmit={onSubmit}>
              <input
                value={user.name}
                onChange={(ev) => setUser({ ...user, name: ev.target.value })}
                placeholder="Name"
              />
              <input
                value={user.email}
                onChange={(ev) => setUser({ ...user, email: ev.target.value })}
                placeholder="Email"
              />
              <input
                value={user.password}
                placeholder="Password"
                disabled
              />
              {/* <input
                onChange={(ev) =>
                  setUser({ ...user, password_confirmation: ev.target.value })
                }
                placeholder="Password Confirmation"
              /> */}
              <div>
                <button className="btn-edit set-margin">Save</button>
                <button type="button" className="btn-delete" onClick={onClose}>
                  Cancel
                </button>
              </div>
            </form>
          )}
        </div>
      </div>
    </>
  );
};

export default UserForm;
