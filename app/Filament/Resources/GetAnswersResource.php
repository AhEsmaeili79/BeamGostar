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

class GetAnswersResource extends Resource
{
    protected static ?string $model = get_answers::class;
    protected static ?string $pluralLabel = 'نحوه جوابدهی';
    protected static ?string $navigationGroup = 'اطلاعات پایه';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'نحوه جوابدهی';

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
            ->label('عنوان')
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
