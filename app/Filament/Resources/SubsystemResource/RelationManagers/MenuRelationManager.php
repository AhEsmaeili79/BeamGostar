<?php

namespace App\Filament\Resources\SubsystemResource\RelationManagers;
use App\Models\Subsystem;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
class MenuRelationManager extends RelationManager
{

    protected static ?string $pluralModelLabel = 'زیرمنو ها';
    protected static ?string $pluralLabel = 'زیرمنو ها';
    
    protected static ?string $label = 'زیرمنو ها';
    protected static ?string $title = 'زیرمنو ها';
    protected static ?string $modelLabel = 'زیرمنو ها';
    protected static string $relationship = 'menu';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make('state')
                    ->label('وضعیت')
                    ->default(1)
                    ->columnSpan(2),
                TextInput::make('title')
                ->required()
                ->maxLength(50)
                ->reactive(),
                TextInput::make('title_en')
                    ->maxLength(50),
                TextInput::make('icon_class')
                    ->maxLength(30),
                TextInput::make('route')
                    ->maxLength(100)
                    ->required(),
                TextInput::make('ordering')
                    ->required()
                    ->numeric()
                    ->required(),
                TextInput::make('subsystem_id')
                    ->default(fn($record) => $this->ownerRecord->id) // Automatically set parent_id
                    ->hidden(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('subsystem_id')
            ->columns([
                // Tables\Columns\TextColumn::make('subsystem_id'),
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
                TextColumn::make('subsystem.title')->label('Parent Menu'),
                TextColumn::make('updated_at')->label('Last Updated')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}

