import axios, { AxiosInstance, AxiosError } from 'axios';
import type { Room, Booking, Payment, BookingFormData, ApiResponse, RoomCategory } from '../types';

/**
 * API Service for Hotel Booking System
 * Handles all backend API calls
 */
class ApiService {
  private api: AxiosInstance;
  private baseURL: string = 'http://localhost/HOTEL%20ROOM%20BOOKING%20SYSTEM/api';

  constructor() {
    this.api = axios.create({
      baseURL: this.baseURL,
      timeout: 10000,
      headers: {
        'Content-Type': 'application/json',
      },
    });

    // Add request interceptor
    this.api.interceptors.request.use(
      config => {
        return config;
      },
      error => Promise.reject(error)
    );

    // Add response interceptor
    this.api.interceptors.response.use(
      response => response.data,
      (error: AxiosError) => {
        console.error('API Error:', error.response?.data || error.message);
        return Promise.reject({
          success: false,
          message: 'API Error',
          error: error.response?.data || error.message,
        });
      }
    );
  }

  /**
   * Fetch all rooms
   */
  async getRooms(categoryId?: number): Promise<ApiResponse<Room[]>> {
    const params = categoryId ? { category_id: categoryId } : {};
    return this.api.get<void, ApiResponse<Room[]>>('/get_rooms.php', { params });
  }

  /**
   * Fetch room categories
   */
  async getCategories(): Promise<ApiResponse<RoomCategory[]>> {
    return this.api.get<void, ApiResponse<RoomCategory[]>>('/get_categories.php');
  }

  /**
   * Fetch single room details
   */
  async getRoomDetails(roomId: number): Promise<ApiResponse<Room>> {
    return this.api.get<void, ApiResponse<Room>>('/get_room_details.php', {
      params: { id: roomId },
    });
  }

  /**
   * Create a new booking
   */
  async createBooking(bookingData: BookingFormData): Promise<ApiResponse<Booking>> {
    return this.api.post<void, ApiResponse<Booking>>('/create_booking.php', bookingData);
  }

  /**
   * Process payment
   */
  async processPayment(bookingId: number, paymentMethod: string, amount: number): Promise<ApiResponse<Payment>> {
    return this.api.post<void, ApiResponse<Payment>>('/process_payment.php', {
      booking_id: bookingId,
      payment_method: paymentMethod,
      amount,
    });
  }

  /**
   * Confirm payment
   */
  async confirmPayment(bookingId: number, transactionId: string): Promise<ApiResponse<Booking>> {
    return this.api.post<void, ApiResponse<Booking>>('/confirm_payment.php', {
      booking_id: bookingId,
      transaction_id: transactionId,
    });
  }

  /**
   * Get available dates for a room
   */
  async getAvailableDates(roomId: number): Promise<ApiResponse<{ available_dates: string[] }>> {
    return this.api.get<void, ApiResponse<{ available_dates: string[] }>>('/get_available_dates.php', {
      params: { room_id: roomId },
    });
  }
}

export default new ApiService();
