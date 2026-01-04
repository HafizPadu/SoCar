// About Page JavaScript - Animated Stats Counter
document.addEventListener('DOMContentLoaded', function() {
    console.log('About page loaded');
    
    // Initialize animated stats counter
    initStatsCounter();
    
    // Add intersection observer for animations
    addScrollAnimations();
});

function initStatsCounter() {
    const statNumbers = document.querySelectorAll('.stat-number');
    
    // Check if stats are in viewport
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumber = entry.target;
                const targetCount = parseInt(statNumber.getAttribute('data-count'));
                
                // Start counting animation
                animateCounter(statNumber, targetCount);
                
                // Stop observing after animation starts
                observer.unobserve(statNumber);
            }
        });
    }, {
        threshold: 0.5,
        rootMargin: '0px 0px -100px 0px'
    });
    
    // Observe each stat number
    statNumbers.forEach(stat => {
        observer.observe(stat);
    });
}

function animateCounter(element, target) {
    const duration = 2000; // 2 seconds
    const start = 0;
    const startTime = Date.now();
    
    function updateCounter() {
        const currentTime = Date.now();
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        // Easing function for smooth animation
        const easeOutQuart = 1 - Math.pow(1 - progress, 4);
        const currentValue = Math.floor(start + (target - start) * easeOutQuart);
        
        // Update the element
        element.textContent = currentValue.toLocaleString();
        
        // Add plus sign for large numbers
        if (target >= 1000) {
            element.textContent = currentValue.toLocaleString();
        }
        
        // Continue animation if not complete
        if (progress < 1) {
            requestAnimationFrame(updateCounter);
        } else {
            // Animation complete
            element.textContent = target.toLocaleString();
            
            // Add slight bounce effect
            element.style.transform = 'scale(1.1)';
            setTimeout(() => {
                element.style.transform = 'scale(1)';
                element.style.transition = 'transform 0.3s ease';
            }, 100);
        }
    }
    
    // Start animation
    requestAnimationFrame(updateCounter);
}

function addScrollAnimations() {
    // Elements to animate on scroll
    const animatedElements = document.querySelectorAll(
        '.story-content, .mission-card, .vision-card, .value-card, .team-card'
    );
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    animatedElements.forEach(element => {
        observer.observe(element);
    });
}

// Add animation styles
const animationStyles = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-in {
        animation: fadeInUp 0.6s ease forwards;
    }
    
    /* Initial state for animated elements */
    .story-content,
    .mission-card,
    .vision-card,
    .value-card,
    .team-card {
        opacity: 0;
    }
`;

// Add animation styles to page
const style = document.createElement('style');
style.textContent = animationStyles;
document.head.appendChild(style);