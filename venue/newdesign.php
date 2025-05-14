<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>NepaliVow</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"/>
  <style>
    body {
      font-family: 'Roboto', sans-serif;
    }
    /* Mobile menu styles */
    .mobile-menu {
      display: none;
    }
    .mobile-menu.active {
      display: block;
    }
  </style>
</head>
<body class="bg-white text-gray-800">
  <!-- Header -->
  <header class="relative bg-cover bg-center h-screen" style="background-image: url('/api/placeholder/1920/1080');">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    
    <!-- Navigation - Desktop & Mobile -->
    <div class="relative z-10 container mx-auto px-4 py-6">
      <div class="flex justify-between items-center">
        <div class="text-white text-2xl font-bold">
          NepaliVow
        </div>
        
        <!-- Desktop Navigation -->
        <nav class="hidden md:flex space-x-4 text-white">
          <a class="hover:underline" href="#">Home</a>
          <a class="hover:underline" href="#">Venues</a>
          <a class="hover:underline" href="#">Services</a>
          <a class="hover:underline" href="#">About</a>
          <a class="hover:underline" href="#">Contact</a>
        </nav>
        
        <!-- Desktop Contact & Button -->
        <div class="hidden md:flex items-center space-x-4">
          <div class="text-white">
            <i class="fas fa-phone-alt"></i>
            +977 98-1234567
          </div>
          <button class="bg-yellow-500 text-white px-4 py-2 rounded">
            Book Now
          </button>
        </div>
        
        <!-- Mobile menu button -->
        <button id="menu-toggle" class="md:hidden text-white text-xl">
          <i class="fas fa-bars"></i>
        </button>
      </div>
      
      <!-- Mobile menu -->
      <div id="mobile-menu" class="mobile-menu mt-4 md:hidden">
        <nav class="flex flex-col bg-black bg-opacity-80 p-4 rounded text-white space-y-2">
          <a class="hover:underline" href="#">Home</a>
          <a class="hover:underline" href="#">Venues</a>
          <a class="hover:underline" href="#">Services</a>
          <a class="hover:underline" href="#">About</a>
          <a class="hover:underline" href="#">Contact</a>
          <div class="py-2 border-t border-gray-700 mt-2">
            <div class="text-white mb-2">
              <i class="fas fa-phone-alt"></i>
              +977 98-1234567
            </div>
            <button class="bg-yellow-500 text-white px-4 py-2 rounded w-full">
              Book Now
            </button>
          </div>
        </nav>
      </div>
    </div>
    
    <!-- Hero Content -->
    <div class="relative z-10 text-center text-white px-4 mt-16 md:mt-32">
      <h2 class="text-lg uppercase">
        Exceptional Wedding Venues in Nepal
      </h2>
      <h1 class="text-3xl md:text-5xl font-bold mt-2">
        Create Your Perfect
        <span class="text-yellow-500">
          Wedding Day
        </span>
      </h1>
      <p class="mt-4 mx-auto max-w-2xl">
        Discover and book the most stunning wedding venues across Nepal, with morning and evening availability.
      </p>
      <div class="mt-6 flex flex-col sm:flex-row justify-center gap-4">
        <button class="bg-yellow-500 text-white px-6 py-3 rounded">
          Explore Venues
        </button>
        <button class="bg-white text-gray-800 px-6 py-3 rounded">
          Contact Us
        </button>
      </div>
    </div>
  </header>
  
  <!-- Search Bar -->
  <div class="container mx-auto px-4 py-6">
    <div class="bg-white shadow-md rounded-lg p-4">
      <div class="flex flex-col md:flex-row items-center gap-2">
        <input class="w-full p-2 border border-gray-300 rounded-lg md:rounded-l-lg" placeholder="Search venues by name or location..." type="text"/>
        <div class="flex w-full md:w-auto gap-2 mt-2 md:mt-0">
          <button class="flex-1 md:flex-auto bg-gray-200 text-gray-800 px-4 py-2 rounded-lg md:rounded-none md:rounded-r-lg">
            Morning
          </button>
          <button class="flex-1 md:flex-auto bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
            Evening
          </button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Featured Wedding Venues -->
  <section class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold">
      Featured Wedding Venues
    </h2>
    <p class="text-gray-600">
      Discover Nepal's most beautiful venues for your special day
    </p>
    <div class="mt-4 text-center">
      <div id="featured-venues" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        
        <!-- Sample Venue Card 1 -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
          <div class="relative">
            <img src="/api/placeholder/400/300" class="w-full h-48 object-cover" alt="Hyatt Regency Kathmandu">
          </div>
          <div class="p-4 flex flex-col h-full">
            <h5 class="text-xl font-bold mb-2">Hyatt Regency Kathmandu</h5>
            <p class="text-gray-600 text-sm flex items-center mb-2">
              <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
              Boudha, Kathmandu
            </p>
            <p class="text-gray-700 text-sm flex items-center mb-3">
              <strong class="mr-2">Starting Price:</strong>
              <span class="text-red-500 font-bold">Rs. 250000 ONWARDS</span>
            </p>
            <div class="flex flex-wrap gap-2 mb-3">
              <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-lg flex items-center"><i class="fas fa-wifi mr-1"></i> Free WiFi</span>
              <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-lg flex items-center"><i class="fas fa-utensils mr-1"></i> Catering</span>
              <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-lg flex items-center"><i class="fas fa-tv mr-1"></i> AV Equipment</span>
            </div>
            <a href="#" class="mt-auto text-center w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition">
              View More
            </a>
          </div>
        </div>
        
        <!-- Sample Venue Card 2 -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
          <div class="relative">
            <img src="/api/placeholder/400/300" class="w-full h-48 object-cover" alt="Hotel Yak & Yeti">
          </div>
          <div class="p-4 flex flex-col h-full">
            <h5 class="text-xl font-bold mb-2">Hotel Yak & Yeti</h5>
            <p class="text-gray-600 text-sm flex items-center mb-2">
              <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
              Durbar Marg, Kathmandu
            </p>
            <p class="text-gray-700 text-sm flex items-center mb-3">
              <strong class="mr-2">Starting Price:</strong>
              <span class="text-red-500 font-bold">Rs. 200000 ONWARDS</span>
            </p>
            <div class="flex flex-wrap gap-2 mb-3">
              <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-lg flex items-center"><i class="fas fa-wifi mr-1"></i> Free WiFi</span>
              <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-lg flex items-center"><i class="fas fa-utensils mr-1"></i> Catering</span>
              <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-lg flex items-center"><i class="fas fa-tv mr-1"></i> AV Equipment</span>
            </div>
            <a href="#" class="mt-auto text-center w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition">
              View More
            </a>
          </div>
        </div>
        
        <!-- Sample Venue Card 3 -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
          <div class="relative">
            <img src="/api/placeholder/400/300" class="w-full h-48 object-cover" alt="Soaltee Crowne Plaza">
          </div>
          <div class="p-4 flex flex-col h-full">
            <h5 class="text-xl font-bold mb-2">Soaltee Crowne Plaza</h5>
            <p class="text-gray-600 text-sm flex items-center mb-2">
              <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
              Tahachal, Kathmandu
            </p>
            <p class="text-gray-700 text-sm flex items-center mb-3">
              <strong class="mr-2">Starting Price:</strong>
              <span class="text-red-500 font-bold">Rs. 180000 ONWARDS</span>
            </p>
            <div class="flex flex-wrap gap-2 mb-3">
              <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-lg flex items-center"><i class="fas fa-wifi mr-1"></i> Free WiFi</span>
              <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-lg flex items-center"><i class="fas fa-utensils mr-1"></i> Catering</span>
              <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-lg flex items-center"><i class="fas fa-tv mr-1"></i> AV Equipment</span>
            </div>
            <a href="#" class="mt-auto text-center w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition">
              View More
            </a>
          </div>
        </div>
        
        <!-- Sample Venue Card 4 -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
          <div class="relative">
            <img src="/api/placeholder/400/300" class="w-full h-48 object-cover" alt="Hotel Annapurna">
          </div>
          <div class="p-4 flex flex-col h-full">
            <h5 class="text-xl font-bold mb-2">Hotel Annapurna</h5>
            <p class="text-gray-600 text-sm flex items-center mb-2">
              <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
              Durbar Marg, Kathmandu
            </p>
            <p class="text-gray-700 text-sm flex items-center mb-3">
              <strong class="mr-2">Starting Price:</strong>
              <span class="text-red-500 font-bold">Rs. 150000 ONWARDS</span>
            </p>
            <div class="flex flex-wrap gap-2 mb-3">
              <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-lg flex items-center"><i class="fas fa-wifi mr-1"></i> Free WiFi</span>
              <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-lg flex items-center"><i class="fas fa-utensils mr-1"></i> Catering</span>
              <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-lg flex items-center"><i class="fas fa-tv mr-1"></i> AV Equipment</span>
            </div>
            <a href="#" class="mt-auto text-center w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition">
              View More
            </a>
          </div>
        </div>
        
      </div>
    </div>
  </section>
  
  <!-- How NepaliVow Works -->
  <section class="bg-gray-100 py-12">
    <div class="container mx-auto px-4">
      <h2 class="text-2xl font-bold text-center">
        How NepaliVow Works
      </h2>
      <p class="text-gray-600 text-center mb-8">
        Find and book your perfect wedding venue in Nepal with our simple three-step process
      </p>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white shadow-md rounded-lg p-6 text-center">
          <div class="text-yellow-500 text-4xl mb-4">
            <i class="fas fa-search"></i>
          </div>
          <h3 class="text-xl font-bold">
            Find Your Venue
          </h3>
          <p class="text-gray-600 mt-2">
            Browse our curated collection of Nepal's finest wedding venues and filter by availability and features.
          </p>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6 text-center">
          <div class="text-yellow-500 text-4xl mb-4">
            <i class="fas fa-clock"></i>
          </div>
          <h3 class="text-xl font-bold">
            Choose Your Shift
          </h3>
          <p class="text-gray-600 mt-2">
            Select your preferred time - morning or evening shift - and add any additional amenities you need.
          </p>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6 text-center">
          <div class="text-yellow-500 text-4xl mb-4">
            <i class="fas fa-calendar-check"></i>
          </div>
          <h3 class="text-xl font-bold">
            Book &amp; Celebrate
          </h3>
          <p class="text-gray-600 mt-2">
            Confirm your booking with our secure payment system and focus on creating beautiful memories.
          </p>
        </div>
      </div>
    </div>
  </section>
  
  <!-- Happy Couples -->
  <section class="container mx-auto px-4 py-12">
    <h2 class="text-2xl font-bold text-center">
      Happy Couples
    </h2>
    <p class="text-gray-600 text-center mb-8">
      What our customers are saying about their wedding experience
    </p>
    
    <!-- Mobile-friendly carousel -->
    <div class="mt-8 relative">
      <div class="carousel overflow-hidden">
        <div class="carousel-inner flex transition-transform duration-300" style="transform: translateX(0%);">
          <!-- Testimonial 1 -->
          <div class="carousel-item w-full md:w-1/3 flex-shrink-0 px-2">
            <div class="bg-white shadow-md rounded-lg p-6 h-full">
              <div class="flex items-center mb-4">
                <img alt="Anisha & Bikash" class="w-12 h-12 rounded-full mr-4" src="/api/placeholder/48/48"/>
                <div>
                  <h3 class="text-lg font-bold">
                    Anisha & Bikash
                  </h3>
                  <p class="text-gray-600">
                    Hyatt Regency Kathmandu
                  </p>
                </div>
              </div>
              <p class="text-gray-600">
                The morning shift wedding gave us beautiful lighting for photos, and the amenities package saved us so much time in planning.
              </p>
              <div class="mt-4 text-yellow-500">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
          </div>
          
          <!-- Testimonial 2 -->
          <div class="carousel-item w-full md:w-1/3 flex-shrink-0 px-2">
            <div class="bg-white shadow-md rounded-lg p-6 h-full">
              <div class="flex items-center mb-4">
                <img alt="Suman & Puja" class="w-12 h-12 rounded-full mr-4" src="/api/placeholder/48/48"/>
                <div>
                  <h3 class="text-lg font-bold">
                    Suman & Puja
                  </h3>
                  <p class="text-gray-600">
                    Fulbari Resort
                  </p>
                </div>
              </div>
              <p class="text-gray-600">
                Having our wedding during the evening with NepaliVow made the booking process so easy and stress-free.
              </p>
              <div class="mt-4 text-yellow-500">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
              </div>
            </div>
          </div>
          
          <!-- Testimonial 3 -->
          <div class="carousel-item w-full md:w-1/3 flex-shrink-0 px-2">
            <div class="bg-white shadow-md rounded-lg p-6 h-full">
              <div class="flex items-center mb-4">
                <img alt="Ramesh & Sita" class="w-12 h-12 rounded-full mr-4" src="/api/placeholder/48/48"/>
                <div>
                  <h3 class="text-lg font-bold">
                    Ramesh & Sita
                  </h3>
                  <p class="text-gray-600">
                    Hotel Annapurna
                  </p>
                </div>
              </div>
              <p class="text-gray-600">
                NepaliVow made our wedding day perfect with their excellent service and beautiful venues.
              </p>
              <div class="mt-4 text-yellow-500">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Carousel Controls -->
      <div class="flex justify-center mt-6 md:hidden">
        <div class="flex space-x-2">
          <button class="carousel-dot w-3 h-3 rounded-full bg-gray-300" data-index="0"></button>
          <button class="carousel-dot w-3 h-3 rounded-full bg-gray-300" data-index="1"></button>
          <button class="carousel-dot w-3 h-3 rounded-full bg-gray-300" data-index="2"></button>
        </div>
      </div>
      
      <div class="hidden md:flex justify-between items-center absolute top-1/2 inset-x-0 transform -translate-y-1/2">
        <button class="carousel-prev bg-gray-800 text-white p-2 rounded-full ml-2">
          <i class="fas fa-chevron-left"></i>
        </button>
        <button class="carousel-next bg-gray-800 text-white p-2 rounded-full mr-2">
          <i class="fas fa-chevron-right"></i>
        </button>
      </div>
    </div>
  </section>
  
  <!-- Stay Updated -->
  <section class="bg-yellow-500 py-12">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-2xl font-bold text-white">
        Stay Updated
      </h2>
      <p class="text-white mb-4">
        Subscribe to our newsletter for exclusive venue updates and special offers
      </p>
      <div class="flex flex-col sm:flex-row justify-center max-w-md mx-auto">
        <input class="p-2 rounded-lg sm:rounded-r-none w-full mb-2 sm:mb-0" placeholder="Your email address" type="email"/>
        <button class="bg-white text-yellow-500 px-4 py-2 rounded-lg sm:rounded-l-none">
          Subscribe
        </button>
      </div>
    </div>
  </section>
  
  <!-- Footer -->
  <footer class="bg-gray-900 text-white py-12">
    <div class="container mx-auto px-4">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <div>
          <h3 class="text-lg font-bold">
            NepaliVow
          </h3>
          <p class="text-gray-400 mt-2">
            Making wedding venue booking in Nepal simple, transparent, and stress-free.
          </p>
          <div class="mt-4 flex space-x-4">
            <a class="text-gray-400 hover:text-white" href="#">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a class="text-gray-400 hover:text-white" href="#">
              <i class="fab fa-twitter"></i>
            </a>
            <a class="text-gray-400 hover:text-white" href="#">
              <i class="fab fa-instagram"></i>
            </a>
          </div>
        </div>
        <div>
          <h3 class="text-lg font-bold">
            Quick Links
          </h3>
          <ul class="mt-2 space-y-2">
            <li>
              <a class="text-gray-400 hover:text-white" href="#">
                Home
              </a>
            </li>
            <li>
              <a class="text-gray-400 hover:text-white" href="#">
                Venues
              </a>
            </li>
            <li>
              <a class="text-gray-400 hover:text-white" href="#">
                Services
              </a>
            </li>
            <li>
              <a class="text-gray-400 hover:text-white" href="#">
                About Us
              </a>
            </li>
            <li>
              <a class="text-gray-400 hover:text-white" href="#">
                Contact
              </a>
            </li>
          </ul>
        </div>
        <div>
          <h3 class="text-lg font-bold">
            Support
          </h3>
          <ul class="mt-2 space-y-2">
            <li>
              <a class="text-gray-400 hover:text-white" href="#">
                FAQs
              </a>
            </li>
            <li>
              <a class="text-gray-400 hover:text-white" href="#">
                Privacy Policy
              </a>
            </li>
            <li>
              <a class="text-gray-400 hover:text-white" href="#">
                Terms of Service
              </a>
            </li>
            <li>
              <a class="text-gray-400 hover:text-white" href="#">
                Refund Policy
              </a>
            </li>
          </ul>
        </div>
        <div>
          <h3 class="text-lg font-bold">
            Contact Us
          </h3>
          <ul class="mt-2 space-y-2">
            <li class="text-gray-400">
              <i class="fas fa-map-marker-alt mr-2"></i>
              123 Durbar Marg, Kathmandu, Nepal
            </li>
            <li class="text-gray-400">
              <i class="fas fa-envelope mr-2"></i>
              info@nepalivow.com
            </li>
            <li class="text-gray-400">
              <i class="fas fa-phone-alt mr-2"></i>
              +977 1-1234567
            </li>
          </ul>
        </div>
      </div>
      <div class="mt-8 text-center text-gray-400">
        © 2025 NepaliVow. All rights reserved.
      </div>
    </div>
  </footer>
  
  <script>
    // Mobile menu toggle
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    
    menuToggle.addEventListener('click', () => {
      mobileMenu.classList.toggle('active');
    });
    
    // Carousel functionality
    const carouselInner = document.querySelector('.carousel-inner');
    const prevButton = document.querySelector('.carousel-prev');
    const nextButton = document.querySelector('.carousel-next');
    const carouselDots = document.querySelectorAll('.carousel-dot');
    const carouselItems = document.querySelectorAll('.carousel-item');
    let currentIndex = 0;
    
    function updateCarousel() {
      // For desktop: partial view
      if (window.innerWidth >= 768) {
        carouselInner.style.transform = `translateX(-${currentIndex * 33.333}%)`;
      } 
      // For mobile: full width slides
      else {
        carouselInner.style.transform = `translateX(-${currentIndex * 100}%)`;
      }
      
      // Update dots on mobile
      carouselDots.forEach((dot, index) => {
        if (index === currentIndex) {
          dot.classList.add('bg-gray-800');
          dot.classList.remove('bg-gray-300');
        } else {
          dot.classList.add('bg-gray-300');
          dot.classList.remove('bg-gray-800');
        }
      });
    }
    
    // Previous button
    if (prevButton) {
      prevButton.addEventListener('click', () => {
        currentIndex = (currentIndex > 0) ? currentIndex - 1 : carouselItems.length - 1;
        updateCarousel();
      });
    }
    
    // Next button
    if (nextButton) {
      nextButton.addEventListener('click', () => {
        currentIndex = (currentIndex < carouselItems.length - 1) ? currentIndex + 1 : 0;
        updateCarousel();
      });
    }
    
    // Dot navigation for mobile
    carouselDots.forEach((dot) => {
      dot.addEventListener('click', () => {
        currentIndex = parseInt(dot.getAttribute('data-index'));
        updateCarousel();
      });
    });
    
    // Initialize
    updateCarousel();
    
    // Handle resize
    window.addEventListener('resize', updateCarousel);
    
    // Auto slide
    setInterval(() => {
      currentIndex = (currentIndex < carouselItems.length - 1) ? currentIndex + 1 : 0;
      updateCarousel();
    }, 5000);
  </script>
</body>
</html>