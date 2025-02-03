<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BankAccountResource\Pages;
use App\Filament\Resources\BankAccountResource\RelationManagers;
use App\Models\bank_account;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\RawJs;
class BankAccountResource extends Resource
{
    protected static ?string $model = bank_account::class;

    protected static ?string $pluralLabel = 'اطلاعات حساب بانکی';
    protected static ?string $navigationGroup = 'اطلاعات پایه';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'اطلاعات حساب بانکی';
    protected static ?string $label = 'اطلاعات حساب بانکی';
    protected static ?string $singularLabel = 'اطلاعات حساب بانکی';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                ->schema([
                Forms\Components\Radio::make('account_type')
                    ->options([
                        1 => 'رسمی',
                        0 => 'غیررسمی',
                    ])
                    ->label('نوع حساب')
                    ->inline()
                    ->required()
                    ->columnSpan(2)
                    ->reactive()
                    ->default(1),

                Forms\Components\TextInput::make('account_number')
                    ->label('شماره حساب')
                    ->required()
                    ->mask('9999999999999999')
                    ->minLength(12)
                    ->maxLength(16)
                    ->columnSpan([
                        'default' => 2,  
                        'sm' => 1,       
                    ]),
                
                    Forms\Components\TextInput::make('card_number')
    ->label('شماره کارت')
    ->mask('9999 9999 9999 9999')
    ->placeholder('xxxx xxxx xxxx xxxx') 
    ->dehydrateStateUsing(fn ($state) => str_replace(' ', '', $state))  
    ->columnSpan([
        'default' => 2, 
        'sm' => 1,       
    ]),

Forms\Components\TextInput::make('shaba_number') 
    ->label('شماره شبا') 
    ->suffix('IR')
    ->dehydrateStateUsing(fn ($state) => str_replace(' ', '', $state))  
    ->mask('99 9999 9999 9999 9999 9999 99') 
    ->placeholder('xx xxxx xxxx xxxx xxxx xxxx xxxx xx') 
    ->helperText('شماره شبا را بدون IR وارد کنید') 
    ->extraInputAttributes(['dir' => 'ltr']) 
    ->columnSpan([
        'default' => 2,  
        'sm' => 1,       
    ]),


                

                Forms\Components\TextInput::make('account_holder_name')
                    ->label('نام دارنده حساب')
                    ->required()
                    ->maxLength(150)
                    ->columnSpan([
                        'default' => 2, 
                        'sm' => 1,       
                    ]),
            ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ردیف')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('account_type')
                ->label('نوع حساب')
                    ->formatStateUsing(function ($state) {
                        return $state == 0 ? 'غیررسمی' : 'رسمی';
                    })
                    ->color(fn($state) => $state == 1 ? 'success': 'danger' ) // 'primary' for 'حقیقی' and 'success' for 'حقوقی'
                    ->icon(fn($state) => $state == 1 ? 'heroicon-o-user' : 'heroicon-o-building-office') // Correct icon name
                    ->wrap()
                    ->badge() // Ensures the state is displayed as a badge
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('account_number')
                    ->label('شماره حساب')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('card_number')
                    ->label('شماره کارت')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('shaba_number')
                    ->label('شماره شبا')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('account_holder_name')
                    ->label('نام دارنده حساب')
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListBankAccounts::route('/'),
            'create' => Pages\CreateBankAccount::route('/create'),
            'edit' => Pages\EditBankAccount::route('/{record}/edit'),
        ];
    }
}
