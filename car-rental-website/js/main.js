// Main JavaScript for Car Rental Website

document.addEventListener('DOMContentLoaded', function() {
    console.log('Car Rental Website Loaded');
    
    // Mobile Menu Toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navMenu = document.querySelector('.nav-menu');
    
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            this.querySelector('i').classList.toggle('fa-bars');
            this.querySelector('i').classList.toggle('fa-times');
        });
    }
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        if (!mobileMenuBtn.contains(event.target) && !navMenu.contains(event.target)) {
            navMenu.classList.remove('active');
            if (mobileMenuBtn.querySelector('i')) {
                mobileMenuBtn.querySelector('i').classList.remove('fa-times');
                mobileMenuBtn.querySelector('i').classList.add('fa-bars');
            }
        }
    });
    
    // Load popular cars
    loadPopularCars();
    
    // Initialize date pickers
    initializeDatePickers();
    
    // Add smooth scrolling for anchor links
    addSmoothScrolling();
    
    // Add active class to current page in navigation
    highlightCurrentPage();
});

// Load Popular Cars
function loadPopularCars() {
    const carsGrid = document.getElementById('popularCars');
    if (!carsGrid) return;
    
    // Sample car data - in production, this would come from an API or database
    const popularCars = [
        {
            id: 1,
            name: "Toyota Camry",
            type: "Sedan",
            price: 45,
            seats: 5,
            transmission: "Automatic",
            fuel: "Petrol",
            image: "https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80"
        },
        {
            id: 2,
            name: "BMW X5",
            type: "SUV",
            price: 120,
            seats: 7,
            transmission: "Automatic",
            fuel: "Petrol",
            image: "https://images.unsplash.com/photo-1555212697-194d092e3b8f?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80"
        },
        {
            id: 3,
            name: "Mercedes Benz C-Class",
            type: "Luxury",
            price: 150,
            seats: 5,
            transmission: "Automatic",
            fuel: "Petrol",
            image: "https://images.unsplash.com/photo-1617814076367-b759c7d7e738?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80"
        },
        {
            id: 4,
            name: "Honda Civic",
            type: "Economy",
            price: 35,
            seats: 5,
            transmission: "Automatic",
            fuel: "Hybrid",
            image: "https://images.unsplash.com/photo-1593941707882-a5bba5338fe2?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80"
        }
    ];
    
    // Clear loading message
    carsGrid.innerHTML = '';
    
    // Add car cards
    popularCars.forEach(car => {
        const carCard = createCarCard(car);
        carsGrid.appendChild(carCard);
    });
}

// Create Car Card HTML
function createCarCard(car) {
    const div = document.createElement('div');
    div.className = 'car-card';
    div.innerHTML = `
        <img src="${car.image}" alt="${car.name}" class="car-image">
        <div class="car-details">
            <div class="car-title">
                <h3 class="car-name">${car.name}</h3>
                <div class="car-price">$${car.price}<span>/day</span></div>
            </div>
            <div class="car-specs">
                <span class="car-spec"><i class="fas fa-car"></i> ${car.type}</span>
                <span class="car-spec"><i class="fas fa-users"></i> ${car.seats} seats</span>
                <span class="car-spec"><i class="fas fa-cog"></i> ${car.transmission}</span>
            </div>
            <button class="book-btn" onclick="bookCar(${car.id})">
                <i class="fas fa-calendar-check"></i> Book Now
            </button>
        </div>
    `;
    return div;
}

// Initialize Date Pickers
function initializeDatePickers() {
    const today = new Date().toISOString().split('T')[0];
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowStr = tomorrow.toISOString().split('T')[0];
    
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach((input, index) => {
        if (index === 0) {
            input.min = today;
            input.value = today;
        } else if (index === 1) {
            input.min = tomorrowStr;
            input.value = tomorrowStr;
        }
        
        // Add change event to validate dates
        input.addEventListener('change', function() {
            validateDates();
        });
    });
}

// Validate Date Selection
function validateDates() {
    const pickupDate = document.querySelector('input[type="date"]:first-of-type');
    const returnDate = document.querySelector('input[type="date"]:last-of-type');
    
    if (pickupDate && returnDate) {
        const pickup = new Date(pickupDate.value);
        const returnD = new Date(returnDate.value);
        
        if (returnD < pickup) {
            alert('Return date must be after pickup date');
            const tomorrow = new Date(pickup);
            tomorrow.setDate(tomorrow.getDate() + 1);
            returnDate.value = tomorrow.toISOString().split('T')[0];
        }
    }
}

// Book Car Function
function bookCar(carId) {
    // In a real application, this would redirect to booking page with car ID
    window.location.href = `booking.html?car=${carId}`;
}

// Add Smooth Scrolling
function addSmoothScrolling() {
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
                
                // Close mobile menu if open
                const navMenu = document.querySelector('.nav-menu');
                const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
                if (navMenu && navMenu.classList.contains('active')) {
                    navMenu.classList.remove('active');
                    if (mobileMenuBtn.querySelector('i')) {
                        mobileMenuBtn.querySelector('i').classList.remove('fa-times');
                        mobileMenuBtn.querySelector('i').classList.add('fa-bars');
                    }
                }
            }
        });
    });
}

// Highlight Current Page in Navigation
function highlightCurrentPage() {
    const currentPage = window.location.pathname.split('/').pop() || 'index.html';
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        const linkHref = link.getAttribute('href');
        if (linkHref === currentPage || 
            (currentPage === '' && linkHref === 'index.html')) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}

// Form Validation Functions
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePhone(phone) {
    const re = /^[\+]?[1-9][\d]{0,15}$/;
    return re.test(phone.replace(/[\s\-\(\)]/g, ''));
}

// Search Cars Function
function searchCars() {
    const location = document.querySelector('input[placeholder="Enter city or airport"]').value;
    const pickupDate = document.querySelector('input[type="date"]:first-of-type').value;
    const returnDate = document.querySelector('input[type="date"]:last-of-type').value;
    
    if (!location || !pickupDate || !returnDate) {
        alert('Please fill in all search fields');
        return;
    }
    
    // In a real application, this would submit the search form
    // For now, redirect to cars page with search parameters
    const params = new URLSearchParams({
        location: location,
        pickup: pickupDate,
        return: returnDate
    });
    
    window.location.href = `cars.html?${params.toString()}`;
}

// Add search functionality to button
document.addEventListener('DOMContentLoaded', function() {
    const searchBtn = document.querySelector('.btn-search');
    if (searchBtn) {
        searchBtn.addEventListener('click', searchCars);
    }
    
    // Add search on Enter key in input fields
    const searchInputs = document.querySelectorAll('.search-item input');
    searchInputs.forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchCars();
            }
        });
    });
});