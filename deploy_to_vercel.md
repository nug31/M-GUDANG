# Deploying to Vercel

This guide provides instructions for deploying the Gudang Mitra frontend to Vercel while using the existing PHP backend on Hostinger.

## Prerequisites

1. A Vercel account (sign up at https://vercel.com/signup)
2. Your GitHub repository connected to Vercel
3. Existing backend deployed on Hostinger at gudang.nugjourney.com

## Deployment Steps

### 1. Connect Your Repository to Vercel

1. Go to https://vercel.com/new
2. Select your GitHub repository (nug31/M-GUDANG)
3. Configure the project with the following settings:
   - Framework Preset: Vite
   - Build Command: `npm run build:vercel`
   - Output Directory: `dist`
   - Install Command: `npm install`

### 2. Configure Environment Variables

Add the following environment variables in the Vercel project settings:

```
VITE_API_URL=https://gudang.nugjourney.com
VITE_API_BASE_PATH=/database/api.php
VITE_REGISTER_API_PATH=/database/simple_login.php
VITE_REQUEST_API_PATH=/database/simple_request_handler.php
VITE_ITEM_API_PATH=/database/simple_add_item.php
VITE_USER_API_PATH=/database/simple_user_management.php
VITE_CATEGORY_API_PATH=/database/simple_category_handler.php
```

### 3. Deploy

1. Click "Deploy" in the Vercel dashboard
2. Wait for the build to complete
3. Your frontend will be deployed to a URL like: https://m-gudang.vercel.app

### 4. Custom Domain (Optional)

1. In the Vercel dashboard, go to your project settings
2. Click on "Domains"
3. Add your custom domain and follow the instructions to configure DNS

## Important Notes

- This deployment only includes the frontend React application
- The backend PHP files remain on your Hostinger server
- All API requests from the frontend will be directed to gudang.nugjourney.com
- Make sure CORS is properly configured on your backend to accept requests from your Vercel domain

## Troubleshooting

If you encounter issues with API requests, check:

1. CORS headers on your backend
2. Environment variables in Vercel
3. Network requests in the browser developer tools

## Updating the Deployment

Any new commits pushed to your GitHub repository will automatically trigger a new deployment on Vercel.
