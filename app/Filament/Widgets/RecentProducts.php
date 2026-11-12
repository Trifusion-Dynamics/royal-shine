<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentProducts extends TableWidget
{
    protected static ?string $heading = 'Recently Added Products';

    public static function canView(): bool
    {
        return auth()->user()?->canAccessModule('products') ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query()->with('category')->latest()->limit(8))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sku'),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category'),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->url(fn (Product $record): string => route('filament.admin.resources.products.edit', $record))
                    ->icon('heroicon-o-pencil-square'),
            ]);
    }
}
