<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Enquiry;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CustomerResource extends AuthorizedResource
{
    protected static ?string $model = Enquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Customers & Enquiries';

    protected static ?string $navigationLabel = 'Customers';

    protected static string $permissionModule = 'customers';

    protected static bool $readOnly = true;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function getEloquentQuery(): Builder
    {
        return Enquiry::query()
            ->selectRaw('MIN(id) as id, mobile, MAX(name) as name, MAX(email) as email, COUNT(*) as enquiries_count, MAX(created_at) as latest_enquiry_at')
            ->groupBy('mobile');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('latest_enquiry_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mobile')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('enquiries_count')
                    ->label('Enquiries')
                    ->sortable(),
                Tables\Columns\TextColumn::make('latest_enquiry_at')
                    ->label('Latest Enquiry')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('history')
                    ->label('Enquiry history')
                    ->icon('heroicon-o-clock')
                    ->modalContent(fn (Enquiry $record) => view('filament.customer-enquiry-history', [
                        'enquiries' => Enquiry::with('product')
                            ->where('mobile', $record->mobile)
                            ->latest()
                            ->get(),
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
        ];
    }
}
