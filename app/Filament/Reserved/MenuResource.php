<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'مدیریت کاربران';
    protected static ?int $navigationSort = 5;

    // Remove dynamic translations from properties
    protected static ?string $navigationLabel = null;
    protected static ?string $pluralLabel = null;

    public static function getLabel(): string
    {
        return __('filament.labels.submenu');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.labels.submenus');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.labels.submenus');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make('state')
                    ->label(__('filament.labels.status'))
                    ->default(1)
                    ->columnSpan(2),

                TextInput::make('title')
                    ->label(__('filament.labels.title'))
                    ->required()
                    ->maxLength(50)
                    ->reactive(),

                TextInput::make('title_en')
                    ->label(__('filament.labels.title_en'))
                    ->maxLength(50),

                TextInput::make('icon_class')
                    ->label(__('filament.labels.icon_class'))
                    ->maxLength(30),

                TextInput::make('route')
                    ->label(__('filament.labels.route'))
                    ->maxLength(100)
                    ->required(),

                TextInput::make('ordering')
                    ->label(__('filament.labels.ordering'))
                    ->required()
                    ->numeric(),

                Select::make('subsystem_id')
                    ->label(__('filament.labels.parent_menu'))
                    ->relationship('subsystem', 'title')
                    ->nullable(),
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
                TextColumn::make('id')
                    ->label(__('filament.labels.id'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('title')
                    ->label(__('filament.labels.title'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('title_en')
                    ->label(__('filament.labels.title_en'))
                    ->sortable(),

                Tables\Columns\IconColumn::make('state')
                    ->label(__('filament.labels.status'))
                    ->icon(fn($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn($state) => $state ? 'success' : 'danger')
                    ->sortable()
                    ->wrap()
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('ordering')
                    ->label(__('filament.labels.ordering'))
                    ->sortable(),

                TextColumn::make('subsystem.title')
                    ->label(__('filament.labels.parent_menu')),

                TextColumn::make('updated_at')
                    ->label(__('filament.labels.updated_at'))
                    ->dateTime(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
