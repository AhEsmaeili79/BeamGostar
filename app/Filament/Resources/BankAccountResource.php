<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BankAccountResource\Pages;
use App\Models\bank_account;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Morilog\Jalali\Jalalian;
class BankAccountResource extends Resource
{
    protected static ?string $model = bank_account::class;

    public static function getNavigationGroup(): string
    {
        return __('filament.labels.base_info');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.labels.bank_accounts');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.labels.bank_accounts');
    }

    public static function getLabel(): string
    {
        return __('filament.labels.bank_account');
    }

    public static function getSingularLabel(): string
    {
        return __('filament.labels.bank_account');
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        Forms\Components\Radio::make('account_type')
                            ->options([
                                1 => __('filament.labels.account_type') . ' ' . __('filament.labels.official'),
                                0 => __('filament.labels.account_type') . ' ' . __('filament.labels.unofficial'),
                            ])
                            ->label(__('filament.labels.account_type'))
                            ->inline()
                            ->required()
                            ->columnSpan(2)
                            ->reactive()
                            ->default(1),

                        Forms\Components\TextInput::make('account_number')
                            ->label(__('filament.labels.account_number'))
                            ->required()
                            ->mask('9999999999999999')
                            ->minLength(12)
                            ->maxLength(16)
                            ->rules([
                                'required',
                                'min:12',
                                'max:16',
                            ])
                            ->columnSpan([
                                'default' => 2,
                                'sm' => 1,
                            ]),

                        Forms\Components\TextInput::make('card_number')
                            ->label(__('filament.labels.card_number'))
                            ->mask('9999 9999 9999 9999')
                            ->placeholder('xxxx xxxx xxxx xxxx')
                            ->dehydrateStateUsing(fn($state) => str_replace(' ', '', $state))
                            ->columnSpan([
                                'default' => 2,
                                'sm' => 1,
                            ]),

                        Forms\Components\TextInput::make('shaba_number')
                            ->label(__('filament.labels.shaba_number'))
                            ->suffix('IR')
                            ->dehydrateStateUsing(fn($state) => str_replace(' ', '', $state))
                            ->mask('99 9999 9999 9999 9999 9999 99')
                            ->placeholder(__('filament.labels.shaba_number'))
                            ->helperText(__('filament.labels.enter_shaba_number'))
                            ->extraInputAttributes(['dir' => 'ltr'])
                            ->columnSpan([
                                'default' => 2,
                                'sm' => 1,
                            ]),

                        Forms\Components\TextInput::make('account_holder_name')
                            ->label(__('filament.labels.account_holder_name'))
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
                    ->label(__('filament.labels.row'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('account_type')
                    ->label(__('filament.labels.account_type'))
                    ->formatStateUsing(function ($state) {
                        return $state == 0 ? __('filament.labels.unofficial') : __('filament.labels.official');
                    })
                    ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                    ->icon(fn($state) => $state == 1 ? 'heroicon-o-user' : 'heroicon-o-building-office')
                    ->wrap()
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('account_number')
                    ->label(__('filament.labels.account_number'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('card_number')
                    ->label(__('filament.labels.card_number'))
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => implode(' ', str_split($state, 4))),

                Tables\Columns\TextColumn::make('shaba_number')
                    ->label(__('filament.labels.shaba_number'))
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) =>
                        substr($state, 0, 2) . ' ' .
                        substr($state, 2, 4) . ' ' .
                        substr($state, 6, 4) . ' ' .
                        substr($state, 10, 4) . ' ' .
                        substr($state, 14, 4) . ' ' .
                        substr($state, 18, 4) . ' ' .
                        substr($state, 22, 2)
                    ),

                Tables\Columns\TextColumn::make('account_holder_name')
                    ->label(__('filament.labels.account_holder_name'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.labels.created_at'))
                    ->formatStateUsing(fn ($state) => 
                        app()->getLocale() === 'fa' 
                            ? Jalalian::fromDateTime($state)->format('Y/m/d H:i') // Convert to Jalali
                            : \Carbon\Carbon::parse($state)->format('Y-m-d H:i') // Gregorian format
                    )
                    ->sortable(),
            ])
            ->filters([
                // Add filters if necessary
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBankAccounts::route('/'),
            'create' => Pages\CreateBankAccount::route('/create'),
            'edit' => Pages\EditBankAccount::route('/{record}/edit'),
        ];
    }
}
