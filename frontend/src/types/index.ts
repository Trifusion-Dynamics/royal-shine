// ============================================================
// ROYAL SHINE - TypeScript Types (matches Prisma/NestJS schema)
// ============================================================

// ─── Enums ────────────────────────────────────────────────

export type UserRole = 'super_admin' | 'admin' | 'content_manager' | 'enquiry_manager';
export type CategoryStatus = 'active' | 'inactive';
export type CollectionStatus = 'active' | 'inactive';
export type ProductStatus = 'draft' | 'published' | 'inactive';
export type MetalType = 'gold' | 'silver' | 'artificial' | 'other';
export type GoldPurity = '9K' | '14K' | '18K' | '22K';
export type BannerStatus = 'active' | 'inactive';
export type EnquiryType = 'product' | 'contact';
export type EnquiryStatus = 'new' | 'contacted' | 'in_progress' | 'converted' | 'closed';
export type GalleryStatus = 'active' | 'inactive';
export type PageStatus = 'published' | 'draft';

// ─── Core Models ──────────────────────────────────────────

export interface User {
  id: number;
  name: string;
  email: string;
  role: UserRole;
  is_admin: boolean;
  email_verified_at: string | null;
  created_at: string;
  updated_at: string;
}

export interface Category {
  id: number;
  name: string;
  slug: string;
  description: string | null;
  image: string | null;
  is_featured: boolean;
  status: CategoryStatus;
  seo_title: string | null;
  seo_description: string | null;
  sort_order: number;
  products_count?: number;
  created_at: string;
  updated_at: string;
}

export interface Collection {
  id: number;
  name: string;
  slug: string;
  description: string | null;
  image: string | null;
  is_featured: boolean;
  status: CollectionStatus;
  seo_title: string | null;
  seo_description: string | null;
  sort_order: number;
  products_count?: number;
  created_at: string;
  updated_at: string;
}

export interface ProductImage {
  id: number;
  product_id: number;
  image: string;
  alt_text: string | null;
  sort_order: number;
  is_primary: boolean;
  created_at: string;
  updated_at: string;
}

export interface Product {
  id: number;
  category_id: number;
  collection_id: number | null;
  name: string;
  slug: string;
  sku: string;
  short_description: string | null;
  description: string | null;
  metal_type: MetalType | null;
  gold_purity: GoldPurity | null;
  weight: number | null;
  weight_unit: string | null;
  stone_details: string | null;
  price: number | null;
  price_on_request: boolean;
  is_featured: boolean;
  status: ProductStatus;
  seo_title: string | null;
  seo_description: string | null;
  sort_order: number;
  category?: Category;
  collection?: Collection | null;
  images?: ProductImage[];
  primary_image?: ProductImage | null;
  created_at: string;
  updated_at: string;
}

export interface Banner {
  id: number;
  image: string;
  heading: string | null;
  subheading: string | null;
  cta_label: string | null;
  cta_url: string | null;
  status: BannerStatus;
  sort_order: number;
  mobile_image: string | null;
  alt_text: string | null;
  starts_at: string | null;
  ends_at: string | null;
  created_at: string;
  updated_at: string;
}

export interface Enquiry {
  id: number;
  product_id: number | null;
  assigned_admin_id: number | null;
  name: string;
  mobile: string;
  email: string | null;
  subject: string;
  enquiry_type: EnquiryType;
  product_reference: string | null;
  message: string;
  status: EnquiryStatus;
  internal_notes: string | null;
  follow_up_date: string | null;
  product?: Product | null;
  assigned_admin?: User | null;
  created_at: string;
  updated_at: string;
}

export interface Gallery {
  id: number;
  image: string;
  title: string | null;
  description: string | null;
  alt_text: string | null;
  status: GalleryStatus;
  sort_order: number;
  created_at: string;
  updated_at: string;
}

export interface Page {
  id: number;
  slug: string;
  title: string;
  content: string;
  status: PageStatus;
  seo_title: string | null;
  seo_description: string | null;
  created_at: string;
  updated_at: string;
}

export interface Setting {
  id: number;
  key: string;
  value: string | null;
  group: string;
  created_at: string;
  updated_at: string;
}

export interface SettingsMap {
  site_name?: string;
  site_logo?: string;
  site_favicon?: string;
  phone?: string;
  whatsapp_number?: string;
  email?: string;
  address?: string;
  facebook_url?: string;
  instagram_url?: string;
  twitter_url?: string;
  youtube_url?: string;
  pinterest_url?: string;
  announcement_bar_text?: string;
  announcement_bar_enabled?: string;
  email_notification_enabled?: string;
  notification_email?: string;
  business_hours?: string;
  google_maps_url?: string;
  meta_description?: string;
  google_analytics_id?: string;
  [key: string]: string | undefined;
}

export interface Testimonial {
  id: number;
  customer_name: string;
  customer_location: string | null;
  testimonial: string;
  image: string | null;
  rating: number;
  sort_order: number;
  is_approved: boolean;
  created_at: string;
  updated_at: string;
}

export interface AdminActivityLog {
  id: number;
  user_id: number | null;
  action: string;
  module: string;
  subject_type: string;
  subject_id: string;
  changed_fields: Record<string, unknown> | null;
  user?: User | null;
  created_at: string;
}

// ─── API Response Types ────────────────────────────────────

export interface ApiResponse<T> {
  data: T;
  message?: string;
  success?: boolean;
}

export interface PaginatedResponse<T> {
  data: T[];
  meta: {
    total: number;
    page: number;
    limit: number;
    totalPages: number;
    lastPage?: number;
  };
}

export interface AuthResponse {
  access_token: string;
  user: User;
}

// ─── Query/Filter Types ────────────────────────────────────

export interface ProductFilters {
  page?: number;
  limit?: number;
  search?: string;
  category_id?: number;
  collection_id?: number;
  metal_type?: MetalType;
  gold_purity?: GoldPurity;
  min_price?: number;
  max_price?: number;
  status?: ProductStatus;
  is_featured?: boolean;
  sort?: string;
}

export interface CategoryFilters {
  page?: number;
  limit?: number;
  search?: string;
  status?: CategoryStatus;
  is_featured?: boolean;
}

export interface EnquiryFilters {
  page?: number;
  limit?: number;
  search?: string;
  status?: EnquiryStatus;
  enquiry_type?: EnquiryType;
  date_from?: string;
  date_to?: string;
  assigned_admin_id?: number;
}

export interface GalleryFilters {
  page?: number;
  limit?: number;
  status?: GalleryStatus;
}

// ─── Form Types ────────────────────────────────────────────

export interface LoginForm {
  email: string;
  password: string;
}

export interface EnquiryFormData {
  name: string;
  mobile: string;
  email?: string;
  subject: string;
  message: string;
  product_id?: number;
  product_reference?: string;
  enquiry_type: EnquiryType;
}

export interface ContactFormData {
  name: string;
  mobile: string;
  email?: string;
  message: string;
}

export interface ProductFormData {
  name: string;
  category_id: number;
  collection_id?: number | null;
  sku: string;
  short_description?: string;
  description?: string;
  metal_type?: MetalType;
  gold_purity?: GoldPurity;
  weight?: number;
  weight_unit?: string;
  stone_details?: string;
  price?: number;
  price_on_request?: boolean;
  is_featured?: boolean;
  status: ProductStatus;
  seo_title?: string;
  seo_description?: string;
  sort_order?: number;
}

// ─── Dashboard Types ───────────────────────────────────────

export interface DashboardStats {
  total_products: number;
  total_categories: number;
  total_collections: number;
  total_enquiries: number;
  new_enquiries: number;
  total_gallery: number;
  total_testimonials: number;
  total_users: number;
}

export interface EnquiryStatusChart {
  status: string;
  count: number;
}

export interface ProductsByCategoryChart {
  category: string;
  count: number;
}

export interface MonthlyEnquiriesChart {
  month: string;
  count: number;
}

export interface DashboardData {
  stats: DashboardStats;
  enquiry_by_status: EnquiryStatusChart[];
  products_by_category: ProductsByCategoryChart[];
  monthly_enquiries: MonthlyEnquiriesChart[];
  recent_enquiries: Enquiry[];
  recent_products: Product[];
}

// ─── Customer Types ────────────────────────────────────────

export interface CustomerGroup {
  mobile: string;
  name: string;
  email: string | null;
  enquiry_count: number;
  last_enquiry_at: string;
  enquiries: Enquiry[];
}

export interface UploadResponse {
  url: string;
  public_id?: string;
  filename?: string;
}
