// Car Filtering and Pagination
let allCars = [];
let filteredCars = [];
let currentPage = 1;
const carsPerPage = 6;

const API_BASE_URL = 'php/api';

document.addEventListener('DOMContentLoaded', function() {
    loadAllCars();
    setupEventListeners();
});

async function loadAllCars() {
    try {
        const response = await fetch(`${API_BASE_URL}/cars.php`);
        const data = await response.json();
        
        if (data.success) {
            allCars = data.cars;
            filteredCars = [...allCars];
            displayCars();
        } else {
            console.error('Failed to load cars:', data.message);
            // Fallback to sample data
            loadSampleData();
        }
    } catch (error) {
        console.error('Error loading cars:', error);
        loadSampleData();
    }
}

function setupEventListeners() {
    // Search on Enter key
    const searchInput = document.getElementById('carSearch');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchCars();
            }
        });
    }
}

function applyFilters() {
    const typeFilter = document.getElementById('carTypeFilter').value;
    const priceFilter = document.getElementById('priceFilter').value;
    const transmissionFilter = document.getElementById('transmissionFilter').value;
    
    filteredCars = allCars.filter(car => {
        // Type filter
        if (typeFilter !== 'all' && car.type !== typeFilter) return false;
        
        // Price filter
        if (priceFilter !== 'all') {
            const [min, max] = priceFilter.split('-').map(p => {
                if (p.endsWith('+')) return parseInt(p) - 1;
                return parseInt(p);
            });
            
            if (priceFilter.endsWith('+')) {
                if (car.price <= max) return false;
            } else {
                if (car.price < min || car.price > max) return false;
            }
        }
        
        // Transmission filter
        if (transmissionFilter !== 'all' && car.transmission !== transmissionFilter) return false;
        
        return true;
    });
    
    currentPage = 1;
    displayCars();
}

function resetFilters() {
    document.getElementById('carTypeFilter').value = 'all';
    document.getElementById('priceFilter').value = 'all';
    document.getElementById('transmissionFilter').value = 'all';
    document.getElementById('carSearch').value = '';
    
    filteredCars = [...allCars];
    currentPage = 1;
    displayCars();
}

function searchCars() {
    const searchTerm = document.getElementById('carSearch').value.toLowerCase();
    
    if (!searchTerm) {
        filteredCars = [...allCars];
    } else {
        filteredCars = allCars.filter(car => 
            car.name.toLowerCase().includes(searchTerm) ||
            car.type.toLowerCase().includes(searchTerm)
        );
    }
    
    currentPage = 1;
    displayCars();
}

function displayCars() {
    const carsGrid = document.getElementById('carsGrid');
    const pagination = document.getElementById('pagination');
    
    if (!carsGrid) return;
    
    // Calculate pagination
    const totalPages = Math.ceil(filteredCars.length / carsPerPage);
    const startIndex = (currentPage - 1) * carsPerPage;
    const endIndex = startIndex + carsPerPage;
    const currentCars = filteredCars.slice(startIndex, endIndex);
    
    // Display cars
    if (currentCars.length === 0) {
        carsGrid.innerHTML = `
            <div class="no-results" style="grid-column: 1/-1; text-align: center; padding: 3rem;">
                <i class="fas fa-car" style="font-size: 3rem; color: var(--gray-color); margin-bottom: 1rem;"></i>
                <h3>No cars found</h3>
                <p>Try adjusting your filters or search terms</p>
                <button onclick="resetFilters()" class="btn-primary" style="margin-top: 1rem;">
                    Reset Filters
                </button>
            </div>
        `;
    } else {
        carsGrid.innerHTML = currentCars.map(car => `
            <div class="car-card">
                <div class="car-image-container">
                    <img src="${car.image}" alt="${car.name}" class="car-image">
                    ${car.type === 'luxury' ? '<span class="car-badge">Premium</span>' : ''}
                </div>
                <div class="car-details">
                    <div class="car-title">
                        <h3 class="car-name">${car.name}</h3>
                        <div class="car-price">$${car.price}<span>/day</span></div>
                    </div>
                    <div class="car-specs">
                        <span class="car-spec"><i class="fas fa-car"></i> ${car.type.charAt(0).toUpperCase() + car.type.slice(1)}</span>
                        <span class="car-spec"><i class="fas fa-users"></i> ${car.seats} seats</span>
                        <span class="car-spec"><i class="fas fa-cog"></i> ${car.transmission.charAt(0).toUpperCase() + car.transmission.slice(1)}</span>
                        <span class="car-spec"><i class="fas fa-gas-pump"></i> ${car.fuel.charAt(0).toUpperCase() + car.fuel.slice(1)}</span>
                    </div>
                    <div class="car-features">
                        ${car.ac ? '<span class="car-feature"><i class="fas fa-snowflake"></i> A/C</span>' : ''}
                        <span class="car-feature"><i class="fas fa-music"></i> Audio</span>
                    </div>
                    <div class="car-actions">
                        <button class="btn-view-details" onclick="viewCarDetails(${car.id})">
                            <i class="fas fa-info-circle"></i> Details
                        </button>
                        <button class="book-btn" onclick="bookCar(${car.id})">
                            <i class="fas fa-calendar-check"></i> Book Now
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    // Display pagination
    if (totalPages > 1) {
        let paginationHTML = '';
        
        // Previous button
        paginationHTML += `
            <button class="page-btn ${currentPage === 1 ? 'disabled' : ''}" 
                    onclick="${currentPage > 1 ? `goToPage(${currentPage - 1})` : ''}">
                <i class="fas fa-chevron-left"></i>
            </button>
        `;
        
        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                paginationHTML += `
                    <button class="page-btn ${i === currentPage ? 'active' : ''}" 
                            onclick="goToPage(${i})">
                        ${i}
                    </button>
                `;
            } else if (i === currentPage - 2 || i === currentPage + 2) {
                paginationHTML += `<span class="page-dots">...</span>`;
            }
        }
        
        // Next button
        paginationHTML += `
            <button class="page-btn ${currentPage === totalPages ? 'disabled' : ''}" 
                    onclick="${currentPage < totalPages ? `goToPage(${currentPage + 1})` : ''}">
                <i class="fas fa-chevron-right"></i>
            </button>
        `;
        
        pagination.innerHTML = paginationHTML;
    } else {
        pagination.innerHTML = '';
    }
}

function goToPage(page) {
    currentPage = page;
    displayCars();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function viewCarDetails(carId) {
    const car = allCars.find(c => c.id === carId);
    if (car) {
        // In a real app, this would open a modal or redirect to detail page
        alert(`Details for ${car.name}:\n\n` +
              `Type: ${car.type}\n` +
              `Price: $${car.price}/day\n` +
              `Seats: ${car.seats}\n` +
              `Transmission: ${car.transmission}\n` +
              `Fuel: ${car.fuel}\n\n` +
              `Click "Book Now" to rent this vehicle.`);
    }
}

// Add to global scope
window.applyFilters = applyFilters;
window.resetFilters = resetFilters;
window.searchCars = searchCars;
window.goToPage = goToPage;
window.viewCarDetails = viewCarDetails;