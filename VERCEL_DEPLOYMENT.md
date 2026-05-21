# Vercel Deployment Guide

## Setup Instructions for Vercel Deployment

### 1. Prepare Your PHP Backend
- Keep your PHP backend running on traditional hosting (your current server)
- Note the PHP domain URL (e.g., `https://your-api-domain.com`)
- Ensure CORS is enabled on your PHP backend to accept requests from your Vercel domain

### 2. Add CORS Headers to PHP Backend
Update your PHP backend to include CORS headers. Add this to your `config.php` or main API files:

```php
// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
```

### 3. Deploy to Vercel

**Option A: Using Vercel CLI**
```bash
npm install -g vercel
vercel
```

**Option B: Using GitHub Integration**
1. Push your code to GitHub
2. Go to vercel.com and sign in
3. Click "New Project" → "Import Git Repository"
4. Select your repository
5. Click "Deploy"

### 4. Set Environment Variables on Vercel

After deployment, configure the environment variable:

1. Go to your Vercel project dashboard
2. Navigate to **Settings** → **Environment Variables**
3. Add a new variable:
   - **Name**: `VITE_API_BASE_URL`
   - **Value**: `https://your-php-domain.com` (replace with your actual PHP backend domain)
4. Select environments: Production, Preview, Development
5. Click "Save"

### 5. Verify Deployment

- Visit your Vercel deployment URL
- Test the room listing, booking, and payment features
- Check browser console (F12) for any CORS errors

## Troubleshooting

### CORS Errors
If you see errors like "Cross-Origin Request Blocked", make sure:
1. PHP backend has CORS headers enabled
2. `VITE_API_BASE_URL` is set correctly on Vercel
3. Your PHP domain is accessible from the internet

### API 404 Errors
Ensure your PHP backend API endpoints match those called in the React code:
- `/api/all` - Get all rooms
- `/api/bookings` - Get bookings
- `/api/reserve` - Create a booking

## Local Development

### Running Locally
```bash
cd client
npm install
npm run dev
```

The app will use `http://localhost:5000` from `.env.local` by default.

### Testing with Remote PHP Backend
To test with your production PHP backend locally:
1. Update `.env.local` with your PHP domain
2. Restart the dev server

## Files Modified for Deployment

- `client/src/App.jsx` - Updated to use `VITE_API_BASE_URL` environment variable
- `client/src/AppNew.jsx` - Updated to use `VITE_API_BASE_URL` environment variable
- `client/.env.example` - Template for environment variables
- `client/.env.local` - Local development configuration
- `vercel.json` - Vercel build configuration
- `.vercelignore` - Files to exclude from deployment
