import { IsString, IsOptional } from 'class-validator';
import { ApiProperty } from '@nestjs/swagger';

export class UpdateSettingsDto {
  @ApiProperty({ description: 'Key-value map of settings to update', example: { site_name: 'Royal Shine', phone: '+91 98765 43210' } })
  settings: Record<string, any>;
}
