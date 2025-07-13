# 🚀 Vercel Deployment Guide for TaskMaster Pro

## Prerequisites
- Vercel account (free at https://vercel.com)
- MongoDB Atlas database (cloud database)
- GitHub repository with your code

## Step-by-Step Deployment

### 1. Prepare Your Project
1. Ensure `vercel.json` is in your project root
2. Create `.env.example` with all required variables
3. Test locally to ensure everything works

### 2. MongoDB Atlas Setup
1. Go to https://cloud.mongodb.com
2. Create a free cluster
3. Create a database user
4. Whitelist all IP addresses (0.0.0.0/0) for Vercel
5. Get your connection string

### 3. Deploy to Vercel

#### Option A: Via Vercel CLI
1. Install Vercel CLI: `npm install -g vercel`
2. Login: `vercel login`
3. Deploy: `vercel` (follow the prompts)
4. Set environment variables: `vercel env add`

#### Option B: Via GitHub Integration
1. Push your code to GitHub
2. Go to Vercel dashboard
3. Click "New Project"
4. Import your GitHub repository
5. Configure environment variables

### 4. Environment Variables
Set these in your Vercel project settings:

| Variable | Value | Example |
|----------|-------|---------|
| `MONGODB_URI` | Your MongoDB Atlas connection string | `mongodb+srv://user:pass@cluster.mongodb.net/taskmaster_pro` |
| `APP_ENV` | `production` | `production` |
| `SESSION_LIFETIME` | `7200` | `7200` |

### 5. Verify Deployment
1. Visit your Vercel domain
2. Test login functionality
3. Check admin account access
4. Verify task creation

## Configuration Files

### vercel.json
```json
{
  "version": 2,
  "builds": [
    {
      "src": "public/index.php",
      "use": "vercel-php@0.6.0"
    }
  ],
  "routes": [
    {
      "src": "/(.*)",
      "dest": "public/index.php"
    }
  ],
  "env": {
    "MONGODB_URI": "@mongodb_uri",
    "APP_ENV": "production",
    "SESSION_LIFETIME": "7200"
  }
}
```

### Directory Structure for Vercel
```
project-root/
├── vercel.json              # Vercel configuration
├── composer.json            # PHP dependencies
├── .env.example             # Environment template
├── public/
│   ├── index.php           # Main API entry point
│   └── app.html            # Frontend application
├── backend/                # PHP backend code
└── vendor/                 # Composer dependencies
```

## Common Issues & Solutions

### Issue: "Function Timeout"
**Solution**: Optimize database queries and reduce processing time

### Issue: "Environment Variables Not Found"
**Solution**: Double-check variable names and values in Vercel settings

### Issue: "MongoDB Connection Failed"
**Solution**: 
- Verify connection string
- Ensure IP whitelist includes 0.0.0.0/0
- Check database user permissions

### Issue: "CORS Errors"
**Solution**: CORS headers are handled in `public/index.php`

## Production Considerations

### Performance
- MongoDB indexes for frequently queried fields
- Optimize PHP code for serverless execution
- Use Vercel's Edge Network for static assets

### Security
- Use environment variables for all sensitive data
- Implement rate limiting if needed
- Regular security updates

### Monitoring
- Use Vercel Analytics
- Monitor MongoDB Atlas metrics
- Set up error logging

## Custom Domain Setup

1. Go to your Vercel project dashboard
2. Click "Domains" tab
3. Add your custom domain
4. Update DNS records as instructed
5. SSL certificate is automatically provisioned

## Scaling Considerations

- Vercel automatically scales based on traffic
- MongoDB Atlas provides automatic scaling
- Consider upgrading plans for high traffic

## Support Resources

- [Vercel Documentation](https://vercel.com/docs)
- [MongoDB Atlas Documentation](https://docs.atlas.mongodb.com/)
- [Vercel PHP Runtime](https://github.com/vercel-community/php)

---

**Your TaskMaster Pro application is now ready for production deployment on Vercel!**
