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
import { CollectionsService } from './collections.service';
import { CreateCollectionDto, UpdateCollectionDto } from './dto/collection.dto';
import { Public } from '../../common/decorators/public.decorator';
import { Roles } from '../../common/decorators/roles.decorator';
import { UserRole } from '@prisma/client';

@ApiTags('Collections')
@Controller('collections')
export class CollectionsController {
  constructor(private readonly collectionsService: CollectionsService) {}

  @Public()
  @Get()
  @ApiOperation({ summary: 'Get all collections (public)' })
  @ApiQuery({ name: 'all', required: false, type: Boolean })
  findAll(@Query('all') all?: string) {
    return this.collectionsService.findAll(all === 'true');
  }

  @Public()
  @Get('slug/:slug')
  @ApiOperation({ summary: 'Get collection by slug with products' })
  findBySlug(@Param('slug') slug: string) {
    return this.collectionsService.findBySlug(slug);
  }

  @Public()
  @Get(':id')
  @ApiOperation({ summary: 'Get collection by ID' })
  findOne(@Param('id', ParseIntPipe) id: number) {
    return this.collectionsService.findOne(id);
  }

  @Post()
  @ApiBearerAuth('JWT-auth')
  @Roles(UserRole.super_admin, UserRole.admin, UserRole.content_manager)
  @ApiOperation({ summary: 'Create collection (Admin)' })
  create(@Body() dto: CreateCollectionDto) {
    return this.collectionsService.create(dto);
  }

  @Put(':id')
  @ApiBearerAuth('JWT-auth')
  @Roles(UserRole.super_admin, UserRole.admin, UserRole.content_manager)
  @ApiOperation({ summary: 'Update collection (Admin)' })
  update(@Param('id', ParseIntPipe) id: number, @Body() dto: UpdateCollectionDto) {
    return this.collectionsService.update(id, dto);
  }

  @Delete(':id')
  @ApiBearerAuth('JWT-auth')
  @Roles(UserRole.super_admin, UserRole.admin, UserRole.content_manager)
  @ApiOperation({ summary: 'Delete collection (Admin)' })
  remove(@Param('id', ParseIntPipe) id: number) {
    return this.collectionsService.remove(id);
  }
}
