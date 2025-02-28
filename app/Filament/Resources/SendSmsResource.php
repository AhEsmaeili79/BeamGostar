<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SendSmsResource\Pages;
use App\Filament\Resources\SendSmsResource\RelationManagers;
use App\Models\Customers;
use App\Models\sendsms;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SendSmsResource extends Resource
{
    protected static ?string $model = sendsms::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    public static function getNavigationGroup(): string
    {
        return __('filament.labels.base_info');
    }

    protected static ?string $navigationLabel = 'پیامک های ارسالی';

    protected static ?string $pluralLabel = 'پیامک های ارسالی';

    protected static ?string $singularLabel = 'پیامک های ارسالی';

    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('customers_id')
                    ->label('مشتری')
                    ->options(
                        Customers::whereNotNull('name_fa')  // Ensure 'name_fa' is not null
                            ->whereNotNull('family_fa')    // Ensure 'family_fa' is not null
                            ->get()
                            ->mapWithKeys(function ($customer) {
                                return [$customer->id => $customer->name_fa . ' ' . $customer->family_fa];  // Concatenate name_fa and family_fa
                            })
                    )
                    ->required()
                    ->searchable()
                    ->columnSpan([
                        'default' => 2,  // 1 column span on larger screens
                        'sm' => 1,       // 2 column span on small screens (screens smaller than 600px)
                    ]),

                Forms\Components\TextInput::make('number')
                    ->label('Phone Number')
                    ->required()
                    ->maxLength(11),
                Forms\Components\Textarea::make('text')
                    ->label('Message Text')
                    ->required()
                    ->maxLength(65535),
                Forms\Components\Select::make('state')
                    ->label('State')
                    ->required()
                    ->options([
                        0 => 'Sent',
                        1 => 'Failed',
                        2 => 'Pending',
                    ]),
                Forms\Components\Hidden::make('send_time')
                ->default(now()->format('Y/m/d-H:i')) // Set current date and time
                ->required(),
            ])
            ->extraAttributes([
                'class' => 'filament-form-wrapper', // Adding a wrapper class for custom styles
                'style' => 'border: 3px solid #ddd; 
                            padding: 20px; 
                            border-radius: 12px; 
                            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); 
                            transition: all 0.3s ease;',
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID'),
                Tables\Columns\TextColumn::make('customer.name_fa') // Access the 'name_fa' through the 'customer' relationship
                ->label('Customer Name')
                ->formatStateUsing(function ($state, $record) {
                    return $record->customer->name_fa . ' ' . $record->customer->family_fa; // Concatenate 'name_fa' and 'family_fa'
                }),
                Tables\Columns\TextColumn::make('number')
                    ->wrap()
                    ->label('Phone Number'),
                Tables\Columns\TextColumn::make('text')
                    ->wrap()
                    ->label('Message Text')->limit(20),
                Tables\Columns\BadgeColumn::make('state')
                    ->wrap()
                    ->label('State')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            0 => 'Sent',
                            1 => 'Failed',
                            2 => 'Pending',
                            default => 'Unknown',
                        };
                    })
                    ->colors([
                        'success' => 0, // Pending - Yellow
                        'danger' => 1, // Sent - Green
                        'warning' => 2,  // Failed - Red
                    ]),
            
                Tables\Columns\TextColumn::make('send_time')
                    ->label('Send Time')
                    ->sortable(), // Allow sorting by send_time
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSendSms::route('/'),
            'create' => Pages\CreateSendSms::route('/create'),
            'edit' => Pages\EditSendSms::route('/{record}/edit'),
        ];
    }
}
