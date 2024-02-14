import { useEffect, useState } from "react";
import axiosClient from "../axios-client";
import { Link } from "react-router-dom";
import { useStateContext } from "../contexts/ContextProvider";
import UserForm from "./UserForm";
import ConfirmDeleteModal from "./ConfimDeleteModel";
import UserDetail from "./UserDetail";
import IconTrash from "../assets/icons/IconTrash";
import IconEdit from "../assets/icons/IconEdit";

const Users = () => {
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(false);
  const { setNotification } = useStateContext();
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [goToPage, setGoToPage] = useState("");
  const [loadingPage, setLoadingPage] = useState(false);

  useEffect(() => {
    const getUsers = async () => {
      setLoading(true);
      setLoadingPage(false)
      try {
        const response = await axiosClient.get(`/users?page=${currentPage}`);
        const { data, meta } = response.data;
        setLoading(false);
        setUsers(data);
        setTotalPages(meta.last_page);
      } catch (error) {
        setLoading(false);
        console.error("Error fetching user data:", error);
      }
    };

    getUsers();
  }, [currentPage, loadingPage]);

  const handlePageChange = (page) => {
    setCurrentPage(page);
  };

  const handleGoToPage = () => {
    const parsedPage = parseInt(goToPage, 10);
    if (!isNaN(parsedPage) && parsedPage >= 1 && parsedPage <= totalPages) {
      setCurrentPage(parsedPage);
      setGoToPage("");
    } else {
      // Handle invalid input (e.g., show an error message)
    }
  };

  const [selectedUser, setSelectedUser] = useState(null);
  const [isUserFormVisible, setIsUserFormVisible] = useState(false);
  const [isDeleteModalOpen, setIsDeleteModalOpen] = useState(false);
  const [isModalDetailOpen, setIsModalModalOpen] = useState(false);

  const handleEditClick = (user, event) => {
    event.stopPropagation();
    setSelectedUser(user);
    setIsUserFormVisible(true);
  };

  const handleAddClick = () => {
    setSelectedUser(null);
    setIsUserFormVisible(true);
  };

  const handleDeleteClick = (user, event) => {
    event.stopPropagation();
    setSelectedUser(user);
    setIsDeleteModalOpen(true);
  };

  const handleCloseModal = () => {
    setIsDeleteModalOpen(false);
    setSelectedUser(null);
  };

  const handleConfirmDelete = (event) => {
    console.log("Deleting user:", selectedUser);
    axiosClient
      .delete(`/users/${selectedUser.id}`)
      .then((res) => {
        if (!res.data.success) {
          console.log(res.success);
          return setNotification(res.data.message);
        }
        setIsDeleteModalOpen(false);
        setLoadingPage(true);
        return setNotification(res.data.message);
      })
    
  };

  const handleOpenDetailUser = (user, event) => {
    setSelectedUser(user);
    setIsModalModalOpen(true);
  };

  const handleCloseDetailUser = (event) => {
    setIsModalModalOpen(false);
  };
  return (
    <div>
      <div
        style={{
          display: "flex",
          justifyContent: "space-between",
          alignItems: "center",
        }}
      >
        <h1>User Management</h1>
        <button onClick={() => handleAddClick()} className="btn-add">
          New User
        </button>
      </div>
      <div className="card animated fadeInDown ">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Create Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          {loading && (
            <tbody>
              <tr>
                <td colSpan="5" className="text-center">
                  Loading...
                </td>
              </tr>
            </tbody>
          )}
          {!loading && (
            <tbody>
              {users.map((U) => (
                <tr
                  className="hover-element"
                  key={U.id}
                  onClick={(event) => handleOpenDetailUser(U, event)}
                >
                  <td>{U.id}</td>
                  <td>{U.name}</td>
                  <td>{U.email}</td>
                  <td>{U.created_at}</td>
                  <td>
                    <div
                      style={{
                        display: "flex",
                      }}
                    >
                      <button
                        onClick={(event) => handleEditClick(U.id, event)}
                        className="btn-edit"
                        style={{
                          display: "flex",
                          alignItems: "center",
                        }}
                      >
                        Edit&nbsp;
                        <IconEdit iconSize="20px" iconColor="#ffffff" />
                      </button>
                      &nbsp;&nbsp;
                      <button
                        onClick={(event) => handleDeleteClick(U, event)}
                        className="btn-delete"
                        style={{
                          display: "flex",
                          alignItems: "center",
                        }}
                      >
                        Delete&nbsp;
                        <IconTrash iconSize="20px" iconColor="#ffffff" />
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          )}
        </table>
      </div>
      <div className="pagination">
        <div className="current-search">
          <p>
            Page {currentPage} of {totalPages}
          </p>
          <input
            type="text"
            value={goToPage}
            onChange={(e) => setGoToPage(e.target.value)}
            placeholder={`1-${totalPages}`}
          />
          <button onClick={handleGoToPage}>Go</button>
        </div>
        <div>
          <button
            onClick={() => handlePageChange(currentPage - 1)}
            disabled={currentPage === 1}
          >
            Previous
          </button>
          <button
            onClick={() => handlePageChange(currentPage + 1)}
            disabled={currentPage === totalPages}
          >
            Next
          </button>
        </div>
      </div>

      {isUserFormVisible && (
        <UserForm
          userId={selectedUser}
          onClose={() => setIsUserFormVisible(false)}
        />
      )}
      {isDeleteModalOpen && (
        <ConfirmDeleteModal
          isOpen={setIsDeleteModalOpen}
          onClose={handleCloseModal}
          onConfirm={handleConfirmDelete}
          userName={selectedUser ? selectedUser.name : ""}
        />
      )}

      {isModalDetailOpen && (
        <UserDetail
          selectedUser={selectedUser}
          onClose={handleCloseDetailUser}
        />
      )}
    </div>
  );
};

export default Users;
