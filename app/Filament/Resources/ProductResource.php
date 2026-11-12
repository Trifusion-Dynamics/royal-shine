<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required(),
                        Forms\Components\Select::make('collection_id')
                            ->relationship('collection', 'name'),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->afterStateUpdated(fn(string $state, $set) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Textarea::make('short_description')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('metal_type')
                            ->label('Metal Type')
                            ->options([
                                'gold' => 'Gold',
                                'silver' => 'Silver',
                                'artificial' => 'Artificial',
                                'other' => 'Other',
                            ])
                            ->native(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Pricing')
                    ->schema([
                        Forms\Components\Toggle::make('price_on_request')
                            ->label('Price on Request'),
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->prefix('\u20B9')
                            ->disabled(fn(callable $get) => (bool) $get('price_on_request')),
                        Forms\Components\Toggle::make('is_featured')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->required()
                            ->default('draft'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Specs')
                    ->schema([
                        Forms\Components\Select::make('gold_purity')
                            ->label('Gold Purity')
                            ->options([
                                '9K' => '9K (9 Karat)',
                                '14K' => '14K (14 Karat)',
                                '18K' => '18K (18 Karat)',
                            ])
                            ->visible(fn(callable $get) => $get('metal_type') === 'gold')
                            ->native(false),
                        Forms\Components\TextInput::make('weight')
                            ->numeric(),
                        Forms\Components\TextInput::make('weight_unit')
                            ->maxLength(50),
                        Forms\Components\Textarea::make('stone_details')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Images')
                    ->schema([
                        Forms\Components\FileUpload::make('images')
                            ->image()
                            ->maxSize(2048)
                            ->multiple()
                            ->directory('products'),
                    ])
                    ->columnSpanFull(),

                Forms\Components\Section::make('SEO')
                    ->schema([
                        Forms\Components\TextInput::make('seo_title')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('seo_description')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->with('images'))
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->getStateUsing(fn($record) => optional($record->images->firstWhere('is_primary', true) ?? $record->images->first())->image)
                    ->height(40)
                    ->width(40),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sku')
                    ->searchable(),
                Tables\Columns\TextColumn::make('metal_type')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('gold_purity')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('inr')
                    ->sortable(),
                Tables\Columns\IconColumn::make('price_on_request')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ])
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ]),
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
                Tables\Filters\SelectFilter::make('metal_type')
                    ->label('Metal Type')
                    ->options([
                        'gold' => 'Gold',
                        'silver' => 'Silver',
                        'artificial' => 'Artificial',
                        'other' => 'Other',
                    ]),
                Tables\Filters\SelectFilter::make('gold_purity')
                    ->label('Gold Purity')
                    ->options([
                        '9K' => '9K',
                        '14K' => '14K',
                        '18K' => '18K',
                    ]),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured'),
            ])
            ->actions([
                Tables\Actions\Action::make('toggleStatus')
                    ->label(fn(Product $record) => $record->status === 'published' ? 'Unpublish' : 'Publish')
                    ->icon(fn(Product $record) => $record->status === 'published' ? 'heroicon-o-eye-slash' : 'heroicon-o-check-circle')
                    ->color(fn(Product $record) => $record->status === 'published' ? 'warning' : 'success')
                    ->action(fn(Product $record) => $record->update([
                        'status' => $record->status === 'published' ? 'draft' : 'published',
                    ])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProductImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}

