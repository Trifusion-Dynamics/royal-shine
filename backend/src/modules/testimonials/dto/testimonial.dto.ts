import { IsString, IsOptional, IsBoolean, IsInt, Min, Max } from 'class-validator';
import { ApiProperty, ApiPropertyOptional, PartialType } from '@nestjs/swagger';

export class CreateTestimonialDto {
  @ApiProperty({ example: 'Ananya Verma' })
  @IsString()
  customerName: string;

  @ApiPropertyOptional({ example: 'Delhi, India' })
  @IsOptional()
  @IsString()
  customerLocation?: string;

  @ApiProperty({ example: 'The craftsmanship on my bridal necklace was breathtaking!' })
  @IsString()
  testimonial: string;

  @ApiPropertyOptional({ example: 'https://res.cloudinary.com/.../customer1.jpg' })
  @IsOptional()
  @IsString()
  image?: string;

  @ApiPropertyOptional({ example: 5, default: 5 })
  @IsOptional()
  @IsInt()
  @Min(1)
  @Max(5)
  rating?: number;

  @ApiPropertyOptional({ default: 0 })
  @IsOptional()
  @IsInt()
  @Min(0)
  sortOrder?: number;

  @ApiPropertyOptional({ default: true })
  @IsOptional()
  @IsBoolean()
  isApproved?: boolean;
}

export class UpdateTestimonialDto extends PartialType(CreateTestimonialDto) {}
