<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GetAnswersResource\Pages;
use App\Models\get_answers;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Morilog\Jalali\Jalalian;

class GetAnswersResource extends Resource
{
    protected static ?string $model = get_answers::class;
    public static function getNavigationGroup(): string
    {
        return __('filament.labels.base_info');
    }
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';


    protected static ?int $navigationSort = 5;

    // Remove dynamic translations from here
    protected static ?string $navigationLabel = null;  // To be set by a method

    public static function getLabel(): string
    {
        return __('filament.labels.get_answers_management');
    }

    // Move this dynamic property to a method
    public static function getNavigationLabel(): string
    {
        return __('filament.labels.get_answers_management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        Toggle::make('status')
                            ->label(__('filament.labels.status'))
                            ->required()
                            ->default(false)
                            ->reactive()
                            ->afterStateUpdated(fn($state) => $state ? 1 : 0)
                            ->offIcon('')
                            ->helperText(__('filament.labels.choose_status'))
                            ->columnSpan(1),

                        TextInput::make('title')
                            ->label(__('filament.labels.title'))
                            ->maxLength(250)
                            ->required()
                            ->unique(get_answers::class, 'title') // Enforce unique validation for the title
                            ->placeholder(__('filament.labels.title'))
                            ->columnSpan(2),
                    ]),
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
                Tables\Columns\TextColumn::make('id')
                    ->label(__('filament.labels.row'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament.labels.title'))
                    ->searchable()
                    ->wrap()
                    ->toggleable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('status')
                    ->label(__('filament.labels.status'))
                    ->icon(fn($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn($state) => $state ? 'success' : 'danger')
                    ->sortable()
                    ->wrap()
                    ->toggleable()
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
            'index' => Pages\ListGetAnswers::route('/'),
            'create' => Pages\CreateGetAnswers::route('/create'),
            'edit' => Pages\EditGetAnswers::route('/{record}/edit'),
        ];
    }
}
