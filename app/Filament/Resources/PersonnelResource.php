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
use Morilog\Jalali\Jalalian;
use App\Rules\ValidNationalCode;
use Illuminate\Validation\Rule;

class PersonnelResource extends Resource
{
    protected static ?string $model = Personnel::class;

    protected static ?string $pluralLabel =  'مدیریت پرسنل';
    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'اطلاعات پایه';
    protected static ?string $navigationLabel = 'مدیریت پرسنل';
    protected static ?string $label = 'مدیریت پرسنل';

    
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
                ->label(__('filament.labels.national_code'))
                ->numeric()
                ->required()
                ->mask(9999999999)
                ->rules(function ($get, $record) {
                    return [
                        'required', 
                        'numeric',   
                        'digits:10', 
                        // Unique validation: Ignore the current 'national_code' in edit mode
                        Rule::unique('personnel', 'national_code')->ignore($record ? $record->id : null),  // Ignore the current record during edit
                        Rule::unique('users', 'name')->ignore($record ? $record->user_id : null),  // Ignore user if editing
                        new ValidNationalCode(),
                    ];
                })
                ->readonly(fn ($get) => $get('record') !== null),  // Make it readonly when editing


            Select::make('role_id')
                ->label('نقش')
                ->required()
                ->hiddenOn(['edit'])
                ->options(function () {
                    return Role::where('name', '!=', 'مشتریان')->pluck('name', 'id'); 
                })
                ->searchable(),
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
                TextColumn::make('created_at')
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
