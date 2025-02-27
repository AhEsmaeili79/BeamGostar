<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BackgroundImageResource\Pages;
use App\Models\BackgroundImage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;

use Filament\Tables\Columns\ImageColumn;
class BackgroundImageResource extends Resource
{
    protected static ?string $model = BackgroundImage::class;


    protected static ?string $navigationLabel = 'صفحه ورود';

    protected static ?string $navigationGroup = 'مدیریت کاربران';

    protected static ?string $label = 'صفحه ورود';


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->label(__('filament.labels.image'))
                    ->maxSize(2048) // 2 MB
                    ->nullable()
                    ->disk('public')
                    ->directory('background'),
            ]); 
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Background Image')
                    ->size(100),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListBackgroundImages::route('/'),
            'create' => Pages\CreateBackgroundImage::route('/create'),
            'edit' => Pages\EditBackgroundImage::route('/{record}/edit'),
        ];
    }
}
