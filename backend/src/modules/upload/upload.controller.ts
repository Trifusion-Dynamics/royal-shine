import {
  Controller,
  Post,
  Delete,
  UploadedFile,
  UseInterceptors,
  Body,
  BadRequestException,
} from '@nestjs/common';
import { FileInterceptor } from '@nestjs/platform-express';
import { ApiTags, ApiOperation, ApiBearerAuth, ApiConsumes, ApiBody } from '@nestjs/swagger';
import { UploadService } from './upload.service';
import { Roles } from '../../common/decorators/roles.decorator';
import { UserRole } from '@prisma/client';

@ApiTags('Upload')
@ApiBearerAuth('JWT-auth')
@Controller('upload')
export class UploadController {
  constructor(private readonly uploadService: UploadService) {}

  @Post()
  @Roles(UserRole.super_admin, UserRole.admin, UserRole.content_manager)
  @ApiOperation({ summary: 'Upload an image (ImageKit / Cloudinary / Local)' })
  @ApiConsumes('multipart/form-data')
  @ApiBody({
    schema: {
      type: 'object',
      properties: {
        file: { type: 'string', format: 'binary' },
        folder: { type: 'string', example: 'products' },
      },
    },
  })
  @UseInterceptors(
    FileInterceptor('file', {
      limits: { fileSize: 10 * 1024 * 1024 }, // 10MB
      fileFilter: (req, file, cb) => {
        if (!file.mimetype.match(/\/(jpg|jpeg|png|webp|avif|svg\+xml)$/)) {
          return cb(new BadRequestException('Only image files (JPG, PNG, WebP, AVIF, SVG) are allowed!'), false);
        }
        cb(null, true);
      },
    }),
  )
  async upload(@UploadedFile() file: Express.Multer.File, @Body('folder') folder?: string) {
    if (!file) {
      throw new BadRequestException('Please provide a file to upload');
    }
    return this.uploadService.uploadFile(file, folder || 'general');
  }

  @Delete()
  @Roles(UserRole.super_admin, UserRole.admin, UserRole.content_manager)
  @ApiOperation({ summary: 'Delete an uploaded image' })
  async delete(@Body('publicId') publicId: string) {
    if (!publicId) {
      throw new BadRequestException('Public ID is required');
    }
    const success = await this.uploadService.deleteFile(publicId);
    return { success };
  }
}
