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
import {
  ApiTags,
  ApiOperation,
  ApiBearerAuth,
  ApiParam,
} from '@nestjs/swagger';
import { ProductsService } from './products.service';
import { CreateProductDto, UpdateProductDto, ProductQueryDto } from './dto/product.dto';
import { Public } from '../../common/decorators/public.decorator';
import { Roles } from '../../common/decorators/roles.decorator';
import { UserRole } from '@prisma/client';

@ApiTags('Products')
@Controller('products')
export class ProductsController {
  constructor(private readonly productsService: ProductsService) {}

  @Public()
  @Get()
  @ApiOperation({ summary: 'List all products (public, with filters & pagination)' })
  findAll(@Query() query: ProductQueryDto) {
    return this.productsService.findAll(query);
  }

  @Public()
  @Get('featured')
  @ApiOperation({ summary: 'Get featured products (public)' })
  getFeatured() {
    return this.productsService.getFeatured();
  }

  @Public()
  @Get('new-arrivals')
  @ApiOperation({ summary: 'Get new arrivals products (public)' })
  getNewArrivals() {
    return this.productsService.getNewArrivals();
  }

  @Public()
  @Get('id/:id')
  @ApiOperation({ summary: 'Get product by ID' })
  findById(@Param('id', ParseIntPipe) id: number) {
    return this.productsService.findById(id);
  }

  @Public()
  @Get(':slug')
  @ApiOperation({ summary: 'Get single product by slug (public)' })
  @ApiParam({ name: 'slug', type: String })
  findOne(@Param('slug') slug: string) {
    return this.productsService.findOne(slug);
  }

  @Post()
  @ApiBearerAuth('JWT-auth')
  @Roles(UserRole.super_admin, UserRole.admin, UserRole.content_manager)
  @ApiOperation({ summary: 'Create product (Admin)' })
  create(@Body() createProductDto: CreateProductDto) {
    return this.productsService.create(createProductDto);
  }

  @Put(':id')
  @ApiBearerAuth('JWT-auth')
  @Roles(UserRole.super_admin, UserRole.admin, UserRole.content_manager)
  @ApiOperation({ summary: 'Update product (Admin)' })
  update(@Param('id', ParseIntPipe) id: number, @Body() dto: UpdateProductDto) {
    return this.productsService.update(id, dto);
  }

  @Delete(':id')
  @ApiBearerAuth('JWT-auth')
  @Roles(UserRole.super_admin, UserRole.admin)
  @ApiOperation({ summary: 'Delete product (Admin)' })
  remove(@Param('id', ParseIntPipe) id: number) {
    return this.productsService.remove(id);
  }
}
