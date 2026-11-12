<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Enums\GoldPurity;
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
                        Forms\Components\TextInput::make('metal_type')
                            ->maxLength(100),
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
                            ->options(array_column(GoldPurity::cases(), 'value', 'value'))
                            ->visible(fn(callable $get) => $get('metal_type') === 'gold'),
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
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sku')
                    ->searchable(),
                Tables\Columns\TextColumn::make('metal_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gold_purity')
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
            ])
            ->actions([
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

