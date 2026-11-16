import {
  IsString,
  IsOptional,
  IsEmail,
  IsEnum,
  IsInt,
  Matches,
  IsDateString,
} from 'class-validator';
import { ApiProperty, ApiPropertyOptional } from '@nestjs/swagger';
import { EnquiryStatus, EnquiryType } from '@prisma/client';

export class CreateEnquiryDto {
  @ApiProperty({ example: 'Pooja Sharma' })
  @IsString()
  name: string;

  @ApiProperty({ example: '9876543210' })
  @IsString()
  @Matches(/^[6-9]\d{9}$/, {
    message: 'Please provide a valid 10-digit Indian mobile number',
  })
  mobile: string;

  @ApiPropertyOptional({ example: 'pooja@example.com' })
  @IsOptional()
  @IsEmail()
  email?: string;

  @ApiProperty({ example: 'I am interested in buying this gold necklace.' })
  @IsString()
  message: string;

  @ApiPropertyOptional({ example: 1 })
  @IsOptional()
  @IsInt()
  productId?: number;

  @ApiPropertyOptional({ example: 'RN-001' })
  @IsOptional()
  @IsString()
  productReference?: string;

  @ApiPropertyOptional({ example: 'Enquiry for 18K Gold Necklace' })
  @IsOptional()
  @IsString()
  subject?: string;

  @ApiPropertyOptional({ enum: EnquiryType, default: EnquiryType.contact })
  @IsOptional()
  @IsEnum(EnquiryType)
  enquiryType?: EnquiryType;

  // Honeypot field - must be empty
  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  website?: string;
}

export class UpdateEnquiryAdminDto {
  @ApiPropertyOptional({ enum: EnquiryStatus })
  @IsOptional()
  @IsEnum(EnquiryStatus)
  status?: EnquiryStatus;

  @ApiPropertyOptional({ example: 'Customer called and requested price estimate' })
  @IsOptional()
  @IsString()
  internalNotes?: string;

  @ApiPropertyOptional({ example: '2026-11-20' })
  @IsOptional()
  @IsDateString()
  followUpDate?: string;

  @ApiPropertyOptional({ example: 1 })
  @IsOptional()
  @IsInt()
  assignedAdminId?: number;
}
