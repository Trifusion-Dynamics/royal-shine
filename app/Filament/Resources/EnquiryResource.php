<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EnquiryResource\Pages;
use App\Filament\Resources\EnquiryResource\RelationManagers;
use App\Models\Enquiry;
use App\Enums\EnquiryStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class EnquiryResource extends AuthorizedResource
{
    protected static ?string $model = Enquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    protected static ?string $navigationGroup = 'Customers & Enquiries';

    protected static string $permissionModule = 'enquiries';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'name'),
                Forms\Components\Select::make('assigned_admin_id')
                    ->label('Assigned admin')
                    ->options(fn (): array => \App\Models\User::where('is_admin', true)->orderBy('name')->pluck('name', 'id')->all())
                    ->searchable(),
                Forms\Components\Select::make('enquiry_type')
                    ->options([
                        'product' => 'Product enquiry',
                        'contact' => 'Contact message',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('subject')
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('mobile')
                    ->required()
                    ->tel()
                    ->maxLength(20),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('product_reference')
                    ->maxLength(255),
                Forms\Components\Textarea::make('message')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->options(array_column(EnquiryStatus::cases(), 'value', 'value'))
                    ->required(),
                Forms\Components\Textarea::make('internal_notes')
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('follow_up_date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('enquiry_type')
                    ->label('Type')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('assignedAdmin.name')
                    ->label('Assigned to')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mobile')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options(array_column(EnquiryStatus::cases(), 'value', 'value'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('follow_up_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(array_column(EnquiryStatus::cases(), 'value', 'value')),
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-m-phone')
                    ->url(fn (Enquiry $record): string => 'https://wa.me/' . $record->mobile . '?text=' . urlencode('Hello ' . $record->name . ', thank you for your enquiry.'))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('call')
                    ->label('Call')
                    ->icon('heroicon-m-phone')
                    ->url(fn (Enquiry $record): string => 'tel:' . $record->mobile)
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEnquiries::route('/'),
            'create' => Pages\CreateEnquiry::route('/create'),
            'edit' => Pages\EditEnquiry::route('/{record}/edit'),
        ];
    }
}
