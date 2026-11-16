# Royal Shine Deployment Guide

## Prerequisites
- Node.js 18+
- PostgreSQL database (Neon recommended)
- ImageKit account for image optimization
- Vercel account for deployment

## 1. Database Setup

```bash
# Backend
cd backend
cp .env.example .env
# Edit .env with your DATABASE_URL, IMAGEKIT credentials, JWT_SECRET

# Install dependencies
pnpm install

# Push schema and seed data
pnpm prisma:push
pnpm prisma:seed
```

## 2. Frontend Setup

```bash
# Frontend
cd frontend
cp .env.local.example .env.local
# Edit .env.local with your API URL and ImageKit endpoint

# Install dependencies
pnpm install

# Build
pnpm build
```

## 3. Deploy to Vercel

### Backend (NestJS)
1. Push code to GitHub
2. Import backend as a Vercel project
3. Add environment variables from `.env`
4. Deploy

### Frontend (Next.js)
1. Import frontend as a Vercel project  
2. Add environment variables from `.env.local`
3. Set `NEXT_PUBLIC_API_URL` to your backend URL
4. Deploy

## 4. ImageKit Configuration

Set these in your backend `.env`:
```
IMAGEKIT_PUBLIC_KEY=your_public_key
IMAGEKIT_PRIVATE_KEY=your_private_key
IMAGEKIT_URL_ENDPOINT=https://ik.imagekit.io/your_imagekit_id
```

And in frontend `.env.local`:
```
NEXT_PUBLIC_IMAGEKIT_URL_ENDPOINT=https://ik.imagekit.io/your_imagekit_id
```

## Project Structure

```
royal-shine/
├── backend/           # NestJS API + Prisma
├── frontend/          # Next.js frontend
├── vercel.json        # Vercel deployment config
└── README.md
```

## Routes

| Route | Description |
|-------|-------------|
| `/` | Homepage with hero, featured products, categories |
| `/jewellery` | Product catalog with filters |
| `/product/[slug]` | Product detail page |
| `/collections` | Collections overview |
| `/gallery` | Image gallery |
| `/about-us` | About page |
| `/contact-us` | Contact form |
