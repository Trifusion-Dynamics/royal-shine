import {
  Controller,
  Get,
  Post,
  Put,
  Delete,
  Body,
  Param,
  Query,
  ParseIntPipe,
} from '@nestjs/common';
import { ApiTags, ApiOperation, ApiBearerAuth, ApiQuery } from '@nestjs/swagger';
import { EnquiriesService } from './enquiries.service';
import { CreateEnquiryDto, UpdateEnquiryAdminDto } from './dto/enquiry.dto';
import { Public } from '../../common/decorators/public.decorator';
import { Roles } from '../../common/decorators/roles.decorator';
import { UserRole, EnquiryStatus, EnquiryType } from '@prisma/client';

@ApiTags('Enquiries')
@Controller('enquiries')
export class EnquiriesController {
  constructor(private readonly enquiriesService: EnquiriesService) {}

  @Public()
  @Post()
  @ApiOperation({ summary: 'Submit product or general enquiry (Public)' })
  submit(@Body() dto: CreateEnquiryDto) {
    return this.enquiriesService.submitEnquiry(dto);
  }

  @Get()
  @ApiBearerAuth('JWT-auth')
  @Roles(UserRole.super_admin, UserRole.admin, UserRole.enquiry_manager)
  @ApiOperation({ summary: 'Get all enquiries with filters & pagination (Admin)' })
  @ApiQuery({ name: 'status', required: false, enum: EnquiryStatus })
  @ApiQuery({ name: 'type', required: false, enum: EnquiryType })
  @ApiQuery({ name: 'search', required: false })
  @ApiQuery({ name: 'page', required: false, type: Number })
  @ApiQuery({ name: 'limit', required: false, type: Number })
  findAll(
    @Query('status') status?: EnquiryStatus,
    @Query('type') type?: EnquiryType,
    @Query('search') search?: string,
    @Query('page') page?: string,
    @Query('limit') limit?: string,
  ) {
    return this.enquiriesService.findAll({
      status,
      type,
      search,
      page: page ? parseInt(page, 10) : 1,
      limit: limit ? parseInt(limit, 10) : 15,
    });
  }

  @Get('customers')
  @ApiBearerAuth('JWT-auth')
  @Roles(UserRole.super_admin, UserRole.admin, UserRole.enquiry_manager)
  @ApiOperation({ summary: 'Get aggregated customers list (Admin)' })
  @ApiQuery({ name: 'search', required: false })
  getCustomers(@Query('search') search?: string) {
    return this.enquiriesService.getCustomers(search);
  }

  @Get('customers/:mobile/history')
  @ApiBearerAuth('JWT-auth')
  @Roles(UserRole.super_admin, UserRole.admin, UserRole.enquiry_manager)
  @ApiOperation({ summary: 'Get customer enquiry history by mobile (Admin)' })
  getCustomerHistory(@Param('mobile') mobile: string) {
    return this.enquiriesService.getCustomerHistory(mobile);
  }

  @Get(':id')
  @ApiBearerAuth('JWT-auth')
  @Roles(UserRole.super_admin, UserRole.admin, UserRole.enquiry_manager)
  @ApiOperation({ summary: 'Get enquiry by ID (Admin)' })
  findOne(@Param('id', ParseIntPipe) id: number) {
    return this.enquiriesService.findOne(id);
  }

  @Put(':id')
  @ApiBearerAuth('JWT-auth')
  @Roles(UserRole.super_admin, UserRole.admin, UserRole.enquiry_manager)
  @ApiOperation({ summary: 'Update enquiry status, notes, assignment (Admin)' })
  update(@Param('id', ParseIntPipe) id: number, @Body() dto: UpdateEnquiryAdminDto) {
    return this.enquiriesService.update(id, dto);
  }

  @Delete(':id')
  @ApiBearerAuth('JWT-auth')
  @Roles(UserRole.super_admin, UserRole.admin)
  @ApiOperation({ summary: 'Delete enquiry (Admin)' })
  remove(@Param('id', ParseIntPipe) id: number) {
    return this.enquiriesService.remove(id);
  }
}
