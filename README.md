# Royal Shine — Next.js + NestJS

> Premium Jewellery Catalogue — migrated from Laravel/PHP to Next.js + NestJS + PostgreSQL

## Stack

| Layer | Technology |
|---|---|
| **Frontend** | Next.js 15, React 19, TypeScript, Tailwind CSS |
| **Backend** | NestJS, TypeScript, Prisma ORM |
| **Database** | PostgreSQL (Neon serverless) |
| **Media** | Cloudinary |
| **Auth** | JWT (access + refresh tokens) |

## Quick Start

```bash
# 1. Install all dependencies (root + workspaces)
pnpm install

# 2. Set up backend environment
cp backend/.env.example backend/.env
# Edit backend/.env — fill in DATABASE_URL, JWT_SECRET, Cloudinary creds

# 3. Set up frontend environment
cp frontend/.env.example frontend/.env.local
# Edit frontend/.env.local if needed (default points to localhost:4000)

# 4. Push Prisma schema to Neon database
pnpm prisma:push

# 5. Run frontend + backend simultaneously
pnpm dev
```

| Service | URL |
|---|---|
| Frontend | http://localhost:3000 |
| Backend API | http://localhost:4000/api/v1 |
| Swagger Docs | http://localhost:4000/api/docs |
| Admin Panel | http://localhost:3000/admin |

## Commands

```bash
# Development
pnpm dev                # Run frontend + backend concurrently
pnpm dev:frontend       # Next.js only
pnpm dev:backend        # NestJS only (watch mode)

# Build
pnpm build              # Build both frontend and backend

# Database
pnpm prisma:push        # Push schema to Neon (no migration history)
pnpm prisma:migrate     # Create & run migration
pnpm prisma:studio      # Open Prisma Studio GUI

# Tests
pnpm test               # Run backend tests (Jest)

# Lint
pnpm lint               # Lint both workspaces
```

## Project Structure

```
royal-shine/
├── frontend/           # Next.js 15 app
│   ├── src/
│   │   ├── app/        # App router pages
│   │   ├── components/ # Shared UI components
│   │   ├── lib/        # API client, utilities
│   │   └── types/      # TypeScript interfaces
│   ├── public/
│   └── package.json
│
├── backend/            # NestJS API
│   ├── src/
│   │   ├── modules/    # Feature modules (products, auth, etc.)
│   │   ├── common/     # Guards, decorators, filters
│   │   └── config/     # App configuration
│   ├── prisma/
│   │   └── schema.prisma
│   └── package.json
│
├── docs/               # Architecture & API docs
│   ├── ARCHITECTURE.md
│   ├── API_DOCUMENTATION.md
│   ├── DATABASE_SCHEMA.md
│   └── MIGRATION_REPORT.md
│
├── package.json        # Root workspace
└── README.md
```

## Environment Variables

### Backend (`backend/.env`)

```env
DATABASE_URL=postgresql://...@neon.tech/royal-shine?sslmode=require
JWT_SECRET=your-secret-key
JWT_EXPIRES_IN=7d
CLOUDINARY_CLOUD_NAME=dupzif1h3
CLOUDINARY_API_KEY=
CLOUDINARY_API_SECRET=
PORT=4000
NODE_ENV=development
```

### Frontend (`frontend/.env.local`)

```env
NEXT_PUBLIC_API_URL=http://localhost:4000/api/v1
NEXT_PUBLIC_CLOUDINARY_CLOUD_NAME=dupzif1h3
```

## Documentation

- [Architecture Overview](docs/ARCHITECTURE.md)
- [API Documentation](docs/API_DOCUMENTATION.md)
- [Database Schema](docs/DATABASE_SCHEMA.md)
- [Migration Report](docs/MIGRATION_REPORT.md)

## Default Admin Credentials

After seeding, log in at `/admin/login`:

| Field | Value |
|---|---|
| Email | admin@royalshine.com |
| Password | (set via seed script) |

---

> See [MIGRATION_REPORT.md](docs/MIGRATION_REPORT.md) for the full list of migrated features and known limitations.
