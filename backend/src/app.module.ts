import { Module } from '@nestjs/common';
import { ConfigModule } from '@nestjs/config';
import { APP_GUARD, APP_FILTER } from '@nestjs/core';
import { PrismaModule } from './database/prisma.module';
import { AllExceptionsFilter } from './common/filters/all-exceptions.filter';
import { JwtAuthGuard } from './common/guards/jwt-auth.guard';
import { RolesGuard } from './common/guards/roles.guard';

// Feature modules
import { AuthModule } from './modules/auth/auth.module';
import { ProductsModule } from './modules/products/products.module';
import { CategoriesModule } from './modules/categories/categories.module';
import { CollectionsModule } from './modules/collections/collections.module';
import { BannersModule } from './modules/banners/banners.module';
import { EnquiriesModule } from './modules/enquiries/enquiries.module';
import { GalleriesModule } from './modules/galleries/galleries.module';
import { PagesModule } from './modules/pages/pages.module';
import { SettingsModule } from './modules/settings/settings.module';
import { TestimonialsModule } from './modules/testimonials/testimonials.module';
import { UsersModule } from './modules/users/users.module';
import { UploadModule } from './modules/upload/upload.module';
import { DashboardModule } from './modules/dashboard/dashboard.module';

@Module({
  imports: [
    ConfigModule.forRoot({ isGlobal: true }),
    PrismaModule,
    AuthModule,
    ProductsModule,
    CategoriesModule,
    CollectionsModule,
    BannersModule,
    EnquiriesModule,
    GalleriesModule,
    PagesModule,
    SettingsModule,
    TestimonialsModule,
    UsersModule,
    UploadModule,
    DashboardModule,
  ],
  providers: [
    { provide: APP_FILTER, useClass: AllExceptionsFilter },
    { provide: APP_GUARD, useClass: JwtAuthGuard },
    { provide: APP_GUARD, useClass: RolesGuard },
  ],
})
export class AppModule {}
