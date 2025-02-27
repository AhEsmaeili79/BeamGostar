<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubsystemResource\Pages;
use App\Filament\Resources\SubsystemResource\RelationManagers;
use App\Models\Subsystem;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use \Illuminate\Support\Str;
class SubsystemResource extends Resource
{
    protected static ?string $model = Subsystem::class;

    protected static ?string $pluralLabel = 'منوی اصلی ';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'مدیریت کاربران';
    protected static ?string $navigationLabel = 'منوی اصلی ';
    protected static ?string $label = 'منوی اصلی ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make('state')
                    ->label('وضعیت')
                    ->default(1)
                    ->columnSpan(2),

                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(50)
                    ->reactive(),

                TextInput::make('title_en')
                    ->label('Title (EN)')
                    ->nullable()
                    ->maxLength(50),

                TextInput::make('icon_class')
                    ->label('Icon Class')
                    ->nullable()
                    ->maxLength(30),

                TextInput::make('ordering')
                    ->label('Ordering')
                    ->numeric()
                    ->required()
                    ->maxLength(3),
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
                TextColumn::make('id')->sortable()->searchable(),
                TextColumn::make('title')->sortable()->searchable(),
                TextColumn::make('title_en')->sortable(),
                Tables\Columns\IconColumn::make('state')
                ->label('وضعیت') // Optional: You can remove this if you don't want any column label.
                ->icon(fn($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle') // Use any icon as per your preference
                ->color(fn($state) => $state ? 'success' : 'danger')
                ->sortable()
                ->wrap()
                ->toggleable()
                ->searchable(),
                TextColumn::make('ordering')->sortable(),
                TextColumn::make('updated_at')->label('Last Updated')->dateTime(),
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
            RelationManagers\MenuRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubsystems::route('/'),
            'create' => Pages\CreateSubsystem::route('/create'),
            'edit' => Pages\EditSubsystem::route('/{record}/edit'),
        ];
    }
}
