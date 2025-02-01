<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentMethodResource\Pages;
use App\Filament\Resources\PaymentMethodResource\RelationManagers;
use App\Models\payment_method;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = payment_method::class;
    protected static ?string $pluralLabel = 'نحوه پرداخت';
    protected static ?string $label = 'نحوه پرداخت';
    protected static ?string $navigationGroup = 'اطلاعات پایه';
    protected static ?string $navigationLabel = 'نحوه پرداخت';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2) // Creates a two-column layout
                ->schema([
                    Toggle::make('status')
                        ->label('وضعیت')
                        ->required()
                        ->default(false)
                        ->reactive()
                        ->afterStateUpdated(fn($state) => $state ? 1 : 0)
                        ->offIcon('')
                        ->helperText('وضعیت آنالیز را انتخاب کنید')
                        ->columnSpan(1), // It will take 1/2 of the available space
    
                    TextInput::make('title')
                        ->label('عنوان آنالیز')
                        ->maxLength(250)
                        ->required()
                        ->placeholder('عنوان آنالیز را وارد کنید')
                        ->columnSpan(2), // It will take 1/2 of the available space
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ردیف')
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان آنالیز')
                    ->searchable()
                    ->wrap()
                    ->toggleable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('status')
                    ->label('وضعیت') // Optional: You can remove this if you don't want any column label.
                    ->icon(fn($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle') // Use any icon as per your preference
                    ->color(fn($state) => $state ? 'success' : 'danger')
                    ->sortable()
                    ->wrap()
                    ->toggleable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->wrap()
                    ->toggleable()
                    ->sortable(),
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
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}
