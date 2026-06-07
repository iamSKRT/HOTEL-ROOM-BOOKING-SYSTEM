<?php
include 'config.php';

// Fetch room counts from database
$room_counts = [];
$sql = "SELECT rc.name as category_name, COUNT(r.id) as room_count
        FROM room_categories rc
        LEFT JOIN rooms r ON rc.id = r.category_id AND r.available = 1
        GROUP BY rc.id, rc.name
        ORDER BY rc.id";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $room_counts[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Palmwave Resort & Suites - About the Hotel</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <link rel="icon" href="./assets/palm-wave-icon.png" type="image/png">

    <link href="https://fonts.googleapis.com/css2?family=Marcellus&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .hero-title {
            font-family: 'Marcellus', serif;
        }
        .section-title {
            font-family: 'Playfair Display', serif;
        }
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(50px);
        }
        .animate-on-scroll-left {
            opacity: 0;
            transform: translateX(-50px);
        }
        .animate-on-scroll-right {
            opacity: 0;
            transform: translateX(50px);
        }
        .animate-on-scroll-scale {
            opacity: 0;
            transform: scale(0.8);
        }
        .navbar-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: rgb(8, 8, 8);
            border-radius: 4px;
            min-width: 200px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .navbar-dropdown.active {
            display: block;
        }
        .search-dropdown {
            position: relative;
        }
        .search-dropdown:hover .navbar-dropdown,
        .navbar-dropdown:hover {
            display: block;
        }
    </style>
</head>
<body class="text-white bg-gray-900">

<!-- OVERLAY -->
<div id="overlay" onclick="closeMenu()" class="fixed inset-0 bg-black/60 z-40 hidden"></div>

<!-- SIDEBAR MENU -->
<div id="sideMenu" class="fixed top-0 left-0 w-[400px] h-full bg-white text-black z-50 transform -translate-x-full transition duration-500 ease-in-out shadow-2xl">
    <div class="flex justify-end p-6">
        <button onclick="closeMenu()" class="text-3xl">&times;</button>
    </div>

    <div class="px-10 space-y-5 text-2xl font-light" style="font-family: 'Marcellus', serif;">
        <a href="pages/index.html" class="block text-yellow-700 hover:text-gray-700">Home</a>
        <div>
            <button onclick="toggleSubMenu()" class="flex justify-between w-full items-center">
                Rooms
                <span id="arrow" class="transition">›</span>
            </button>

            <div id="subMenu" class="hidden pl-6 mt-2 space-y-2 text-lg">
                <a href="pages/rooms.html?category=1" class="block">Standard Rooms</a>
                <a href="pages/rooms.html?category=2" class="block">Deluxe Rooms</a>
                <a href="pages/rooms.html?category=3" class="block">Suites</a>
                <a href="pages/rooms.html?category=4" class="block">Villas</a>
                <a href="pages/rooms.html?category=5" class="block">Penthouse</a>
            </div>
        </div>
        <a href="luxury.php" class="block hover:text-gray-700">About The Hotel</a>
        <a href="#" class="block hover:text-gray-700">Premium Services</a>
        <a href="#" class="block hover:text-gray-700">Contact</a>
    </div>

    <div class="absolute bottom-10 left-10 text-sm space-y-2 text-black">
        <p class="font-semibold">Contact Info</p>
        <p>322 Main Street, PH, CA 94559</p>
        <p>+41 22 345 67 88</p>
        <p>PalmWaveResort&Suites@gmail.com</p>
    </div>
</div>

<!-- NAVBAR -->
<header class="absolute top-0 left-0 w-full z-20 bg-white/5 border-b border-white/30">
    <div class="max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">

      <div class="flex items-center space-x-4" style="font-family: 'Marcellus', serif;">
        <button onclick="openMenu()" class="text-2xl">☰</button>

        <nav class="hidden md:flex space-x-6 uppercase text-sm tracking-wide" style="font-family: 'Marcellus', serif;">
          <a href="pages/index.html" class="hover:text-gray-300">Home</a>
          <div class="search-dropdown relative group">
              <a href="pages/rooms.html" class="hover:text-gray-300 flex items-center">
                  Rooms
                  <svg class="w-4 h-4 ml-1 transform group-hover:rotate-180 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                  </svg>
              </a>
              <div class="navbar-dropdown bg-slate-800 text-white rounded shadow-lg mt-2 absolute left-0 top-full w-48 z-50">
                <a href="pages/rooms.html" class="block px-4 py-2 border-b border-slate-700 hover:bg-slate-700 font-semibold">View All Rooms</a>
                <a href="pages/rooms.html?category=1" class="block px-4 py-2 hover:bg-slate-700">Standard Rooms</a>
                <a href="pages/rooms.html?category=2" class="block px-4 py-2 hover:bg-slate-700">Deluxe Rooms</a>
                <a href="pages/rooms.html?category=3" class="block px-4 py-2 hover:bg-slate-700">Suites</a>
                <a href="pages/rooms.html?category=4" class="block px-4 py-2 hover:bg-slate-700">Villas</a>
                <a href="pages/rooms.html?category=5" class="block px-4 py-2 hover:bg-slate-700">Penthouse</a>
              </div>
          </div>

          <div class="search-dropdown relative group">
              <a href="luxury.php" class="hover:text-gray-300 flex items-center underline decoration-white underline-offset-8 decoration-4 ">
                  Pages
                  <svg class="w-4 h-4 ml-1 transform group-hover:rotate-180 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                  </svg>
              </a>
              <div class="navbar-dropdown bg-slate-800 text-white rounded shadow-lg mt-2 absolute left-0 top-full w-48 z-50">
                  <a href="luxury.php" class="block px-4 py-2 hover:bg-slate-700">About The Hotel</a>
                  <a href="pages/services.html" class="block px-4 py-2 hover:bg-slate-700">Services</a>
                  <a href="" class="block px-4 py-2 hover:bg-slate-700">Our Blog</a>                       
              </div>
          </div>

        </nav>
      </div>

      <div class="flex-1 text-center">
        <h1>
          <a href="pages/index.html" 
             class="text-3xl md:text-3xl tracking-wide text-white hover:text-gray-300 transition duration-300"
             style="font-family: 'Playfair Display', serif; letter-spacing: 0.08em;">
            Palmwave Resort & Suites
          </a>
        </h1>
      </div>

      <div class="flex items-center space-x-4 text-sm">
        <span>PalmWaveResort&Suites@gmail.com</span>
      </div>

    </div>
</header>

<!-- Hero Section -->
<section class="pt-24 pb-12 px-4 bg-cover bg-center bg-no-repeat">
    <div class="max-w-7xl mx-auto text-center">
        <h1 class="hero-title text-5xl md:text-7xl font-bold text-yellow-400 mb-6 animate-on-scroll" id="hero-title">
            An Iconic Hotel Since 1998
        </h1>
        <h2 class="section-title text-3xl md:text-4xl font-semibold text-white mb-8 animate-on-scroll" id="hero-subtitle">
            About The Hotel
        </h2>
        <p class="text-xl text-gray-300 max-w-3xl mx-auto animate-on-scroll" id="hero-description">
            The seaside haven of warmth, tranquility and restoration
        </p>
    </div>
</section>

<!-- Resort Images and Room Counts -->
<section class="py-16 px-4 bg-black/50">
    <div class="max-w-7xl mx-auto">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <!-- Images -->
            <div class="grid grid-cols-2 gap-4 animate-on-scroll-left" id="resort-images">
                <img src="https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=400&h=300&fit=crop" alt="Resort View 1" class="rounded-lg shadow-lg animate-on-scroll-scale">
                <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=400&h=300&fit=crop" alt="Resort View 2" class="rounded-lg shadow-lg animate-on-scroll-scale">
                <img src="https://images.unsplash.com/photo-1540541338287-41700207dee6?w=400&h=300&fit=crop" alt="Resort View 3" class="rounded-lg shadow-lg animate-on-scroll-scale">
                <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=400&h=300&fit=crop" alt="Resort View 4" class="rounded-lg shadow-lg animate-on-scroll-scale">
            </div>
            <!-- Room Counts -->
            <div class="animate-on-scroll-right" id="room-counts">
                <h3 class="section-title text-3xl font-bold text-yellow-400 mb-6">
                    Enjoy Your Stay At The Hotel
                </h3>
                <p class="text-gray-300 mb-8">
                    Spend your comfortable holiday in the heart of the beautiful Napa Valley
                </p>
                <div class="grid grid-cols-2 gap-6" id="room-stats">
                    <?php foreach ($room_counts as $index => $room): ?>
                    <div class="text-center animate-on-scroll-scale" style="animation-delay: <?php echo $index * 0.1; ?>s">
                        <div class="text-4xl font-bold text-yellow-400 mb-2" id="count-<?php echo $index; ?>">0</div>
                        <div class="text-white"><?php echo htmlspecialchars($room['category_name']); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- History Section -->
<section class="py-16 px-4">
    <div class="max-w-4xl mx-auto text-center animate-on-scroll" id="history-section">
        <h2 class="section-title text-4xl font-bold text-yellow-400 mb-8">
            Our History
        </h2>
        <p class="text-gray-300 text-lg leading-relaxed mb-6 animate-on-scroll">
            Founded in 2001, Palmwave Resort & Suites has been a beacon of luxury and hospitality in Valley for over two decades. What began as a modest seaside retreat has evolved into an iconic destination, renowned for its unparalleled commitment to guest satisfaction and exquisite accommodations.
        </p>
        <p class="text-gray-300 text-lg leading-relaxed animate-on-scroll">
            Through the years, we've maintained our dedication to providing a sanctuary of warmth and tranquility, where every guest can experience the perfect blend of modern luxury and natural beauty. Our journey continues with the same passion that started it all, ensuring that Palmwave remains the premier choice for discerning travelers seeking an unforgettable escape.
        </p>
    </div>
</section>

<!-- Services Section -->
<section class="py-16 px-4 bg-black/50">
    <div class="max-w-6xl mx-auto">
        <h2 class="section-title text-4xl font-bold text-yellow-400 text-center mb-12 animate-on-scroll" id="services-title">
            Our Services
        </h2>
        <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-6" id="services-grid">
            <div class="text-center p-4 rounded-lg bg-gray-800/50 hover:bg-gray-700/50 transition-colors animate-on-scroll-scale">
                <div class="text-yellow-400 text-2xl mb-2">🛫</div>
                <div class="text-white font-semibold">Airport Pick-up</div>
            </div>
            <div class="text-center p-4 rounded-lg bg-gray-800/50 hover:bg-gray-700/50 transition-colors animate-on-scroll-scale">
                <div class="text-yellow-400 text-2xl mb-2">🧹</div>
                <div class="text-white font-semibold">Housekeeper Services</div>
            </div>
            <div class="text-center p-4 rounded-lg bg-gray-800/50 hover:bg-gray-700/50 transition-colors animate-on-scroll-scale">
                <div class="text-yellow-400 text-2xl mb-2">📶</div>
                <div class="text-white font-semibold">Wifi & Internet</div>
            </div>
            <div class="text-center p-4 rounded-lg bg-gray-800/50 hover:bg-gray-700/50 transition-colors animate-on-scroll-scale">
                <div class="text-yellow-400 text-2xl mb-2">👔</div>
                <div class="text-white font-semibold">Laundry Services</div>
            </div>
            <div class="text-center p-4 rounded-lg bg-gray-800/50 hover:bg-gray-700/50 transition-colors animate-on-scroll-scale">
                <div class="text-yellow-400 text-2xl mb-2">🍳</div>
                <div class="text-white font-semibold">Breakfast in Bed</div>
            </div>
            <div class="text-center p-4 rounded-lg bg-gray-800/50 hover:bg-gray-700/50 transition-colors animate-on-scroll-scale">
                <div class="text-yellow-400 text-2xl mb-2">🏊‍♂️</div>
                <div class="text-white font-semibold">Swimming Pool</div>
            </div>
            <div class="text-center p-4 rounded-lg bg-gray-800/50 hover:bg-gray-700/50 transition-colors animate-on-scroll-scale">
                <div class="text-yellow-400 text-2xl mb-2">💪</div>
                <div class="text-white font-semibold">Fitness Center</div>
            </div>
            <div class="text-center p-4 rounded-lg bg-gray-800/50 hover:bg-gray-700/50 transition-colors animate-on-scroll-scale">
                <div class="text-yellow-400 text-2xl mb-2">🧘‍♀️</div>
                <div class="text-white font-semibold">Wellness Center</div>
            </div>
            <div class="text-center p-4 rounded-lg bg-gray-800/50 hover:bg-gray-700/50 transition-colors animate-on-scroll-scale">
                <div class="text-yellow-400 text-2xl mb-2">👨‍💼</div>
                <div class="text-white font-semibold">Concierge Service</div>
            </div>
            <div class="text-center p-4 rounded-lg bg-gray-800/50 hover:bg-gray-700/50 transition-colors animate-on-scroll-scale">
                <div class="text-yellow-400 text-2xl mb-2">🚗</div>
                <div class="text-white font-semibold">Parking Space</div>
            </div>
            <div class="text-center p-4 rounded-lg bg-gray-800/50 hover:bg-gray-700/50 transition-colors animate-on-scroll-scale">
                <div class="text-yellow-400 text-2xl mb-2">🛎️</div>
                <div class="text-white font-semibold">Room Services</div>
            </div>
            <div class="text-center p-4 rounded-lg bg-gray-800/50 hover:bg-gray-700/50 transition-colors animate-on-scroll-scale">
                <div class="text-yellow-400 text-2xl mb-2">🍖</div>
                <div class="text-white font-semibold">Barbecue Area</div>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="bg-gray-800 border-t border-gray-700 py-8 px-6">
    <div class="max-w-6xl mx-auto text-center text-gray-400 text-sm">
        <p>&copy; 2026 Palmwave Resort & Suites. All rights reserved.</p>
        <div class="mt-2 space-x-4">
            <a href="#" class="hover:text-white">Privacy Policy</a>
            <a href="#" class="hover:text-white">Terms of Service</a>
            <a href="#" class="hover:text-white">Contact</a>
        </div>
    </div>
</footer>

<script>
function openMenu() {
    document.getElementById('sideMenu').classList.remove('-translate-x-full');
    document.getElementById('overlay').classList.remove('hidden');
}

function closeMenu() {
    document.getElementById('sideMenu').classList.add('-translate-x-full');
    document.getElementById('overlay').classList.add('hidden');
}

function toggleSubMenu() {
    document.getElementById('subMenu')?.classList.toggle('hidden');
}

    // GSAP Animations
    gsap.registerPlugin(ScrollTrigger);

    // Function to animate elements on scroll
    function animateOnScroll() {
        // Navbar animation
        gsap.to("#navbar", {
            opacity: 1,
            y: 0,
            duration: 0.8,
            ease: "power2.out",
            scrollTrigger: {
                trigger: "#navbar",
                start: "top 90%",
                toggleActions: "play none none reverse"
            }
        });

        // Hero animations with stagger
        gsap.to("#hero-title", {
            opacity: 1,
            y: 0,
            duration: 1,
            ease: "power2.out",
            scrollTrigger: {
                trigger: "#hero-title",
                start: "top 80%",
                toggleActions: "play none none reverse"
            }
        });

        gsap.to("#hero-subtitle", {
            opacity: 1,
            y: 0,
            duration: 1,
            delay: 0.2,
            ease: "power2.out",
            scrollTrigger: {
                trigger: "#hero-subtitle",
                start: "top 80%",
                toggleActions: "play none none reverse"
            }
        });

        gsap.to("#hero-description", {
            opacity: 1,
            y: 0,
            duration: 1,
            delay: 0.4,
            ease: "power2.out",
            scrollTrigger: {
                trigger: "#hero-description",
                start: "top 80%",
                toggleActions: "play none none reverse"
            }
        });

        // Resort images animation with stagger
        gsap.to("#resort-images", {
            opacity: 1,
            x: 0,
            duration: 1,
            ease: "power2.out",
            scrollTrigger: {
                trigger: "#resort-images",
                start: "top 80%",
                toggleActions: "play none none reverse"
            }
        });

        gsap.to("#resort-images img", {
            opacity: 1,
            scale: 1,
            duration: 0.8,
            stagger: 0.1,
            ease: "back.out(1.7)",
            scrollTrigger: {
                trigger: "#resort-images",
                start: "top 80%",
                toggleActions: "play none none reverse"
            }
        });

        // Room counts animation
        gsap.to("#room-counts", {
            opacity: 1,
            x: 0,
            duration: 1,
            ease: "power2.out",
            scrollTrigger: {
                trigger: "#room-counts",
                start: "top 80%",
                toggleActions: "play none none reverse"
            }
        });

        // Animate room count numbers
        <?php foreach ($room_counts as $index => $room): ?>
        gsap.to("#count-<?php echo $index; ?>", {
            textContent: <?php echo $room['room_count']; ?>,
            duration: 2,
            ease: "power2.out",
            snap: { textContent: 1 },
            scrollTrigger: {
                trigger: "#room-counts",
                start: "top 80%",
                toggleActions: "play none none reverse"
            }
        });
        <?php endforeach; ?>

        gsap.to("#room-stats > div", {
            opacity: 1,
            y: 0,
            duration: 0.8,
            stagger: 0.1,
            ease: "power2.out",
            scrollTrigger: {
                trigger: "#room-stats",
                start: "top 80%",
                toggleActions: "play none none reverse"
            }
        });

        // History section animation
        gsap.to("#history-section", {
            opacity: 1,
            y: 0,
            duration: 1,
            ease: "power2.out",
            scrollTrigger: {
                trigger: "#history-section",
                start: "top 80%",
                toggleActions: "play none none reverse"
            }
        });

        gsap.to("#history-section p", {
            opacity: 1,
            y: 0,
            duration: 0.8,
            stagger: 0.2,
            ease: "power2.out",
            scrollTrigger: {
                trigger: "#history-section",
                start: "top 80%",
                toggleActions: "play none none reverse"
            }
        });

        // Services animations
        gsap.to("#services-title", {
            opacity: 1,
            y: 0,
            duration: 1,
            ease: "power2.out",
            scrollTrigger: {
                trigger: "#services-title",
                start: "top 80%",
                toggleActions: "play none none reverse"
            }
        });

        gsap.to("#services-grid > div", {
            opacity: 1,
            y: 0,
            duration: 0.6,
            stagger: 0.05,
            ease: "power2.out",
            scrollTrigger: {
                trigger: "#services-grid",
                start: "top 85%",
                toggleActions: "play none none reverse"
            }
        });

        // Footer animation
        gsap.to("#footer", {
            opacity: 1,
            y: 0,
            duration: 0.8,
            ease: "power2.out",
            scrollTrigger: {
                trigger: "#footer",
                start: "top 90%",
                toggleActions: "play none none reverse"
        
            }
        });
    }

    // Initialize animations
    animateOnScroll();

    // Add smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                gsap.to(window, {
                    duration: 1,
                    scrollTo: { y: target.offsetTop - 80 },
                    ease: "power2.inOut"
                    
                });
            }
        });
    });
</script>

</body>
</html>
