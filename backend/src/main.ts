import { NestFactory } from '@nestjs/core';
import { AppModule } from './app.module';
import { ValidationPipe } from '@nestjs/common';
import { SwaggerModule, DocumentBuilder } from '@nestjs/swagger';
import { AllExceptionsFilter } from './common/filters/all-exceptions.filter';
import helmet from 'helmet';
import * as compression from 'compression';
import * as express from 'express';
import * as path from 'path';

async function bootstrap() {
  const app = await NestFactory.create(AppModule, { logger: ['error', 'warn', 'log'] });
  (app.getHttpAdapter() as any).getInstance().set('trust proxy', 1);
  app.use(helmet({ crossOriginResourcePolicy: { policy: 'cross-origin' } }));
  app.use(compression());
  const uploadsPath = path.resolve(process.cwd(), 'uploads');
  app.use('/uploads', express.static(uploadsPath, { fallthrough: false, maxAge: '7d' }));

  const configuredOrigins = (process.env.CORS_ORIGIN || 'http://localhost:3000').split(',').map((origin) => origin.trim()).filter(Boolean);
  app.enableCors({
    origin: (origin, callback) => {
      if (!origin || configuredOrigins.includes(origin)) return callback(null, true);
      return callback(new Error('Origin not allowed by CORS'));
    },
    methods: ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
    allowedHeaders: ['Content-Type', 'Authorization', 'Accept'],
    credentials: true,
  });

  app.setGlobalPrefix('api/v1');
  app.useGlobalPipes(new ValidationPipe({ whitelist: true, forbidNonWhitelisted: true, transform: true, transformOptions: { enableImplicitConversion: true } }));
  app.useGlobalFilters(new AllExceptionsFilter());

  const config = new DocumentBuilder().setTitle('Royal Shine API').setDescription('Royal Shine Jewellery Catalogue API').setVersion('1.0')
    .addBearerAuth({ type: 'http', scheme: 'bearer', bearerFormat: 'JWT' }, 'JWT-auth')
    .addTag('Auth').addTag('Products').addTag('Categories').addTag('Collections').addTag('Banners')
    .addTag('Enquiries').addTag('Galleries').addTag('Pages').addTag('Settings').addTag('Testimonials')
    .addTag('Users').addTag('Upload').addTag('Dashboard').build();
  SwaggerModule.setup('api/docs', app, SwaggerModule.createDocument(app, config), { swaggerOptions: { persistAuthorization: true } });

  const port = Number(process.env.PORT || 4000);
  await app.listen(port, '0.0.0.0');
  console.log(`Royal Shine API listening on port ${port}`);
}
bootstrap();
