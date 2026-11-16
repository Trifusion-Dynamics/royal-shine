import { IsString, IsOptional, IsBoolean, IsEnum, IsInt, Min } from 'class-validator';
import { ApiProperty, ApiPropertyOptional, PartialType } from '@nestjs/swagger';
import { ContentStatus } from '@prisma/client';

export class CreateCategoryDto {
  @ApiProperty({ example: 'Necklaces' })
  @IsString()
  name: string;

  @ApiPropertyOptional({ example: 'necklaces' })
  @IsOptional()
  @IsString()
  slug?: string;

  @ApiPropertyOptional({ example: 'Exquisite handcrafted gold and diamond necklaces' })
  @IsOptional()
  @IsString()
  description?: string;

  @ApiPropertyOptional({ example: 'https://res.cloudinary.com/.../necklace.jpg' })
  @IsOptional()
  @IsString()
  image?: string;

  @ApiPropertyOptional({ default: false })
  @IsOptional()
  @IsBoolean()
  isFeatured?: boolean;

  @ApiPropertyOptional({ enum: ContentStatus, default: ContentStatus.active })
  @IsOptional()
  @IsEnum(ContentStatus)
  status?: ContentStatus;

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
  @IsInt()
  @Min(0)
  sortOrder?: number;
}

export class UpdateCategoryDto extends PartialType(CreateCategoryDto) {}

export class ReorderCategoriesDto {
  @ApiProperty({ type: [Number], example: [3, 1, 2] })
  ids: number[];
}
