import {
  Controller,
  Get,
  Post,
  Put,
  Delete,
  Body,
  Param,
  ParseIntPipe,
} from '@nestjs/common';
import { ApiTags, ApiOperation, ApiBearerAuth } from '@nestjs/swagger';
import { PagesService } from './pages.service';
import { CreatePageDto, UpdatePageDto } from './dto/page.dto';
import { Public } from '../../common/decorators/public.decorator';
import { Roles } from '../../common/decorators/roles.decorator';
import { UserRole } from '@prisma/client';

@ApiTags('Pages')
@Controller('pages')
export class PagesController {
  constructor(private readonly pagesService: PagesService) {}

  @Public()
  @Get()
  @ApiOperation({ summary: 'Get all static pages (Public / Admin)' })
  findAll() {
    return this.pagesService.findAll();
  }

  @Public()
  @Get('slug/:slug')
  @ApiOperation({ summary: 'Get static page by slug (e.g. about-us, privacy-policy)' })
  findBySlug(@Param('slug') slug: string) {
    return this.pagesService.findBySlug(slug);
  }

  @Public()
  @Get(':id')
  @ApiOperation({ summary: 'Get static page by ID' })
  findOne(@Param('id', ParseIntPipe) id: number) {
    return this.pagesService.findOne(id);
  }

  @Post()
  @ApiBearerAuth('JWT-auth')
  @Roles(UserRole.super_admin, UserRole.admin, UserRole.content_manager)
  @ApiOperation({ summary: 'Create page (Admin)' })
  create(@Body() dto: CreatePageDto) {
    return this.pagesService.create(dto);
  }

  @Put(':id')
  @ApiBearerAuth('JWT-auth')
  @Roles(UserRole.super_admin, UserRole.admin, UserRole.content_manager)
  @ApiOperation({ summary: 'Update page (Admin)' })
  update(@Param('id', ParseIntPipe) id: number, @Body() dto: UpdatePageDto) {
    return this.pagesService.update(id, dto);
  }

  @Delete(':id')
  @ApiBearerAuth('JWT-auth')
  @Roles(UserRole.super_admin, UserRole.admin, UserRole.content_manager)
  @ApiOperation({ summary: 'Delete page (Admin)' })
  remove(@Param('id', ParseIntPipe) id: number) {
    return this.pagesService.remove(id);
  }
}
