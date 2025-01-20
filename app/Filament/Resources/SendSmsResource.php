<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SendSmsResource\Pages;
use App\Filament\Resources\SendSmsResource\RelationManagers;
use App\Models\Customers;
use App\Models\SendSms;
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
    protected static ?string $model = SendSms::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'اطلاعات پایه';

    protected static ?string $navigationLabel = 'پیامک ها';

    protected static ?string $pluralLabel = 'پیامک ها';

    protected static ?string $singularLabel = 'پیامک ها';

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
