import {
  IsString,
  IsOptional,
  IsBoolean,
  IsNumber,
  IsEnum,
  MinLength,
  IsArray,
  ValidateNested,
} from 'class-validator';
import { ApiProperty, ApiPropertyOptional, PartialType } from '@nestjs/swagger';
import { Type } from 'class-transformer';
import { MetalType, GoldPurity, ProductStatus } from '@prisma/client';

export class ProductImageDto {
  @ApiPropertyOptional()
  @IsOptional()
  @IsNumber()
  id?: number;

  @ApiProperty({ example: 'https://res.cloudinary.com/.../img.jpg' })
  @IsString()
  image: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  altText?: string;

  @ApiPropertyOptional({ default: false })
  @IsOptional()
  @IsBoolean()
  isPrimary?: boolean;

  @ApiPropertyOptional({ default: 0 })
  @IsOptional()
  @IsNumber()
  sortOrder?: number;
}

export class CreateProductDto {
  @ApiProperty({ example: '18K Gold Necklace with Pendant' })
  @IsString()
  @MinLength(2)
  name: string;

  @ApiPropertyOptional({ example: '18k-gold-necklace-pendant' })
  @IsOptional()
  @IsString()
  slug?: string;

  @ApiProperty({ example: 'RN-001' })
  @IsString()
  sku: string;

  @ApiProperty({ example: 1 })
  @IsNumber()
  @Type(() => Number)
  categoryId: number;

  @ApiPropertyOptional({ example: 1 })
  @IsOptional()
  @IsNumber()
  @Type(() => Number)
  collectionId?: number;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  shortDescription?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  description?: string;

  @ApiPropertyOptional({ enum: MetalType })
  @IsOptional()
  @IsEnum(MetalType)
  metalType?: MetalType;

  @ApiPropertyOptional({ enum: GoldPurity })
  @IsOptional()
  @IsEnum(GoldPurity)
  goldPurity?: GoldPurity;

  @ApiPropertyOptional()
  @IsOptional()
  @Type(() => Number)
  weight?: number;

  @ApiPropertyOptional({ example: 'g', default: 'g' })
  @IsOptional()
  @IsString()
  weightUnit?: string;

  @ApiPropertyOptional({ example: 'Embedded with diamonds' })
  @IsOptional()
  @IsString()
  stoneDetails?: string;

  @ApiPropertyOptional({ example: 45000 })
  @IsOptional()
  @Type(() => Number)
  price?: number;

  @ApiPropertyOptional({ default: false })
  @IsOptional()
  @IsBoolean()
  priceOnRequest?: boolean;

  @ApiPropertyOptional({ default: false })
  @IsOptional()
  @IsBoolean()
  isFeatured?: boolean;

  @ApiPropertyOptional({ enum: ProductStatus, default: ProductStatus.draft })
  @IsOptional()
  @IsEnum(ProductStatus)
  status?: ProductStatus;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  seoTitle?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  seoDescription?: string;

  @ApiPropertyOptional({ default: 0 })
  @IsOptional()
  @IsNumber()
  @Type(() => Number)
  sortOrder?: number;

  @ApiPropertyOptional({ type: [ProductImageDto] })
  @IsOptional()
  @IsArray()
  @ValidateNested({ each: true })
  @Type(() => ProductImageDto)
  images?: ProductImageDto[];
}

export class UpdateProductDto extends PartialType(CreateProductDto) {}

export class ProductQueryDto {
  @ApiPropertyOptional({ default: 1 })
  @IsOptional()
  @Type(() => Number)
  @IsNumber()
  page?: number = 1;

  @ApiPropertyOptional({ default: 20 })
  @IsOptional()
  @Type(() => Number)
  @IsNumber()
  limit?: number = 20;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  search?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @Type(() => Number)
  @IsNumber()
  categoryId?: number;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  categorySlug?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @Type(() => Number)
  @IsNumber()
  collectionId?: number;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  collectionSlug?: string;

  @ApiPropertyOptional({ enum: ProductStatus })
  @IsOptional()
  @IsEnum(ProductStatus)
  status?: ProductStatus;

  @ApiPropertyOptional({ enum: MetalType })
  @IsOptional()
  @IsEnum(MetalType)
  metalType?: MetalType;

  @ApiPropertyOptional({ enum: GoldPurity })
  @IsOptional()
  @IsEnum(GoldPurity)
  goldPurity?: GoldPurity;

  @ApiPropertyOptional()
  @IsOptional()
  @Type(() => Number)
  minPrice?: number;

  @ApiPropertyOptional()
  @IsOptional()
  @Type(() => Number)
  maxPrice?: number;

  @ApiPropertyOptional()
  @IsOptional()
  @IsBoolean()
  @Type(() => Boolean)
  isFeatured?: boolean;

  @ApiPropertyOptional({ default: 'createdAt' })
  @IsOptional()
  @IsString()
  sortBy?: string = 'createdAt';

  @ApiPropertyOptional({ default: 'desc' })
  @IsOptional()
  @IsString()
  sortOrder?: 'asc' | 'desc' = 'desc';
}
