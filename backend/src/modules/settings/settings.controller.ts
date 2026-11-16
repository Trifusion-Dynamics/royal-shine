import { Controller, Get, Put, Body } from '@nestjs/common';
import { ApiTags, ApiOperation, ApiBearerAuth } from '@nestjs/swagger';
import { SettingsService } from './settings.service';
import { UpdateSettingsDto } from './dto/setting.dto';
import { Public } from '../../common/decorators/public.decorator';
import { Roles } from '../../common/decorators/roles.decorator';
import { UserRole } from '@prisma/client';

@ApiTags('Settings')
@Controller('settings')
export class SettingsController {
  constructor(private readonly settingsService: SettingsService) {}

  @Public()
  @Get()
  @ApiOperation({ summary: 'Get all site settings as a key-value dictionary (Public)' })
  getAll() {
    return this.settingsService.getAllAsMap();
  }

  @Put()
  @ApiBearerAuth('JWT-auth')
  @Roles(UserRole.super_admin, UserRole.admin)
  @ApiOperation({ summary: 'Update site settings (Admin)' })
  update(@Body() dto: UpdateSettingsDto) {
    return this.settingsService.updateMany(dto.settings || {});
  }
}
