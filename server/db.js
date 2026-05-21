import mysql from 'mysql2/promise';
import dotenv from 'dotenv';

dotenv.config();

const pool = mysql.createPool({
  host: process.env.DB_HOST || 'localhost',
  user: process.env.DB_USER || 'root',
  password: process.env.DB_PASSWORD || '',
  database: process.env.DB_NAME || 'hotel_db',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0,
});

export async function runQuery(sql, values = []) {
  const [rows] = await pool.execute(sql, values);
  return rows;
}

export async function runInsert(sql, values = []) {
  const [result] = await pool.execute(sql, values);
  return result;
}

export default pool;
