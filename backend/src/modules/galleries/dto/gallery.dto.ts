import { IsString, IsOptional, IsEnum, IsInt, Min } from 'class-validator';
import { ApiProperty, ApiPropertyOptional, PartialType } from '@nestjs/swagger';
import { ContentStatus } from '@prisma/client';

export class CreateGalleryDto {
  @ApiProperty({ example: 'https://res.cloudinary.com/.../ring.jpg' })
  @IsString()
  image: string;

  @ApiPropertyOptional({ example: 'Gold Bridal Jhumka' })
  @IsOptional()
  @IsString()
  title?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  description?: string;

  @ApiPropertyOptional()
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
}

export class UpdateGalleryDto extends PartialType(CreateGalleryDto) {}
