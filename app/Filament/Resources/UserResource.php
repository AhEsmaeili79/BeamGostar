<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Rawilk\FilamentPasswordInput\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    
    // Make methods public to override parent methods
    public static function getNavigationGroup(): string
    {
        return __('filament.labels.user_management');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.labels.users');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.labels.users');
    }

    public static function getLabel(): string
    {
        return __('filament.labels.users');
    }

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('filament.labels.username'))
                    ->required()
                    ->maxLength(50)
                    ->reactive()
                    ->hiddenOn(['edit']),

                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),

                Password::make('password')
                    ->label(__('filament.labels.password'))
                    ->required()
                    ->password()
                    ->hiddenOn(['edit'])
                    ->maxLength(50)
                    ->reactive(),

                Password::make('re_password')
                    ->label(__('filament.labels.password'))
                    ->required()
                    ->password()
                    ->hiddenOn(['edit'])
                    ->same('password')
                    ->maxLength(50)
                    ->reactive(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('filament.labels.row'))
                    ->sortable()
                    ->wrap(),

                TextColumn::make('customer_full_name')
                    ->label(__('filament.labels.full_name'))
                    ->getStateUsing(function ($record) {
                        // Try to get the full name from customer or personnel
                        $fullName = trim(($record->customer ? ($record->customer->name_fa . ' ' . $record->customer->family_fa) : '') 
                                    ?: ($record->personnel ? ($record->personnel->name . ' ' . $record->personnel->family) : ''));
                
                        // Return the full name or "-" if empty
                        return $fullName ?: '-';
                    })
                    ->wrap(),

                TextColumn::make('name')
                    ->label(__('filament.labels.username')),

                TextColumn::make('roles.name')
                    ->label(__('filament.labels.working_group'))
                    ->wrap(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
