import { IsString, IsOptional, IsEnum, IsInt, Min, IsDateString } from 'class-validator';
import { ApiProperty, ApiPropertyOptional, PartialType } from '@nestjs/swagger';
import { ContentStatus } from '@prisma/client';

export class CreateBannerDto {
  @ApiProperty({ example: 'https://res.cloudinary.com/.../banner1.jpg' })
  @IsString()
  image: string;

  @ApiPropertyOptional({ example: 'https://res.cloudinary.com/.../banner1_mobile.jpg' })
  @IsOptional()
  @IsString()
  mobileImage?: string;

  @ApiPropertyOptional({ example: 'Royal Heritage Collection' })
  @IsOptional()
  @IsString()
  heading?: string;

  @ApiPropertyOptional({ example: 'Handcrafted luxury for life’s grandest celebrations' })
  @IsOptional()
  @IsString()
  subheading?: string;

  @ApiPropertyOptional({ example: 'Explore Catalogue' })
  @IsOptional()
  @IsString()
  ctaLabel?: string;

  @ApiPropertyOptional({ example: '/jewellery' })
  @IsOptional()
  @IsString()
  ctaUrl?: string;

  @ApiPropertyOptional({ example: 'Festive Banner' })
  @IsOptional()
  @IsString()
  altText?: string;

  @ApiPropertyOptional({ enum: ContentStatus, default: ContentStatus.active })
  @IsOptional()
  @IsEnum(ContentStatus)
  status?: ContentStatus;

  @ApiPropertyOptional({ default: 0 })
  @IsOptional()
  @IsInt()
  @Min(0)
  sortOrder?: number;

  @ApiPropertyOptional()
  @IsOptional()
  @IsDateString()
  startsAt?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsDateString()
  endsAt?: string;
}

export class UpdateBannerDto extends PartialType(CreateBannerDto) {}
