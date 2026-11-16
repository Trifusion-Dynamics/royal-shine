import { Injectable, Logger } from '@nestjs/common';
import { ConfigService } from '@nestjs/config';
import * as fs from 'fs';
import * as path from 'path';
import { v2 as cloudinary } from 'cloudinary';

@Injectable()
export class UploadService {
  private readonly logger = new Logger(UploadService.name);
  private readonly localUploadDir: string;
  private imageKit: any = null;

  constructor(private configService: ConfigService) {
    this.localUploadDir = path.resolve(process.cwd(), 'uploads');
    if (!fs.existsSync(this.localUploadDir)) {
      fs.mkdirSync(this.localUploadDir, { recursive: true });
    }

    // Configure Cloudinary if credentials exist
    const cloudName = this.configService.get<string>('CLOUDINARY_CLOUD_NAME');
    const apiKey = this.configService.get<string>('CLOUDINARY_API_KEY');
    const apiSecret = this.configService.get<string>('CLOUDINARY_API_SECRET');

    if (cloudName && apiKey && apiSecret) {
      cloudinary.config({
        cloud_name: cloudName,
        api_key: apiKey,
        api_secret: apiSecret,
        secure: true,
      });
    }

    // Configure ImageKit if credentials exist
    const ikPublicKey = this.configService.get<string>('IMAGEKIT_PUBLIC_KEY');
    const ikPrivateKey = this.configService.get<string>('IMAGEKIT_PRIVATE_KEY');
    const ikUrlEndpoint = this.configService.get<string>('IMAGEKIT_URL_ENDPOINT');

    if (ikPublicKey && ikPrivateKey && ikUrlEndpoint) {
      try {
        const ImageKit = require('imagekit');
        this.imageKit = new ImageKit({
          publicKey: ikPublicKey,
          privateKey: ikPrivateKey,
          urlEndpoint: ikUrlEndpoint,
        });
        this.logger.log('ImageKit initialized successfully');
      } catch (e) {
        this.logger.warn('ImageKit package not loaded, will use fallback');
      }
    }
  }

  async uploadFile(file: Express.Multer.File, folder = 'royal-shine'): Promise<{ url: string; publicId?: string }> {
    // 1. Try ImageKit if initialized
    if (this.imageKit) {
      try {
        const res = await this.imageKit.upload({
          file: file.buffer,
          fileName: `${Date.now()}-${file.originalname}`,
          folder: `/${folder}`,
        });
        return { url: res.url, publicId: res.fileId };
      } catch (err: any) {
        this.logger.warn(`ImageKit upload failed: ${err.message}. Falling back...`);
      }
    }

    // 2. Try Cloudinary if configured
    const cloudName = this.configService.get<string>('CLOUDINARY_CLOUD_NAME');
    if (cloudName) {
      try {
        const result = await new Promise<any>((resolve, reject) => {
          const uploadStream = cloudinary.uploader.upload_stream(
            { folder: `royal-shine/${folder}`, resource_type: 'auto' },
            (error, res) => {
              if (error) reject(error);
              else resolve(res);
            },
          );
          uploadStream.end(file.buffer);
        });
        return { url: result.secure_url, publicId: result.public_id };
      } catch (err: any) {
        this.logger.warn(`Cloudinary upload failed: ${err.message}. Falling back to local storage...`);
      }
    }

    // 3. Robust Local Storage Fallback
    const targetFolder = path.join(this.localUploadDir, folder);
    if (!fs.existsSync(targetFolder)) {
      fs.mkdirSync(targetFolder, { recursive: true });
    }

    const ext = path.extname(file.originalname) || '.jpg';
    const filename = `${Date.now()}-${Math.random().toString(36).substring(2, 9)}${ext}`;
    const filePath = path.join(targetFolder, filename);

    fs.writeFileSync(filePath, file.buffer);

    const port = this.configService.get<string>('PORT') || '4000';
    const baseUrl = this.configService.get<string>('BACKEND_URL') || `http://localhost:${port}`;
    const fileUrl = `${baseUrl}/uploads/${folder}/${filename}`;

    return {
      url: fileUrl,
      publicId: `local:${folder}/${filename}`,
    };
  }

  async deleteFile(publicId: string): Promise<boolean> {
    if (!publicId) return true;

    if (publicId.startsWith('local:')) {
      const relativePath = publicId.replace('local:', '');
      const filePath = path.join(this.localUploadDir, relativePath);
      if (fs.existsSync(filePath)) {
        fs.unlinkSync(filePath);
      }
      return true;
    }

    if (this.imageKit && !publicId.includes('/')) {
      try {
        await this.imageKit.deleteFile(publicId);
        return true;
      } catch (e) {
        this.logger.warn(`Failed to delete ImageKit file: ${publicId}`);
      }
    }

    try {
      await cloudinary.uploader.destroy(publicId);
      return true;
    } catch (e) {
      this.logger.warn(`Failed to delete Cloudinary file: ${publicId}`);
      return false;
    }
  }
}
