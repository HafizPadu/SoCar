// Admin Panel JavaScript

document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin panel loaded');
    
    // Initialize admin functionality
    initAdminPanel();
    
    // Check if user is logged in
    checkAuth();
});

function initAdminPanel() {
    // Mobile sidebar toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.getElementById('sidebar');
    
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            if (sidebar && sidebar.classList.contains('active') && 
                !sidebar.contains(e.target) && 
                !menuToggle.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        }
    });
    
    // Initialize tooltips
    initTooltips();
    
    // Initialize notifications
    initNotifications();
    
    // Initialize search functionality
    initSearch();
}

function checkAuth() {
    // Simple authentication check
    // In a real app, this would check session/token
    const currentPage = window.location.pathname;
    
    // Don't check on login page
    if (currentPage.includes('index.html') || currentPage.endsWith('admin/')) {
        return;
    }
    
    // Check if user is logged in (simple demo check)
    const isLoggedIn = localStorage.getItem('adminLoggedIn') === 'true';
    
    if (!isLoggedIn) {
        // Redirect to login page
        window.location.href = 'index.html';
        return;
    }
}

function logout() {
    if (confirm('Are you sure you want to logout?')) {
        localStorage.removeItem('adminLoggedIn');
        window.location.href = 'index.html';
    }
}

function initTooltips() {
    // Initialize tooltips for buttons
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', function(e) {
            const tooltipText = this.getAttribute('data-tooltip');
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = tooltipText;
            
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.position = 'fixed';
            tooltip.style.top = (rect.top - tooltip.offsetHeight - 10) + 'px';
            tooltip.style.left = (rect.left + rect.width / 2 - tooltip.offsetWidth / 2) + 'px';
            
            this._tooltip = tooltip;
        });
        
        element.addEventListener('mouseleave', function() {
            if (this._tooltip) {
                this._tooltip.remove();
                delete this._tooltip;
            }
        });
    });
}

function initNotifications() {
    const notificationBtn = document.querySelector('.notification-btn');
    
    if (notificationBtn) {
        notificationBtn.addEventListener('click', function() {
            showNotifications();
        });
    }
}

function showNotifications() {
    // Create notifications dropdown
    const dropdown = document.createElement('div');
    dropdown.className = 'notifications-dropdown';
    dropdown.innerHTML = `
        <div class="dropdown-header">
            <h4>Notifications</h4>
            <button class="btn-mark-read">Mark all as read</button>
        </div>
        <div class="dropdown-list">
            <div class="notification-item unread">
                <div class="notification-icon">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div class="notification-content">
                    <p><strong>New booking received</strong></p>
                    <p>John Doe booked a car for 3 days</p>
                    <span class="notification-time">10 min ago</span>
                </div>
            </div>
            <div class="notification-item unread">
                <div class="notification-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="notification-content">
                    <p><strong>Car maintenance due</strong></p>
                    <p>Toyota Camry needs routine check</p>
                    <span class="notification-time">2 hours ago</span>
                </div>
            </div>
            <div class="notification-item">
                <div class="notification-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="notification-content">
                    <p><strong>New customer registered</strong></p>
                    <p>Sarah Johnson created account</p>
                    <span class="notification-time">5 hours ago</span>
                </div>
            </div>
        </div>
        <div class="dropdown-footer">
            <a href="notifications.html">View all notifications</a>
        </div>
    `;
    
    // Position dropdown
    const btnRect = document.querySelector('.notification-btn').getBoundingClientRect();
    dropdown.style.position = 'fixed';
    dropdown.style.top = (btnRect.bottom + 10) + 'px';
    dropdown.style.right = (window.innerWidth - btnRect.right - 10) + 'px';
    
    document.body.appendChild(dropdown);
    
    // Close dropdown when clicking outside
    setTimeout(() => {
        document.addEventListener('click', function closeDropdown(e) {
            if (!dropdown.contains(e.target) && !notificationBtn.contains(e.target)) {
                dropdown.remove();
                document.removeEventListener('click', closeDropdown);
            }
        });
    }, 100);
    
    // Mark as read functionality
    dropdown.querySelector('.btn-mark-read').addEventListener('click', function() {
        dropdown.querySelectorAll('.notification-item').forEach(item => {
            item.classList.remove('unread');
        });
        // Update badge count
        const badge = document.querySelector('.notification-badge');
        if (badge) {
            badge.textContent = '0';
            badge.style.display = 'none';
        }
    });
}

function initSearch() {
    const searchInput = document.querySelector('.topbar-search input');
    
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && this.value.trim()) {
                performSearch(this.value.trim());
            }
        });
        
        // Add search icon click
        const searchIcon = document.querySelector('.topbar-search i');
        if (searchIcon) {
            searchIcon.addEventListener('click', function() {
                if (searchInput.value.trim()) {
                    performSearch(searchInput.value.trim());
                }
            });
        }
    }
}

function performSearch(query) {
    // Show search results modal
    const modal = document.createElement('div');
    modal.className = 'modal-overlay active';
    modal.innerHTML = `
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3>Search Results for "${query}"</h3>
                <button class="modal-close" onclick="this.closest('.modal-overlay').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="search-results">
                    <div class="search-loading">
                        <div class="spinner"></div>
                        <p>Searching...</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Simulate search API call
    setTimeout(() => {
        const resultsContainer = modal.querySelector('.search-results');
        resultsContainer.innerHTML = `
            <div class="result-category">
                <h4>Bookings (3 results)</h4>
                <div class="result-item">
                    <i class="fas fa-calendar-check"></i>
                    <div>
                        <p><strong>Booking #SR-001250</strong></p>
                        <p>John Smith - Toyota Camry - $135.00</p>
                    </div>
                </div>
            </div>
            <div class="result-category">
                <h4>Cars (2 results)</h4>
                <div class="result-item">
                    <i class="fas fa-car"></i>
                    <div>
                        <p><strong>Toyota Camry</strong></p>
                        <p>Sedan - $45/day - Available</p>
                    </div>
                </div>
            </div>
        `;
    }, 1000);
    
    // Close on overlay click
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.remove();
        }
    });
}

// Table functions
function confirmDelete(id, type) {
    if (confirm(`Are you sure you want to delete this ${type}? This action cannot be undone.`)) {
        // In real app, make API call to delete
        console.log(`Deleting ${type} with ID: ${id}`);
        
        // Show success message
        showToast(`${type} deleted successfully`, 'success');
        
        // Remove row from table
        const row = document.querySelector(`[data-id="${id}"]`);
        if (row) {
            row.remove();
        }
    }
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
        <button class="toast-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toast.parentElement) {
            toast.remove();
        }
    }, 5000);
}

// Export data
function exportData(format = 'csv') {
    // Show export modal
    const modal = document.createElement('div');
    modal.className = 'modal-overlay active';
    modal.innerHTML = `
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <h3>Export Data</h3>
                <button class="modal-close" onclick="this.closest('.modal-overlay').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="export-options">
                    <label>
                        <input type="radio" name="exportFormat" value="csv" checked>
                        CSV Format
                    </label>
                    <label>
                        <input type="radio" name="exportFormat" value="excel">
                        Excel Format
                    </label>
                    <label>
                        <input type="radio" name="exportFormat" value="pdf">
                        PDF Format
                    </label>
                </div>
                <div class="export-period">
                    <label>Date Range</label>
                    <select>
                        <option>Last 7 days</option>
                        <option>This month</option>
                        <option>Last month</option>
                        <option>Custom range</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="this.closest('.modal-overlay').remove()">Cancel</button>
                <button class="btn btn-primary" onclick="startExport()">Export</button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    function startExport() {
        const format = modal.querySelector('input[name="exportFormat"]:checked').value;
        showToast(`Exporting data as ${format.toUpperCase()}...`, 'info');
        modal.remove();
        
        // Simulate export process
        setTimeout(() => {
            showToast('Data exported successfully!', 'success');
        }, 2000);
    }
    
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.remove();
        }
    });
}

// Add to global scope
window.logout = logout;
window.confirmDelete = confirmDelete;
window.exportData = exportData;