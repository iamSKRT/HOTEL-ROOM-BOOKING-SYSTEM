// TypeScript Interfaces for Hotel Booking System

/**
 * Room Category interface
 */
export interface RoomCategory {
  id: number;
  name: string;
  price: number;
  description: string;
}

/**
 * Room interface
 */
export interface Room {
  id: number;
  name: string;
  category_id: number;
  category?: RoomCategory;
  price: number;
  description: string;
  image_url: string;
  amenities: string[];
  capacity: number;
  created_at: string;
  updated_at: string;
}

/**
 * Customer interface
 */
export interface Customer {
  id: number;
  name: string;
  email: string;
  phone: string;
  address: string;
  created_at: string;
}

/**
 * Booking interface
 */
export interface Booking {
  id: number;
  customer_id: number;
  room_id: number;
  customer?: Customer;
  room?: Room;
  check_in_date: string;
  check_out_date: string;
  number_of_guests: number;
  total_price: number;
  payment_method: string;
  status: 'pending' | 'paid' | 'confirmed' | 'cancelled';
  created_at: string;
  updated_at: string;
}

/**
 * Payment interface
 */
export interface Payment {
  id: number;
  booking_id: number;
  booking?: Booking;
  amount: number;
  payment_method: 'gcash' | 'maya' | 'card';
  transaction_id: string;
  status: 'pending' | 'completed' | 'failed';
  created_at: string;
}

/**
 * Booking form data interface
 */
export interface BookingFormData {
  customer_name: string;
  customer_email: string;
  customer_phone: string;
  room_id: number;
  check_in_date: string;
  check_out_date: string;
  number_of_guests: number;
  number_of_rooms: number;
  payment_method: 'gcash' | 'maya';
}

/**
 * API Response wrapper interface
 */
export interface ApiResponse<T> {
  success: boolean;
  message: string;
  data?: T;
  error?: string;
}

/**
 * Pagination interface
 */
export interface PaginationParams {
  page: number;
  limit: number;
  category_id?: number;
}
