import { Controller, Get } from '@nestjs/common';
import { ApiTags, ApiOperation, ApiBearerAuth } from '@nestjs/swagger';
import { DashboardService } from './dashboard.service';
import { Roles } from '../../common/decorators/roles.decorator';
import { UserRole } from '@prisma/client';

@ApiTags('Dashboard')
@ApiBearerAuth('JWT-auth')
@Controller('dashboard')
export class DashboardController {
  constructor(private readonly dashboardService: DashboardService) {}

  @Get('stats')
  @Roles(UserRole.super_admin, UserRole.admin, UserRole.content_manager, UserRole.enquiry_manager)
  @ApiOperation({ summary: 'Get dashboard statistics, charts data and recent items (Admin)' })
  getStats() {
    return this.dashboardService.getStats();
  }
}
