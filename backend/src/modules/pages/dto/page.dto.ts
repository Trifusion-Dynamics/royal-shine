import { IsString, IsOptional } from 'class-validator';
import { ApiProperty, ApiPropertyOptional, PartialType } from '@nestjs/swagger';

export class CreatePageDto {
  @ApiProperty({ example: 'About Us' })
  @IsString()
  title: string;

  @ApiPropertyOptional({ example: 'about-us' })
  @IsOptional()
  @IsString()
  slug?: string;

  @ApiPropertyOptional({ example: '<p>Royal Shine is a legacy brand...</p>' })
  @IsOptional()
  @IsString()
  content?: string;

  @ApiPropertyOptional({ default: 'active' })
  @IsOptional()
  @IsString()
  status?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  seoTitle?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  seoDescription?: string;
}

export class UpdatePageDto extends PartialType(CreatePageDto) {}
