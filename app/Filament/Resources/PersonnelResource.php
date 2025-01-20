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

    protected static ?string $pluralLabel =  'پرسنل';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'اطلاعات پایه';
    protected static ?string $navigationLabel = 'پرسنل';
    protected static ?string $label = 'پرسنل';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            TextInput::make('name')
                ->label('Name')
                ->required()
                ->maxLength(40),
            TextInput::make('family')
                ->label('Family')
                ->required()
                ->maxLength(40),
            TextInput::make('national_code')
                ->label('National Code')
                ->required()
                ->unique('personnel', 'name')
                ->unique('users', 'name')
                ->maxLength(15),
            
                Select::make('role_id')
                ->label('Role')
                ->required()
                ->hiddenOn(['edit'])
                ->options(function () {
                    // Fetch all roles from the Role model and return them as options
                    return Role::all()->pluck('name', 'id'); // `id` will be the value and `name` will be the label
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
                    ->label('Name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('family')
                    ->label('Family Name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('national_code')
                    ->label('National Code')
                    ->searchable(),
                
                TextColumn::make('user.roles.name') 
                    ->label('User Roles')
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
