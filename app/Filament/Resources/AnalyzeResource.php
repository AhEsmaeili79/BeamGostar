<?php

namespace App\Filament\Resources;

use App\Exports\AnalyzeExport;
use App\Filament\Resources\AnalyzeResource\Pages;
use App\Models\Analyze;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid;
use Morilog\Jalali\Jalalian;

class AnalyzeResource extends Resource
{
    protected static ?string $model = Analyze::class;
    
    public static function getNavigationGroup(): string
    {
        return __('filament.labels.base_info');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.labels.analyzes');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.labels.analyzes');
    }

    public static function getLabel(): string
    {
        return __('filament.labels.analyze');
    }

    public static function getSingularLabel(): string
    {
        return __('filament.labels.analyze');
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?int $navigationSort = 2;
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2) // Creates a two-column layout
                    ->schema([
                        Toggle::make('status')
                            ->label(__('filament.labels.status'))
                            ->required()
                            ->default(false)
                            ->reactive()
                            ->afterStateUpdated(fn($state) => $state ? 1 : 0)
                            ->offIcon('')
                            ->helperText(__('filament.labels.status'))
                            ->columnSpan(1), // It will take 1/2 of the available space
                
                        TextInput::make('title')
                            ->label(__('filament.labels.title'))
                            ->maxLength(250)
                            ->required()
                            ->placeholder(__('filament.labels.title'))
                            ->unique(Analyze::class, 'title') // Enforce unique validation for the title
                            ->columnSpan(2), // It will take 1/2 of the available space
                    ]),
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
                    ->default(1)
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
                // Add filters if necessary
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                BulkAction::make('Export')
                    ->requiresConfirmation()
                    ->icon('heroicon-o-document')
                    ->action(fn (\Illuminate\Database\Eloquent\Collection $records) => (new AnalyzeExport($records))->download('Analyze.xlsx')),
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
            'index' => Pages\ListAnalyzes::route('/'),
            'create' => Pages\CreateAnalyze::route('/create'),
            'edit' => Pages\EditAnalyze::route('/{record}/edit'),
        ];
    }
}
