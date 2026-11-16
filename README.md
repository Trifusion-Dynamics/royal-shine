# Royal Shine

Premium jewellery catalogue and enquiry platform built with Next.js, NestJS, Prisma and Neon PostgreSQL.

## Architecture
- `frontend/`: Next.js App Router storefront and admin experience.
- `backend/`: NestJS REST API, authentication, authorization and business modules.
- `backend/prisma/`: PostgreSQL schema and seed routines.
- `docs/`: architecture and API documentation.

The active migration branch contains no Laravel/PHP runtime, Composer application, Blade templates or Filament admin panel. Historical Git history and the old legacy branch are retained for rollback until the replacement is verified.

## Local development
Requires Node.js 20+, pnpm and PostgreSQL/Neon.

```bash
pnpm install
cp backend/.env.example backend/.env
cp frontend/.env.example frontend/.env.local
# Fill backend/.env with rotated database and provider credentials.
pnpm --filter backend prisma:generate
pnpm dev
```

- Storefront: http://localhost:3000
- API: http://localhost:4000/api/v1
- Swagger: http://localhost:4000/api/docs
- Admin sign-in: http://localhost:3000/admin/login

## Production environment
Backend: DATABASE_URL, JWT_SECRET, CORS_ORIGIN (comma-separated exact origins), PORT, BACKEND_URL, and either Cloudinary or ImageKit credentials.
Frontend: NEXT_PUBLIC_API_URL (public NestJS API base including /api/v1) and NEXT_PUBLIC_SITE_URL.

Never commit production secrets. Do not run destructive schema pushes against a database containing data. Review Prisma changes and back up production data before migrations.

## Deployment
- Vercel project root: `frontend`.
- Render Node web service: repository root; build/start commands target the backend workspace.
- Both services must deploy from the reviewed migration branch.

## Delivery status
The storefront landing page, admin sign-in entry and admin overview shell are being established on this branch. Dedicated CRUD screens for all management modules, remaining public content routes, automated tests and complete production end-to-end verification must pass before treating the platform as fully production-ready.
