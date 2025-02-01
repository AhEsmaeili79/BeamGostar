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

class AnalyzeResource extends Resource
{
    protected static ?string $model = Analyze::class;
    protected static ?string $pluralLabel = 'اطلاعات آنالیز ها';
    protected static ?string $navigationGroup = 'اطلاعات پایه';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'اطلاعات آنالیز ها';

    protected static ?string $label = 'آنالیز';

    protected static ?string $singularLabel = 'آنالیز';

    protected static ?int $navigationSort =2;
    
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
                    ->default(1)
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
