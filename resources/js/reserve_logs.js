// Reservation Logs JavaScript Functions
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the page
    initializeReservationLogs();
});

// Sample data for demonstration (replace with actual API calls)
let reservationsData = [
    {
        id: 1,
        name: 'John Doe',
        email: 'john.doe@email.com',
        date: '2024-07-15',
        time: '2:00 PM - 6:00 PM',
        message: 'Birthday celebration for my daughter...',
        venue: 'Main Hall',
        eventType: 'Birthday Party',
        theme: 'Princess Theme',
        receipt: 'receipt_001.pdf',
        status: 'active'
    },
    {
        id: 2,
        name: 'Jane Smith',
        email: 'jane.smith@email.com',
        date: '2024-07-20',
        time: '10:00 AM - 2:00 PM',
        message: 'Corporate meeting for quarterly review...',
        venue: 'Conference Room',
        eventType: 'Corporate Event',
        theme: 'Professional',
        receipt: 'receipt_002.pdf',
        status: 'completed'
    },
    {
        id: 3,
        name: 'Mike Johnson',
        email: 'mike.johnson@email.com',
        date: '2024-07-25',
        time: '6:00 PM - 11:00 PM',
        message: 'Wedding reception celebration...',
        venue: 'Garden',
        eventType: 'Wedding',
        theme: 'Rustic Garden',
        receipt: 'receipt_003.pdf',
        status: 'canceled'
    }
];

// Pagination variables
let currentPage = 1;
let itemsPerPage = 10;

function initializeReservationLogs() {
    setupEventListeners();
    renderTable();
    setupPagination();
}

function setupEventListeners() {
    // Pagination
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    if (prevBtn) {
        prevBtn.addEventListener('click', () => changePage(-1));
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', () => changePage(1));
    }

    // Status dropdown changes
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('status-dropdown')) {
            handleStatusChange(e.target);
        }
    });
}

// Render table
function renderTable() {
    const tbody = document.querySelector('.reservation-table tbody');
    if (!tbody) return;
    
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const currentData = reservationsData.slice(startIndex, endIndex);
    
    tbody.innerHTML = '';
    
    if (currentData.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="11" style="text-align: center; padding: 40px;">
                    No reservations found.
                </td>
            </tr>
        `;
        return;
    }
    
    currentData.forEach(reservation => {
        const row = createTableRow(reservation);
        tbody.appendChild(row);
    });
}

function createTableRow(reservation) {
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>${reservation.name}</td>
        <td>${reservation.email}</td>
        <td>${reservation.date}</td>
        <td>${reservation.time}</td>
        <td>${truncateText(reservation.message, 50)}</td>
        <td>${reservation.venue}</td>
        <td>${reservation.eventType}</td>
        <td>${reservation.theme}</td>
        <td>
            <a href="#" class="receipt-link" onclick="viewReceipt('${reservation.receipt}')">View Receipt</a>
        </td>
        <td>
            <select class="status-dropdown" data-id="${reservation.id}">
                <option value="active" ${reservation.status === 'active' ? 'selected' : ''}>Active</option>
                <option value="canceled" ${reservation.status === 'canceled' ? 'selected' : ''}>Canceled</option>
                <option value="completed" ${reservation.status === 'completed' ? 'selected' : ''}>Completed</option>
            </select>
        </td>
        <td>
            <div class="action-buttons">
                <button class="btn-view" onclick="viewReservation(${reservation.id})">View</button>
                <button class="btn-delete" onclick="deleteReservation(${reservation.id})">Delete</button>
            </div>
        </td>
    `;
    return row;
}

function truncateText(text, maxLength) {
    if (text.length > maxLength) {
        return text.substring(0, maxLength) + '...';
    }
    return text;
}

// Pagination
function setupPagination() {
    const totalPages = Math.ceil(reservationsData.length / itemsPerPage);
    const pageInfo = document.querySelector('.page-info');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    if (pageInfo) {
        pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
    }
    
    if (prevBtn) {
        prevBtn.disabled = currentPage === 1;
        prevBtn.style.opacity = currentPage === 1 ? '0.5' : '1';
    }
    
    if (nextBtn) {
        nextBtn.disabled = currentPage === totalPages || totalPages === 0;
        nextBtn.style.opacity = (currentPage === totalPages || totalPages === 0) ? '0.5' : '1';
    }
}

function changePage(direction) {
    const totalPages = Math.ceil(reservationsData.length / itemsPerPage);
    const newPage = currentPage + direction;
    
    if (newPage >= 1 && newPage <= totalPages) {
        currentPage = newPage;
        renderTable();
        setupPagination();
    }
}

// Status change handler
function handleStatusChange(selectElement) {
    const reservationId = parseInt(selectElement.dataset.id);
    const newStatus = selectElement.value;
    
    // Update local data
    const reservation = reservationsData.find(r => r.id === reservationId);
    if (reservation) {
        reservation.status = newStatus;
        
        // Show confirmation
        showNotification(`Reservation status updated to ${newStatus}`, 'success');
        
        // Here you would typically make an API call to update the server
        updateReservationStatus(reservationId, newStatus);
    }
}

// CRUD Operations
function viewReservation(id) {
    const reservation = reservationsData.find(r => r.id === id);
    if (reservation) {
        showReservationModal(reservation);
    }
}

function deleteReservation(id) {
    if (confirm('Are you sure you want to delete this reservation?')) {
        // Remove from local data
        reservationsData = reservationsData.filter(r => r.id !== id);
        
        // Re-render table
        renderTable();
        setupPagination();
        
        showNotification('Reservation deleted successfully', 'success');
        
        // Here you would typically make an API call to delete from server
        deleteReservationFromServer(id);
    }
}

function viewReceipt(receiptFile) {
    // In a real application, this would open the receipt file
    showNotification(`Opening receipt: ${receiptFile}`, 'info');
    
    // You could implement actual file viewing here
    // window.open(`/receipts/${receiptFile}`, '_blank');
}

// Modal for viewing reservation details
function showReservationModal(reservation) {
    // Create modal HTML
    const modalHTML = `
        <div class="modal-overlay" id="reservationModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Reservation Details</h2>
                    <button class="modal-close" onclick="closeModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="detail-group">
                        <label>Name:</label>
                        <span>${reservation.name}</span>
                    </div>
                    <div class="detail-group">
                        <label>Email:</label>
                        <span>${reservation.email}</span>
                    </div>
                    <div class="detail-group">
                        <label>Date:</label>
                        <span>${reservation.date}</span>
                    </div>
                    <div class="detail-group">
                        <label>Time:</label>
                        <span>${reservation.time}</span>
                    </div>
                    <div class="detail-group">
                        <label>Venue:</label>
                        <span>${reservation.venue}</span>
                    </div>
                    <div class="detail-group">
                        <label>Event Type:</label>
                        <span>${reservation.eventType}</span>
                    </div>
                    <div class="detail-group">
                        <label>Theme/Motif:</label>
                        <span>${reservation.theme}</span>
                    </div>
                    <div class="detail-group">
                        <label>Status:</label>
                        <span class="status-badge status-${reservation.status}">${reservation.status}</span>
                    </div>
                    <div class="detail-group">
                        <label>Message:</label>
                        <p>${reservation.message}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn-secondary" onclick="closeModal()">Close</button>
                </div>
            </div>
        </div>
    `;
    
    // Add modal to page
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Add modal styles if not already present
    if (!document.getElementById('modalStyles')) {
        addModalStyles();
    }
    
    // Add event listeners for closing modal
    const modal = document.getElementById('reservationModal');
    const modalContent = modal.querySelector('.modal-content');
    
    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
    
    // Prevent closing when clicking inside modal content
    modalContent.addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    // Close modal with ESC key
    document.addEventListener('keydown', handleModalKeydown);
    
    // Focus trap for accessibility
    const focusableElements = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
    const firstElement = focusableElements[0];
    const lastElement = focusableElements[focusableElements.length - 1];
    
    // Focus first element
    if (firstElement) {
        firstElement.focus();
    }
    
    // Handle tab navigation
    modal.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            if (e.shiftKey) {
                if (document.activeElement === firstElement) {
                    e.preventDefault();
                    lastElement.focus();
                }
            } else {
                if (document.activeElement === lastElement) {
                    e.preventDefault();
                    firstElement.focus();
                }
            }
        }
    });
}

// Handle ESC key for modal
function handleModalKeydown(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
}

function closeModal() {
    const modal = document.getElementById('reservationModal');
    if (modal) {
        modal.remove();
    }
    
    // Remove event listeners
    document.removeEventListener('keydown', handleModalKeydown);
}

function addModalStyles() {
    const styles = `
        <style id="modalStyles">
            .modal-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
            }
            
            .modal-content {
                background: white;
                border-radius: 10px;
                max-width: 600px;
                width: 90%;
                max-height: 80vh;
                overflow-y: auto;
            }
            
            .modal-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 20px;
                border-bottom: 1px solid #eee;
            }
            
            .modal-header h2 {
                margin: 0;
                color: #007b3d;
            }
            
            .modal-close {
                background: none;
                border: none;
                font-size: 24px;
                cursor: pointer;
                color: #999;
            }
            
            .modal-body {
                padding: 20px;
            }
            
            .detail-group {
                margin-bottom: 15px;
                display: flex;
                gap: 10px;
            }
            
            .detail-group label {
                font-weight: 600;
                min-width: 120px;
                color: #333;
            }
            
            .detail-group span, .detail-group p {
                flex: 1;
                margin: 0;
            }
            
            .status-badge {
                padding: 4px 8px;
                border-radius: 4px;
                font-size: 12px;
                font-weight: 600;
                text-transform: uppercase;
            }
            
            .status-active {
                background: #d4edda;
                color: #155724;
            }
            
            .status-canceled {
                background: #f8d7da;
                color: #721c24;
            }
            
            .status-completed {
                background: #d1ecf1;
                color: #0c5460;
            }
            
            .modal-footer {
                padding: 20px;
                border-top: 1px solid #eee;
                text-align: right;
            }
            
            .btn-secondary {
                padding: 10px 20px;
                border: 1px solid #6c757d;
                background: white;
                color: #6c757d;
                border-radius: 5px;
                cursor: pointer;
            }
            
            .btn-secondary:hover {
                background: #6c757d;
                color: white;
            }
        </style>
    `;
    
    document.head.insertAdjacentHTML('beforeend', styles);
}

// Notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Add notification styles if not already present
    if (!document.getElementById('notificationStyles')) {
        addNotificationStyles();
    }
    
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function addNotificationStyles() {
    const styles = `
        <style id="notificationStyles">
            .notification {
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                border-radius: 5px;
                color: white;
                font-weight: 500;
                z-index: 1001;
                animation: slideIn 0.3s ease;
            }
            
            .notification-success {
                background: #28a745;
            }
            
            .notification-error {
                background: #dc3545;
            }
            
            .notification-info {
                background: #17a2b8;
            }
            
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        </style>
    `;
    
    document.head.insertAdjacentHTML('beforeend', styles);
}

// API Functions (replace with actual API calls)
function updateReservationStatus(id, status) {
    // Example API call
    /*
    fetch(`/api/reservations/${id}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Status updated successfully', 'success');
        } else {
            showNotification('Failed to update status', 'error');
        }
    })
    .catch(error => {
        showNotification('Error updating status', 'error');
        console.error('Error:', error);
    });
    */
}

function deleteReservationFromServer(id) {
    // Example API call
    /*
    fetch(`/api/reservations/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Reservation deleted successfully', 'success');
        } else {
            showNotification('Failed to delete reservation', 'error');
        }
    })
    .catch(error => {
        showNotification('Error deleting reservation', 'error');
        console.error('Error:', error);
    });
    */
}

// Export functions for global access
window.viewReservation = viewReservation;
window.deleteReservation = deleteReservation;
window.viewReceipt = viewReceipt;