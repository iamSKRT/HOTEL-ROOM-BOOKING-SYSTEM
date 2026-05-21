import express from 'express';
import cors from 'cors';
import dotenv from 'dotenv';
import { runQuery, runInsert } from './db.js';

dotenv.config();

const app = express();
const port = process.env.PORT || 5000;

app.use(cors({ origin: 'http://localhost:5173' }));
app.use(express.json());

const sampleRooms = [
  {
    id: 1,
    name: 'Coral Breeze Room',
    type: 'Standard',
    capacity: 2,
    description: 'A calming coastal retreat designed for pure relaxation and comfort.',
    image: 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=800&q=80',
  },
  {
    id: 2,
    name: 'Seabreeze Comfort Room',
    type: 'Standard',
    capacity: 2,
    description: 'Soft ocean tones with a peaceful island ambiance.',
    image: 'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=800&q=80',
  },
  {
    id: 3,
    name: 'Azure Horizon Deluxe',
    type: 'Deluxe',
    capacity: 4,
    description: 'Elegant ocean horizon views with premium interiors.',
    image: 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80',
  },
];

const sampleBookings = [
  {
    id: 1,
    room_id: 1,
    checkin: '2026-05-10',
    checkout: '2026-05-13',
    guest_count: 2,
  },
  {
    id: 2,
    room_id: 2,
    checkin: '2026-05-16',
    checkout: '2026-05-19',
    guest_count: 3,
  },
];

const handleRoomRequest = async (req, res) => {
  try {
    const rows = await runQuery('SELECT id, name, type, capacity, description, image FROM rooms');
    if (!rows.length) {
      return res.json({ rooms: sampleRooms, message: 'No rows found; returning sample rooms.' });
    }
    return res.json({ rooms: rows });
  } catch (error) {
    console.error('MySQL error:', error.message);
    return res.status(500).json({
      rooms: sampleRooms,
      error: 'MySQL query failed. Verify your database and rooms table.',
      details: error.message,
    });
  }
};

app.get('/api/all', handleRoomRequest);
app.get('/api/rooms', handleRoomRequest);

app.get('/api/bookings', async (req, res) => {
  try {
    const rows = await runQuery('SELECT id, room_id, checkin, checkout, guest_count FROM bookings');
    if (!rows.length) {
      return res.json({ bookings: sampleBookings, message: 'No rows found; returning sample bookings.' });
    }
    return res.json({ bookings: rows });
  } catch (error) {
    console.error('MySQL error:', error.message);
    return res.status(500).json({
      bookings: sampleBookings,
      error: 'MySQL query failed. Verify your database and bookings table.',
      details: error.message,
    });
  }
});

app.post('/api/reserve', async (req, res) => {
  const { roomId, checkIn, checkOut, guestCount } = req.body;

  if (!roomId || !checkIn || !checkOut) {
    return res.status(400).json({ error: 'roomId, checkIn, and checkOut are required.' });
  }

  if (new Date(checkOut) <= new Date(checkIn)) {
    return res.status(400).json({ error: 'checkOut must be later than checkIn.' });
  }

  try {
    const overlappingRooms = await runQuery(
      'SELECT id FROM bookings WHERE room_id = ? AND NOT (checkout <= ? OR checkin >= ?)',
      [roomId, checkIn, checkOut]
    );

    if (overlappingRooms.length) {
      return res.status(409).json({ error: 'Selected dates are already booked for that room.' });
    }

    const result = await runInsert(
      'INSERT INTO bookings (room_id, checkin, checkout, guest_count) VALUES (?, ?, ?, ?)',
      [roomId, checkIn, checkOut, guestCount || 1]
    );

    return res.json({
      booking: {
        id: result.insertId,
        room_id: roomId,
        checkin: checkIn,
        checkout: checkOut,
        guest_count: guestCount || 1,
      },
    });
  } catch (error) {
    console.error('Reservation error:', error.message);
    return res.status(500).json({ error: 'Reservation failed.', details: error.message });
  }
});

app.listen(port, () => {
  console.log(`Server listening on http://localhost:${port}`);
});
