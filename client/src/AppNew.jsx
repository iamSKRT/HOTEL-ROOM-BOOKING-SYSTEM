import { useEffect, useState, useRef } from 'react';
import gsap from 'gsap';

const SERVER_URL = 'http://localhost:5000';

const formatDateInput = (date) => date.toISOString().slice(0, 10);
const addDays = (dateString, offset) => {
  const date = new Date(dateString);
  date.setDate(date.getDate() + offset);
  return formatDateInput(date);
};

const roomAmenities = {
  standard: ['Air Conditioner', 'Cable TV', 'WiFi & Internet', 'Towels', 'Slippers', 'Hair Dryer', 'Shampoo', 'Safe Box', 'Welcome Drinks'],
  deluxe: ['Air Conditioner', 'Cable TV', 'WiFi & Internet', 'Towels', 'Slippers', 'Hair Dryer', 'Shampoo', 'Espresso Machine', 'Safe Box', 'Welcome Drinks', 'In-room Refrigerator', 'Premium Toiletries'],
  suite: ['Air Conditioner', 'Cable TV', 'WiFi & Internet', 'Towels', 'Slippers', 'Hair Dryer', 'Shampoo', 'Espresso Machine', 'Safe Box', 'Welcome Drinks', 'In-room Refrigerator', 'Premium Toiletries', 'Spa Bath', 'Rooftop Access', 'Complimentary Wine'],
  villa: ['Air Conditioner', 'Cable TV', 'WiFi & Internet', 'Towels', 'Slippers', 'Hair Dryer', 'Shampoo', 'Espresso Machine', 'Safe Box', 'Welcome Drinks', 'In-room Refrigerator', 'Premium Toiletries', 'Private Beach Access', 'Personal Chef Option', 'Concierge Service'],
};

const experienceCards = [
  {
    title: 'Spa & Wellness',
    image: '/spaandwellness.png',
    description:
      'Set in lush jungle, our modern spa embodies the calm of nature, offering extraordinary visual and auditory experiences that pamper and heal.',
  },
  {
    title: 'Island Activities',
    image: '/islandactivity.png',
    description:
      'Discover the thrill of island adventures with our curated activities, from snorkeling to hiking, all designed to immerse you in the natural beauty of the surroundings.',
  },
  {
    title: 'Gastronomic Dine',
    image: '/food.png',
    description:
      'Indulge in a culinary journey with our diverse menu, featuring locally-sourced ingredients and expertly crafted dishes that celebrate the flavors of the region.',
  },
];

const roomCards = [
  {
    id: 1,
    title: 'Coral Breeze Room',
    type: 'Standard',
    pax: '2 Guests',
    image: '/coral.png',
    description: 'A calming coastal retreat designed for pure relaxation and comfort.',
    price: '$250/night',
  },
  {
    id: 2,
    title: 'Seabreeze Comfort Room',
    type: 'Standard',
    pax: '2 Guests',
    image: '/seabreeze.png',
    description: 'Soft ocean tones with a peaceful island ambiance.',
    price: '$260/night',
  },
  {
    id: 3,
    title: 'Azure Horizon Deluxe',
    type: 'Deluxe',
    pax: '2–4 Guests',
    image: '/Azure.png',
    description: 'Elegant ocean horizon views with premium interiors.',
    price: '$380/night',
  },
  {
    id: 4,
    title: 'Golden Palm Deluxe',
    type: 'Deluxe',
    pax: '2–4 Guests',
    image: '/Golden.png',
    description: 'Warm tropical luxury with golden ambient lighting.',
    price: '$390/night',
  },
  {
    id: 5,
    title: 'Ocean Pearl Executive Suite',
    type: 'Suites',
    pax: '2–4 Guests',
    image: '/Ocean.png',
    description: 'Spacious suite with panoramic ocean views and premium amenities.',
    price: '$520/night',
  },
  {
    id: 6,
    title: 'Sapphire Wave Suite',
    type: 'Suites',
    pax: '2–4 Guests',
    image: '/Sapphire.png',
    description: 'Luxurious suite with stunning ocean views and elegant design.',
    price: '$530/night',
  },
];

const services = [
  { title: 'Airport Pick-up Service', icon: '🚕', description: 'Seamless luxury transfers from airport to resort.' },
  { title: 'Housekeeping Service', icon: '🧹', description: 'Daily premium room care.' },
  { title: 'High-Speed WiFi', icon: '📶', description: 'Ultra-fast internet throughout property.' },
  { title: 'Laundry Service', icon: '👕', description: 'Professional garment care.' },
  { title: 'In-Room Dining', icon: '🍽️', description: 'Elegant dining served to your room.' },
  { title: 'Private Parking', icon: '🅿️', description: 'Secure 24/7 valet parking.' },
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

function RoomDetailModal({ room, onClose, bookings }) {
  const [checkIn, setCheckIn] = useState('');
  const [checkOut, setCheckOut] = useState('');
  const [guestCount, setGuestCount] = useState(2);
  const today = formatDateInput(new Date());
  const checkOutMin = checkIn ? addDays(checkIn, 1) : addDays(today, 1);

  const roomBookings = bookings.filter((booking) => String(booking.room_id) === String(room.id));

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

  const handleBooking = async (event) => {
    event.preventDefault();
    if (!checkIn || !checkOut || bookingConflict()) return;

    try {
      const response = await fetch(`${SERVER_URL}/api/reserve`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ roomId: room.id, checkIn, checkOut, guestCount }),
      });
      if (response.ok) {
        alert('Room booked successfully!');
        onClose();
      }
    } catch (error) {
      alert('Booking failed: ' + error.message);
    }
  };

  const roomType = room.type.toLowerCase().replace(' ', '');
  const amenities = roomAmenities[roomType] || roomAmenities.standard;

  return (
    <div className="modal-overlay" onClick={onClose}>
      <div className="room-detail-modal" onClick={(e) => e.stopPropagation()}>
        <button className="modal-close" onClick={onClose}>✕</button>

        <div className="room-detail-header">
          <img src={room.image} alt={room.title} className="detail-hero-image" />
        </div>

        <div className="room-detail-content">
          <div className="detail-main">
            <h1>{room.title}</h1>
            <p className="room-type-badge">{room.type} • {room.pax}</p>
            <p className="room-description">{room.description}</p>
            <p className="room-price">{room.price}</p>

            <section className="amenities-section">
              <h3>Family-friendly Room Amenities</h3>
              <div className="amenities-grid">
                {amenities.map((amenity) => (
                  <div key={amenity} className="amenity-item">
                    <span className="amenity-check">✓</span>
                    <span>{amenity}</span>
                  </div>
                ))}
              </div>
            </section>

            <section className="whats-included">
              <h3>What's Included in This Suite?</h3>
              <ul className="included-list">
                <li>Premium bedding and pillow selection</li>
                <li>Scenic views of the ocean and landscape</li>
                <li>24-hour concierge service</li>
                <li>Complimentary daily breakfast</li>
                <li>Access to all resort facilities</li>
                <li>Spa and wellness credits</li>
                <li>Priority dinner reservations</li>
                <li>Personalized turndown service</li>
              </ul>
            </section>
          </div>

          <div className="booking-sidebar">
            <div className="booking-card-detail">
              <h3>Check Availability & Book</h3>
              <form onSubmit={handleBooking} className="detail-booking-form">
                <label>
                  Check In
                  <input type="date" value={checkIn} min={today} onChange={(e) => setCheckIn(e.target.value)} required />
                </label>
                <label>
                  Check Out
                  <input type="date" value={checkOut} min={checkOutMin} onChange={(e) => setCheckOut(e.target.value)} required />
                </label>
                <label>
                  Guests
                  <select value={guestCount} onChange={(e) => setGuestCount(Number(e.target.value))}>
                    <option value={1}>1 Guest</option>
                    <option value={2}>2 Guests</option>
                    <option value={3}>3 Guests</option>
                    <option value={4}>4 Guests</option>
                  </select>
                </label>

                {bookingConflict() && <p className="conflict-warning">Dates unavailable. Choose different dates.</p>}
                {roomBookings.length > 0 && (
                  <div className="booked-dates-info">
                    <p className="info-label">Booked Dates:</p>
                    {roomBookings.map((b) => (
                      <p key={b.id}>{b.checkin} → {b.checkout}</p>
                    ))}
                  </div>
                )}

                <button type="submit" className="book-now-btn" disabled={bookingConflict() || !checkIn || !checkOut}>
                  Book Now
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

function App() {
  const [menuOpen, setMenuOpen] = useState(false);
  const [subMenuOpen, setSubMenuOpen] = useState(false);
  const [bookingModalOpen, setBookingModalOpen] = useState(false);
  const [selectedRoom, setSelectedRoom] = useState(null);
  const [rooms, setRooms] = useState([]);
  const [bookings, setBookings] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [testimonialIndex, setTestimonialIndex] = useState(0);
  const roomsGridRef = useRef(null);

  const today = formatDateInput(new Date());

  useEffect(() => {
    fetchAllRooms();
    fetchBookings();
  }, []);

  // GSAP animation for room cards
  useEffect(() => {
    if (roomsGridRef.current && rooms.length > 0) {
      const cards = roomsGridRef.current.querySelectorAll('.gallery-card');
      gsap.fromTo(
        cards,
        {
          opacity: 0,
          x: 100,
        },
        {
          opacity: 1,
          x: 0,
          duration: 0.8,
          stagger: 0.15,
          ease: 'power2.out',
        }
      );
    }
  }, [rooms]);

  const fetchAllRooms = async () => {
    setLoading(true);
    setError('');
    try {
      const response = await fetch(`${SERVER_URL}/api/all`);
      if (!response.ok) throw new Error(`Server error: ${response.status}`);
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
      if (!response.ok) throw new Error(`Server error: ${response.status}`);
      const data = await response.json();
      setBookings(data.bookings || []);
    } catch (err) {
      console.warn('Could not load bookings:', err.message);
      setBookings([]);
    }
  };

  return (
    <div className="app-shell">
      {/* Overlay */}
      <div className={`overlay ${menuOpen ? 'visible' : ''}`} onClick={() => setMenuOpen(false)} />

      {/* Sidebar */}
      <aside className={`sidebar ${menuOpen ? 'active' : ''}`}>
        <div className="sidebar-header">
          <button className="close-button" onClick={() => setMenuOpen(false)}>✕</button>
        </div>
        <nav className="sidebar-nav">
          <a href="#" className="sidebar-link">Home</a>
          <a href="#rooms" className="sidebar-link">Rooms</a>
          <a href="#" className="sidebar-link">About The Hotel</a>
          <a href="#" className="sidebar-link">Premium Services</a>
          <button className="submenu-toggle" onClick={() => setSubMenuOpen(!subMenuOpen)}>
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

      {/* Header */}
      <header className="hero-section">
        <div className="hero-overlay" />
        <div className="navbar">
          <div className="navbar-left">
            <button className="menu-button" onClick={() => setMenuOpen(true)}>☰</button>
          </div>
          <h1 className="navbar-brand">Palmwave Resort & Suites</h1>
          <div className="navbar-right">
            <span className="contact-info">Tel: +63 36 345 67 88</span>
            <button className="reserve-btn" onClick={() => setBookingModalOpen(true)}>Reserve Now</button>
          </div>
        </div>

        <div className="hero-content">
          <section className="hero-copy">
            <p className="eyebrow">A Sanctuary of Tranquility</p>
            <h1>Refined Countryside Escape at Palmwave Resort & Suites</h1>
            <p>Experience timeless elegance where nature meets sophistication. Indulge in serene landscapes, curated luxury, and unforgettable stays.</p>
            <button className="cta-button">Explore Suites</button>
          </section>

          <section className="hero-booking-card">
            <h2>Reserve Your Stay</h2>
            <form className="booking-form" onSubmit={(e) => e.preventDefault()}>
              <label>
                Check In
                <input type="date" min={today} />
              </label>
              <label>
                Check Out
                <input type="date" min={addDays(today, 1)} />
              </label>
              <label>
                Room Type
                <select>
                  <option>Standard Room</option>
                  <option>Deluxe Room</option>
                  <option>Suite</option>
                  <option>Villa</option>
                </select>
              </label>
              <div className="guest-grid">
                <select>
                  <option>1 Adult</option>
                  <option>2 Adults</option>
                  <option>3 Adults</option>
                </select>
                <select>
                  <option>0 Children</option>
                  <option>1 Child</option>
                  <option>2 Children</option>
                </select>
              </div>
              <button className="primary-button">Check Availability</button>
            </form>
          </section>
        </div>
      </header>

      {/* Experience Section */}
      <section className="experience-section" id="experience">
        <div className="experience-top">
          <img className="experience-icon" src="/palm-wave-icon.png" alt="Palmwave Icon" />
          <h2>Unforgettable Experience</h2>
          <p>One of the World's Most Desirable Locations</p>
          <p>A superior, 5-star resort embodying the very best of Fiji Islands luxury, tranquility & sophistication.</p>
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

      {/* Rooms Showcase */}
      <section className="rooms-showcase" id="rooms">
        <div className="rooms-header">
          <p>Indulge in a World-Class Stay Experience</p>
          <h2>Experience Luxurious Rooms & Suites with Breathtaking Views</h2>
          <p>Escape into a sanctuary where the mountains meet endless ocean horizons, and every stay is defined by refined luxury, natural beauty, and serene elegance.</p>
        </div>
        {loading && <p className="loading-text">Loading rooms...</p>}
        {error && <p className="error-text">{error}</p>}
        <div className="room-gallery" ref={roomsGridRef}>
          {(rooms.length > 0 ? rooms : roomCards).map((room) => (
            <article key={room.id} className="gallery-card" onClick={() => setSelectedRoom(room)}>
              <div className="gallery-image">
                <img src={room.image} alt={room.title} />
                <div className="gallery-overlay">
                  <button className="discover-btn">Discover More</button>
                </div>
              </div>
              <div className="gallery-content">
                <h3>{room.title}</h3>
                <p>{room.type} • Pax: {room.pax}</p>
                <p className="room-brief">{room.description}</p>
              </div>
            </article>
          ))}
        </div>
      </section>

      {/* Services Section */}
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

      {/* Testimonials */}
      <section className="testimonial-section">
        <div className="testimonial-intro">
          <span>Voice From Our Guests</span>
          <h2>Luxury Experiences Shared</h2>
          <p>Moments Cherished by Our Guests at Palmwave Resort & Suites</p>
        </div>
        <div className="testimonial-slider">
          <article className="testimonial-card">
            <p>"{testimonials[testimonialIndex].quote}"</p>
            <div className="testimonial-meta">
              <strong>{testimonials[testimonialIndex].name}</strong>
              <span>{testimonials[testimonialIndex].source}</span>
            </div>
          </article>
          <div className="testimonial-dots">
            {testimonials.map((_, index) => (
              <button key={index} className={testimonialIndex === index ? 'dot active' : 'dot'} onClick={() => setTestimonialIndex(index)} type="button" />
            ))}
          </div>
        </div>
      </section>

      {/* Room Detail Modal */}
      {selectedRoom && <RoomDetailModal room={selectedRoom} onClose={() => setSelectedRoom(null)} bookings={bookings} />}

      {/* Booking Modal */}
      {bookingModalOpen && (
        <div className="modal-overlay" onClick={() => setBookingModalOpen(false)}>
          <div className="booking-modal" onClick={(e) => e.stopPropagation()}>
            <button className="modal-close" onClick={() => setBookingModalOpen(false)}>✕</button>
            <div className="modal-body">
              <div className="modal-image">
                <img src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=900&q=80" alt="Booking" />
              </div>
              <div className="modal-form">
                <h2>Book Your Stay</h2>
                <form className="booking-form">
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
                    <select><option>1 Room</option><option>2 Rooms</option></select>
                  </label>
                  <label>
                    Guests
                    <select><option>1 Adult, 0 Children</option><option>2 Adults, 1 Child</option></select>
                  </label>
                  <button className="primary-button">Check Availability</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

export default App;
