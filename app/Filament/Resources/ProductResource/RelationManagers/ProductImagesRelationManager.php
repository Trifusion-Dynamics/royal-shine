<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\ProductImage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ProductImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $recordTitleAttribute = 'image';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->required()
                    ->maxSize(2048)
                    ->directory('products'),
                Forms\Components\TextInput::make('alt_text')
                    ->maxLength(255),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_primary'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('alt_text'),
                Tables\Columns\IconColumn::make('is_primary')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
