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
import { GalleriesService } from './galleries.service';
import { CreateGalleryDto, UpdateGalleryDto } from './dto/gallery.dto';
import { Public } from '../../common/decorators/public.decorator';
import { Roles } from '../../common/decorators/roles.decorator';
import { UserRole } from '@prisma/client';

@ApiTags('Galleries')
@Controller('galleries')
export class GalleriesController {
  constructor(private readonly galleriesService: GalleriesService) {}

  @Public()
  @Get()
  @ApiOperation({ summary: 'Get all gallery items (Public / Admin)' })
  @ApiQuery({ name: 'all', required: false, type: Boolean })
  findAll(@Query('all') all?: string) {
    return this.galleriesService.findAll(all === 'true');
  }

  @Public()
  @Get(':id')
  @ApiOperation({ summary: 'Get gallery item by ID' })
  findOne(@Param('id', ParseIntPipe) id: number) {
    return this.galleriesService.findOne(id);
  }

  @Post()
  @ApiBearerAuth('JWT-auth')
  @Roles(UserRole.super_admin, UserRole.admin, UserRole.content_manager)
  @ApiOperation({ summary: 'Create gallery item (Admin)' })
  create(@Body() dto: CreateGalleryDto) {
    return this.galleriesService.create(dto);
  }

  @Put(':id')
  @ApiBearerAuth('JWT-auth')
  @Roles(UserRole.super_admin, UserRole.admin, UserRole.content_manager)
  @ApiOperation({ summary: 'Update gallery item (Admin)' })
  update(@Param('id', ParseIntPipe) id: number, @Body() dto: UpdateGalleryDto) {
    return this.galleriesService.update(id, dto);
  }

  @Delete(':id')
  @ApiBearerAuth('JWT-auth')
  @Roles(UserRole.super_admin, UserRole.admin, UserRole.content_manager)
  @ApiOperation({ summary: 'Delete gallery item (Admin)' })
  remove(@Param('id', ParseIntPipe) id: number) {
    return this.galleriesService.remove(id);
  }
}
