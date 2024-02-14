import React from "react";

const ConfirmDeleteModal = ({ isOpen, onClose, onConfirm, userName }) => {
  if (!isOpen) return null;

  return (
    <div className="modal-backdrop">
      <div className="deletion-modal">
        <div className="deletion-modal-body">
          <h1>Confirm Deletion</h1>
          {userName != "" ? <p>
            Are you sure you want to delete user <strong>{userName}</strong>?
            This action cannot be undone, and related orders will also be
            deleted.
          </p> : <p>
            Are you sure you want to delete this product?
            This action cannot be undone.
          </p>  }
          <div className="botton-deletion">
            <button className="btn-edit" onClick={onConfirm}>
              Confirm
            </button>
            <button className="btn-delete" onClick={onClose}>
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ConfirmDeleteModal;
