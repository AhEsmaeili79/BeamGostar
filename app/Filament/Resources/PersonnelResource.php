<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PersonnelResource\Pages;
use App\Models\Personnel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;



class PersonnelResource extends Resource
{
    protected static ?string $model = Personnel::class;

    protected static ?string $pluralLabel =  'مدیریت اشخاص';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'اطلاعات پایه';
    protected static ?string $navigationLabel = 'مدیریت اشخاص';
    protected static ?string $label = 'مدیریت اشخاص';

    
    protected static ?int $navigationSort =1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            TextInput::make('name')
                ->label('نام')
                ->required()
                ->maxLength(40),
            TextInput::make('family')
                ->label('نام خانوادگی')
                ->required()
                ->maxLength(40),
            TextInput::make('national_code')
                ->label('کد ملی')
                ->required()
                ->unique('personnel', 'name')
                ->unique('users', 'name')
                ->maxLength(15),
            
                Select::make('role_id')
                ->label('نقش')
                ->required()
                ->hiddenOn(['edit'])
                ->options(function () {
                    return Role::all()->pluck('name', 'id'); 
                })
                ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('name')
                    ->label('نام')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('family')
                    ->label('نام خانوادگی')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('national_code')
                    ->label('کد ملی')
                    ->searchable(),
                
                TextColumn::make('user.roles.name') 
                    ->label('نقش ها')
                    ->wrap(),
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
            'index' => Pages\ListPersonnels::route('/'),
            'create' => Pages\CreatePersonnel::route('/create'),
            'edit' => Pages\EditPersonnel::route('/{record}/edit'),
        ];
    }
}
