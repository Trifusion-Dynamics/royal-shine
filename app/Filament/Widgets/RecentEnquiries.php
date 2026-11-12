<?php

namespace App\Filament\Widgets;

use App\Models\Enquiry;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentEnquiries extends TableWidget
{
    protected static ?string $heading = 'Recent Enquiries';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Enquiry::query()
                    ->orderBy('created_at', 'desc')
                    ->take(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mobile')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product_reference')
                    ->searchable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'in_progress' => 'In Progress',
                        'converted' => 'Converted',
                        'closed' => 'Closed',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-m-phone')
                    ->url(fn (Enquiry $record): string => 'https://wa.me/' . $record->mobile)
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('call')
                    ->label('Call')
                    ->icon('heroicon-m-phone')
                    ->url(fn (Enquiry $record): string => 'tel:' . $record->mobile)
                    ->openUrlInNewTab(),
            ]);
    }
}
