import { useEffect, useState, useRef } from 'react';
import gsap from 'gsap';

const SERVER_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:5000';

const formatDateInput = (date) => date.toISOString().slice(0, 10);
const addDays = (dateString, offset) => {
  const date = new Date(dateString);
  date.setDate(date.getDate() + offset);
  return formatDateInput(date);
};

const menuLinks = ['Home', 'Rooms', 'Pages', 'Blog'];

const amenitiesList = [
  'Air conditioner',
  'Cable TV',
  'Wifi & Internet',
  'Towels',
  'Slippers',
  'Hair Dryer',
  'Shampoo',
  'Espresso Machine',
  'Safe Box',
  'Welcome Drinks',
  'Pet Friendly',
  'In-room Refrigerator'
];

const experienceCards = [
  {
    title: 'Spa & Wellness',
    image: '/assets/spaandwellness.png',
    description:
      'Set in lush jungle, our modern spa embodies the calm of nature, offering extraordinary visual and auditory experiences that pamper and heal.',
  },
  {
    title: 'Island Activities',
    image: '/assets/islandactivity.png',
    description:
      'Discover the thrill of island adventures with our curated activities, from snorkeling to hiking, all designed to immerse you in the natural beauty of the surroundings.',
  },
  {
    title: 'Gastronomic Dine',
    image: '/assets/food.png',
    description:
      'Indulge in a culinary journey with our diverse menu, featuring locally-sourced ingredients and expertly crafted dishes that celebrate the flavors of the region.',
  },
];

const roomCards = [
  {
    title: 'Coral Breeze Room',
    type: 'Standard',
    pax: '2 Guests',
    image: '/assets/coral.png',
    description: 'A calming coastal retreat designed for pure relaxation and comfort.',
    amenities: amenitiesList,
    whatsIncluded: 'Standard room amenities, complimentary daily breakfast, beach access',
  },
  {
    title: 'Seabreeze Comfort Room',
    type: 'Standard',
    pax: '2 Guests',
    image: '/assets/seabreeze.png',
    description: 'Soft ocean tones with a peaceful island ambiance.',
    amenities: amenitiesList,
    whatsIncluded: 'Standard room amenities, complimentary daily breakfast, beach access',
  },
  {
    title: 'Azure Horizon Deluxe',
    type: 'Deluxe',
    pax: '2–4 Guests', 
    image: '/assets/Azure.png',
    description: 'Elegant ocean horizon views with premium interiors.',
    amenities: amenitiesList,
    whatsIncluded: 'Deluxe amenities, spa credit, premium breakfast, late checkout',
  },
  {
    title: 'Golden Palm Deluxe',
    type: 'Deluxe',
    pax: '2–4 Guests',
    image: '/assets/Golden.png',
    description: 'Warm tropical luxury with golden ambient lighting.',
    amenities: amenitiesList,
    whatsIncluded: 'Deluxe amenities, spa credit, premium breakfast, late checkout',
  },
  {
    title: 'Ocean Pearl Executive Suite',
    type: 'Suites',
    pax: '2–4 Guests',
    image: '/assets/Ocean.png',
    description: 'Spacious suite with panoramic ocean views and premium amenities.',
    amenities: amenitiesList,
    whatsIncluded: 'Suite amenities, private lounge access, complimentary bar, butler service',
  },
  {
    title: 'Sapphire Wave Suite',
    type: 'Suites',
    pax: '2–4 Guests',
    image: '/assets/Sapphire.png',
    description: 'Luxurious suite with stunning ocean views and elegant design.',
    amenities: amenitiesList,
    whatsIncluded: 'Suite amenities, private lounge access, complimentary bar, butler service',
  },
  {
    title: 'Sunset Mirage Suite',
    type: 'Suites',
    pax: '2 Guests',
    image: '/assets/Sunset Mirage Suite.png',
    description: 'Elegant suite with breathtaking sunset views and premium amenities.',
    amenities: amenitiesList,
    whatsIncluded: 'Suite amenities, private lounge access, complimentary bar, butler service',
  },
  {
    title: 'Palm Royale Villa',
    type: 'Villas',
    pax: '6–8 Guests',
    image: '/assets/Palm Royale Villa.png',
    description: 'Luxurious villa with private beach access and premium amenities.',
    amenities: amenitiesList,
    whatsIncluded: 'Villa amenities, private chef service, dedicated concierge, infinity pool',
  },
  {
    title: 'Lagoon Crest Villa',
    type: 'Villas',
    pax: '8–10 Guests',
    image: '/assets/Lagoon Crest Villa.png',
    description: 'Stunning villa with direct beach access and world-class amenities.',
    amenities: amenitiesList,
    whatsIncluded: 'Villa amenities, private chef service, dedicated concierge, infinity pool',
  },
  {
    title: 'Royal Tides Oceanfront Penthouse',
    type: 'Penthouse',
    pax: '15–20 Guests',
    image: '/assets/Royal Tides Oceanfront Penthouse.png',
    description: 'Exclusive penthouse with panoramic ocean views and luxurious amenities.',
    amenities: amenitiesList,
    whatsIncluded: 'Penthouse amenities, private chef, dedicated staff, helipad access',
  },
];

const services = [
  { title: 'Airport Pick-up Service', icon: '🚕', description: 'Seamless luxury transfers from airport to resort with premium comfort and reliability.' },
  { title: 'Housekeeping Service', icon: '🛎️', description: 'Daily premium room care ensuring a spotless and relaxing environment throughout your stay.' },
  { title: 'High-Speed WiFi', icon: '📶', description: 'Ultra-fast internet connection available across the entire property.' },
  { title: 'Laundry Service', icon: '👕', description: 'Professional garment care with fast turnaround and premium handling.' },
  { title: 'In-Room Dining', icon: '🍽️', description: 'Elegant breakfast and dining served directly to your room at your preferred time.' },
  { title: 'Private Parking', icon: '🅿️', description: 'Secure valet-style parking exclusively for guests with 24/7 access.' },
];

const testimonials = [
  {
    quote: 'Everything here was great: the staff, the room layout, the amenities, and especially the mountain view.',
    name: 'Anna Williams',
    source: 'TripAdvisor',
  },
  {
    quote: 'The ocean view suite was breathtaking. Service was exceptional and very peaceful.',
    name: 'Michael Cruz',
    source: 'Google Reviews',
  },
  {
    quote: 'A true luxury escape. The sunset view and ambiance were unforgettable.',
    name: 'Sofia Reyes',
    source: 'Booking.com',
  },
];

function App() {
  const [menuOpen, setMenuOpen] = useState(false);
  const [subMenuOpen, setSubMenuOpen] = useState(false);
  const [bookingModalOpen, setBookingModalOpen] = useState(false);
  const [roomDetailsModalOpen, setRoomDetailsModalOpen] = useState(false);
  const [selectedRoomForDetails, setSelectedRoomForDetails] = useState(null);
  const [rooms, setRooms] = useState([]);
  const [bookings, setBookings] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [reserveStatus, setReserveStatus] = useState('');
  const [reserveError, setReserveError] = useState('');
  const [selectedRoomId, setSelectedRoomId] = useState('');
  const [checkIn, setCheckIn] = useState('');
  const [checkOut, setCheckOut] = useState('');
  const [guestCount, setGuestCount] = useState(2);
  const [testimonialIndex, setTestimonialIndex] = useState(0);
  const galleryCardsRef = useRef([]);

  const today = formatDateInput(new Date());
  const checkOutMin = checkIn ? addDays(checkIn, 1) : addDays(today, 1);

  const selectedRoom = rooms.find((room) => String(room.id) === String(selectedRoomId));
  const roomBookings = bookings.filter((booking) => String(booking.room_id) === String(selectedRoomId));

  useEffect(() => {
    if (rooms.length && !selectedRoomId) {
      setSelectedRoomId(String(rooms[0].id));
    }
  }, [rooms, selectedRoomId]);

  // GSAP animation for gallery cards - animate from right to left on scroll
  useEffect(() => {
    const cards = galleryCardsRef.current;
    if (cards.length === 0) return;

    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px',
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const index = cards.indexOf(entry.target);
          gsap.to(entry.target, {
            duration: 0.8,
            x: 0,
            opacity: 1,
            ease: 'power3.out',
            delay: index * 0.1,
          });
          observer.unobserve(entry.target);
        }
      });
    }, observerOptions);

    cards.forEach((card) => {
      // Set initial state
      gsap.set(card, { x: 100, opacity: 0 });
      observer.observe(card);
    });

    return () => {
      cards.forEach((card) => observer.unobserve(card));
    };
  }, []);

  useEffect(() => {
    fetchAllRooms();
    fetchBookings();
  }, []);

  const fetchAllRooms = async () => {
    setLoading(true);
    setError('');

    try {
      const response = await fetch(`${SERVER_URL}/api/all`);
      if (!response.ok) {
        throw new Error(`Server error: ${response.status}`);
      }
      const data = await response.json();
      setRooms(data.rooms || []);
    } catch (err) {
      setError(err.message || 'Failed to load rooms');
    } finally {
      setLoading(false);
    }
  };

  const fetchBookings = async () => {
    try {
      const response = await fetch(`${SERVER_URL}/api/bookings`);
      if (!response.ok) {
        throw new Error(`Server error: ${response.status}`);
      }
      const data = await response.json();
      setBookings(data.bookings || []);
    } catch (err) {
      console.warn('Could not load bookings:', err.message);
      setBookings([]);
    }
  };

  const bookingConflict = () => {
    if (!checkIn || !checkOut) return false;
    const start = new Date(checkIn);
    const end = new Date(checkOut);

    return roomBookings.some((booking) => {
      const bookingStart = new Date(booking.checkin);
      const bookingEnd = new Date(booking.checkout);
      return start < bookingEnd && end > bookingStart;
    });
  };

  const handleReserve = async (event) => {
    event.preventDefault();
    setReserveStatus('');
    setReserveError('');

    if (!selectedRoomId || !checkIn || !checkOut) {
      setReserveError('Please choose a room, check-in date, and check-out date.');
      return;
    }

    if (new Date(checkOut) <= new Date(checkIn)) {
      setReserveError('Check-out must be after the check-in date.');
      return;
    }

    if (new Date(checkIn) < new Date(today)) {
      setReserveError('Check-in cannot be in the past.');
      return;
    }

    if (bookingConflict()) {
      setReserveError('Selected date range overlaps an existing reservation. Choose different dates.');
      return;
    }

    try {
      const response = await fetch(`${SERVER_URL}/api/reserve`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          roomId: Number(selectedRoomId),
          checkIn,
          checkOut,
          guestCount,
        }),
      });
      const data = await response.json();
      if (!response.ok) {
        throw new Error(data.error || 'Reservation failed');
      }
      setReserveStatus(`Reservation confirmed for ${selectedRoom?.name || 'selected room'} from ${checkIn} to ${checkOut}.`);
      setCheckIn('');
      setCheckOut('');
      setGuestCount(2);
      fetchBookings();
    } catch (err) {
      setReserveError(err.message || 'Reservation failed.');
    }
  };

  const openRoomDetails = (room) => {
    setSelectedRoomForDetails(room);
    setRoomDetailsModalOpen(true);
  };

  return (
    <div className="app-shell">
      <div className={`overlay ${menuOpen ? 'visible' : ''}`} onClick={() => setMenuOpen(false)} />

      <aside className={`sidebar ${menuOpen ? 'active' : ''}`}>
        <div className="sidebar-header">
          <button className="close-button" onClick={() => setMenuOpen(false)}>
            ×
          </button>
        </div>
        <nav className="sidebar-nav">
          {menuLinks.map((link) => (
            <a key={link} href="#" className="sidebar-link">
              {link}
            </a>
          ))}
          <button className="submenu-toggle" onClick={() => setSubMenuOpen((prev) => !prev)}>
            Restaurants & Bars <span>{subMenuOpen ? '˅' : '›'}</span>
          </button>
          <div className={`submenu ${subMenuOpen ? 'open' : ''}`}>
            <a href="#" className="sidebar-link">Dining</a>
            <a href="#" className="sidebar-link">Bar Lounge</a>
          </div>
          <a href="#" className="sidebar-link">Spa & Wellness</a>
          <a href="#" className="sidebar-link">Weddings & Events</a>
          <a href="#" className="sidebar-link">Local Activities</a>
          <a href="#" className="sidebar-link">Blog</a>
          <a href="#" className="sidebar-link">Contact</a>
        </nav>
        <div className="sidebar-footer">
          <p className="footer-label">Contact Info</p>
          <p>322 Main Street, PH, CA 94559</p>
          <p>+41 22 345 67 88</p>
          <p>PalmWaveResort&Suites@gmail.com</p>
        </div>
      </aside>

      <header className="hero-section">
        <div className="hero-overlay" />
        <div className="page-header">
          <div className="brand-row">
            <button className="menu-button" onClick={() => setMenuOpen(true)}>
              ☰
            </button>
            <nav className="top-nav">
              {menuLinks.map((link) => (
                <a key={link} href="#" className="top-link">
                  {link}
                </a>
              ))}
            </nav>
          </div>
          <a href="#" className="brand-title">
            Palmwave Resort & Suites
          </a>
          <div className="header-actions">
            <span>Tel: +63 36 345 67 88</span>
            <span>EN / FR</span>
            <button className="reserve-now" onClick={() => setBookingModalOpen(true)}>
              Reserve Now
            </button>
          </div>
        </div>

        <div className="hero-content">
          <section className="hero-copy">
            <p className="eyebrow">A Sanctuary of Tranquility</p>
            <h1>Refined Countryside Escape at Palmwave Resort & Suites</h1>
            <p>
              Experience timeless elegance where nature meets sophistication. Indulge in serene landscapes,
              curated luxury, and unforgettable stays.
            </p>
            <button className="cta-button">Explore Suites</button>
          </section>

          <section className="hero-booking-card">
            <h2>Reserve Your Stay</h2>
            <form className="booking-form" onSubmit={handleReserve}>
              <label>
                Check In
                <input type="date" value={checkIn} min={today} onChange={(e) => setCheckIn(e.target.value)} />
              </label>
              <label>
                Check Out
                <input type="date" value={checkOut} min={checkOutMin} onChange={(e) => setCheckOut(e.target.value)} />
              </label>
              <label>
                Rooms
                <select value={selectedRoomId} onChange={(e) => setSelectedRoomId(e.target.value)}>
                  {rooms.map((room) => (
                    <option key={room.id} value={room.id}>
                      {room.name}
                    </option>
                  ))}
                </select>
              </label>
              <div className="guest-grid">
                <label>
                  Adults
                  <select value={guestCount} onChange={(e) => setGuestCount(Number(e.target.value))}>
                    <option value={1}>1 Adult</option>
                    <option value={2}>2 Adults</option>
                    <option value={3}>3 Adults</option>
                    <option value={4}>4 Adults</option>
                  </select>
                </label>
                <label>
                  Children
                  <select>
                    <option>0 Children</option>
                    <option>1 Child</option>
                    <option>2 Children</option>
                  </select>
                </label>
              </div>
              <button className="primary-button">Check Availability</button>
              {reserveError && <div className="form-note error">{reserveError}</div>}
              {reserveStatus && <div className="form-note success">{reserveStatus}</div>}
            </form>
          </section>
        </div>
      </header>

      {bookingModalOpen && (
        <div className="modal-backdrop" onClick={() => setBookingModalOpen(false)}>
          <div className="booking-modal" onClick={(event) => event.stopPropagation()}>
            <button className="modal-close" onClick={() => setBookingModalOpen(false)}>
              ×
            </button>
            <div className="modal-body">
              <div className="modal-image">
                <img src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=900&q=80" alt="Booking" />
              </div>
              <div className="modal-form">
                <h2>Book Your Stay</h2>
                <form className="booking-form" onSubmit={(event) => event.preventDefault()}>
                  <label>
                    Check In
                    <input type="date" min={today} />
                  </label>
                  <label>
                    Check Out
                    <input type="date" min={addDays(today, 1)} />
                  </label>
                  <label>
                    Rooms
                    <select>
                      <option>1 Room</option>
                      <option>2 Rooms</option>
                    </select>
                  </label>
                  <label>
                    Guests
                    <select>
                      <option>1 Adult, 0 Children</option>
                      <option>2 Adults, 1 Child</option>
                    </select>
                  </label>
                  <button className="primary-button">Check Availability</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      )}

      <section className="experience-section" id="experience">
        <div className="experience-top">
          <img className="experience-icon" src="/assets/palm-wave-icon.png" alt="Palmwave Icon" />
          <h2>Unforgettable Experience</h2>
          <p>One of the World's Most Desirable Locations</p>
          <p>
            A superior, 5-star resort embodying the very best of Fiji Islands luxury,
            tranquility & sophistication.
          </p>
        </div>
        <div className="experience-grid">
          {experienceCards.map((card) => (
            <article key={card.title} className="experience-card">
              <img src={card.image} alt={card.title} />
              <h3>{card.title}</h3>
              <p>{card.description}</p>
              <a href="#" className="text-link">Discover More</a>
            </article>
          ))}
        </div>
      </section>

      <section className="rooms-showcase" id="rooms">
        <div className="rooms-header">
          <div>
            <p>Indulge in a World-Class Stay Experience</p>
            <h2>Experience Luxurious Rooms & Suites with Breathtaking Views</h2>
            <p>
              Escape into a sanctuary where the mountains meet endless ocean horizons, and every stay is defined by refined luxury, natural beauty, and serene elegance.
              Experience a world of comfort, sophistication, and timeless tranquility crafted for the discerning traveler.
            </p>
          </div>
        </div>
        <div className="room-gallery">
          {roomCards.map((room, index) => (
            <article
              key={room.title}
              ref={(el) => (galleryCardsRef.current[index] = el)}
              className="gallery-card"
            >
              <div className="gallery-image">
                <img src={encodeURI(room.image)} alt={room.title} />
              </div>
              <div className="gallery-content">
                <h3>{room.title}</h3>
                <p>{room.type} • Pax: {room.pax}</p>
                <p>{room.description}</p>
                <button
                  className="discover-button"
                  onClick={() => openRoomDetails(room)}
                >
                  Discover More
                </button>
              </div>
            </article>
          ))}
        </div>
      </section>

      <section className="services-section" id="services">
        <div className="services-grid">
          {services.map((service) => (
            <article key={service.title} className="service-card">
              <div className="service-icon">{service.icon}</div>
              <h3>{service.title}</h3>
              <p>{service.description}</p>
            </article>
          ))}
        </div>
      </section>

      <section className="testimonial-section">
        <div className="testimonial-intro">
          <span>Voice From Our Guests</span>
          <h2>Luxury Experiences Shared</h2>
          <p>Moments Cherished by Our Guests at Palmwave Resort & Suites</p>
        </div>
        <div className="testimonial-slider">
          <article className="testimonial-card">
            <p>“{testimonials[testimonialIndex].quote}”</p>
            <div className="testimonial-meta">
              <strong>{testimonials[testimonialIndex].name}</strong>
              <span>{testimonials[testimonialIndex].source}</span>
            </div>
          </article>
          <div className="testimonial-dots">
            {testimonials.map((_, index) => (
              <button
                key={index}
                className={testimonialIndex === index ? 'dot active' : 'dot'}
                onClick={() => setTestimonialIndex(index)}
                type="button"
              />
            ))}
          </div>
        </div>
      </section>

      <main className="main-content">
        <section className="summary-card">
          <h2>Rooms loaded from the database</h2>
          <p>Browse the latest room details pulled from the backend.</p>
        </section>
        <section className="rooms-section">
          <div className="rooms-toolbar">
            <h3>All Rooms</h3>
            <button className="secondary-button" onClick={fetchAllRooms}>Refresh All</button>
          </div>
          {loading && <p>Loading rooms from local MySQL...</p>}
          {error && <p className="error-message">{error}</p>}
          <div className="rooms-grid">
            {rooms.length === 0 && !loading && !error && (
              <div className="empty-state">No rooms available yet.</div>
            )}
            {rooms.map((room) => (
              <article key={room.id || room.name} className="room-card">
                {room.image && <img src={room.image.startsWith('http') ? room.image : room.image} alt={room.name} />}
                <div className="room-card-body">
                  <h4>{room.name || 'Room name'}</h4>
                  <p>{room.description || room.type || 'Room description'}</p>
                  <div className="room-meta">
                    <span>{room.type || 'Standard'}</span>
                    <span>{room.capacity ? `Pax: ${room.capacity}` : '2 Guests'}</span>
                  </div>
                  <button
                    className="discover-button-sm"
                    onClick={() => openRoomDetails(room)}
                  >
                    View Details
                  </button>
                </div>
              </article>
            ))}
          </div>
        </section>
      </main>

      {/* Room Details Modal */}
      {roomDetailsModalOpen && selectedRoomForDetails && (
        <div className="modal-backdrop" onClick={() => setRoomDetailsModalOpen(false)}>
          <div className="room-details-modal" onClick={(event) => event.stopPropagation()}>
            <button className="modal-close" onClick={() => setRoomDetailsModalOpen(false)}>
              ×
            </button>
            <div className="modal-content-wrapper">
              <div className="room-details-image">
                <img src={encodeURI(selectedRoomForDetails.image)} alt={selectedRoomForDetails.title} />
              </div>
              <div className="room-details-body">
                <h2>{selectedRoomForDetails.title}</h2>
                <p className="room-type-tag">{selectedRoomForDetails.type} • {selectedRoomForDetails.pax}</p>
                <p className="room-description">{selectedRoomForDetails.description}</p>

                <div className="amenities-section">
                  <h3>Family-friendly Amenities</h3>
                  <h4>Room Amenities</h4>
                  <ul className="amenities-list">
                    {(selectedRoomForDetails.amenities || amenitiesList).map((amenity) => (
                      <li key={amenity}>
                        <span className="amenity-check">✓</span>
                        {amenity}
                      </li>
                    ))}
                  </ul>
                </div>

                <div className="whats-included-section">
                  <h3>What's included in this suite?</h3>
                  <p>{selectedRoomForDetails.whatsIncluded || 'Luxurious amenities with premium service'}</p>
                </div>

                <div className="availability-booking-section">
                  <h3>Availability & Booking</h3>
                  <form className="booking-form modal-booking-form" onSubmit={handleReserve}>
                    <div className="booking-grid">
                      <label>
                        Check In
                        <input type="date" value={checkIn} min={today} onChange={(e) => setCheckIn(e.target.value)} />
                      </label>
                      <label>
                        Check Out
                        <input type="date" value={checkOut} min={checkOutMin} onChange={(e) => setCheckOut(e.target.value)} />
                      </label>
                      <label>
                        Guests
                        <select value={guestCount} onChange={(e) => setGuestCount(Number(e.target.value))}>
                          <option value={1}>1 Adult</option>
                          <option value={2}>2 Adults</option>
                          <option value={3}>3 Adults</option>
                          <option value={4}>4 Adults</option>
                        </select>
                      </label>
                    </div>
                    {reserveError && <div className="form-note error">{reserveError}</div>}
                    {reserveStatus && <div className="form-note success">{reserveStatus}</div>}
                    <button type="submit" className="primary-button full-width">
                      Complete Booking
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

export default App;
