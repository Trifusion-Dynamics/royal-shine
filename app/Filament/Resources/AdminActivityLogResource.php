<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminActivityLogResource\Pages;
use App\Models\AdminActivityLog;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AdminActivityLogResource extends AuthorizedResource
{
    protected static ?string $model = AdminActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Administration';

    protected static string $permissionModule = 'activity_logs';

    protected static bool $readOnly = true;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Admin')
                    ->default('Deleted admin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('action')
                    ->badge(),
                Tables\Columns\TextColumn::make('module')
                    ->badge(),
                Tables\Columns\TextColumn::make('subject_type')
                    ->formatStateUsing(fn (string $state): string => class_basename($state)),
                Tables\Columns\TextColumn::make('subject_id')
                    ->label('Record'),
                Tables\Columns\TextColumn::make('changed_fields')
                    ->formatStateUsing(fn (?array $state): string => implode(', ', $state ?? []))
                    ->wrap(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('module')
                    ->options([
                        'products' => 'Products',
                        'categories' => 'Categories',
                        'collections' => 'Collections',
                        'banners' => 'Banners',
                        'galleries' => 'Gallery',
                        'pages' => 'Pages',
                        'enquiries' => 'Enquiries',
                        'settings' => 'Settings',
                        'users' => 'Admin users',
                    ]),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdminActivityLogs::route('/'),
        ];
    }
}
