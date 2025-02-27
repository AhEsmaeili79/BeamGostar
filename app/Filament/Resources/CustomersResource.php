<?php

namespace App\Filament\Resources;

use App\Exports\CustomersExport;
use App\Filament\Resources\CustomersResource\Pages;
use App\Models\Customers;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
use App\Rules\ValidNationalCode;
use Illuminate\Validation\Rule;
use Morilog\Jalali\Jalalian;

class CustomersResource extends Resource
{
    protected static ?string $model = Customers::class;

    protected static ?string $pluralLabel = null;
    protected static ?string $navigationLabel = null;
    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?string $navigationGroup = null;
    protected static ?string $pluralModelLabel = null;
    protected static ?string $label = null;
    protected static ?string $singularLabel = null;

    // Dynamically get the labels and navigation
    public static function getLabel(): string
    {
        return __('filament.labels.customer');
    }

    public static function getSingularLabel(): string
    {
        return __('filament.labels.singular_customer');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.labels.customers_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.labels.customers_management');
    }

    public static function getNavigationGroup(): string
    {
        return __('filament.labels.admissions');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.labels.customers_management');
    }

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        $isPersian = app()->getLocale() === 'fa';
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament.labels.customer_type'))
                ->schema([
                    Radio::make('customer_type')
                        ->options([
                            0 => __('filament.labels.customer_type_options.0'),
                            1 => __('filament.labels.customer_type_options.1'),
                        ])
                        ->label(__('filament.labels.customer_type'))
                        ->inline()
                        ->required()
                        ->reactive()
                        ->default(0)
                        ->extraAttributes([
                            'class' => 'p-3 rounded-lg border border-gray-300 shadow-sm hover:border-indigo-500 transition-colors duration-300',
                        ]),
            
                    Radio::make('nationality')
                        ->options([
                            0 => __('filament.labels.nationality_options.0'),
                            1 => __('filament.labels.nationality_options.1'),
                        ])
                        ->label(__('filament.labels.nationality'))
                        ->inline()
                        ->required()
                        ->reactive()
                        ->default(0)
                        ->extraAttributes([
                            'class' => 'p-3 rounded-lg border border-gray-300 shadow-sm hover:border-indigo-500 transition-colors duration-300',
                        ]),
            
                    Radio::make('clearing_type')
                        ->options([
                            0 => __('filament.labels.clearing_type_options.0'),
                            1 => __('filament.labels.clearing_type_options.1'),
                        ])
                        ->label(__('filament.labels.clearing_type'))
                        ->inline()
                        ->required()
                        ->default(0)
                        ->reactive()
                        ->extraAttributes([
                            'class' => 'p-3 rounded-lg border border-gray-300 shadow-sm hover:border-indigo-500 transition-colors duration-300',
                        ]),
                ])
                ->extraAttributes([
                    'class' => 'flex space-x-4 items-center', 
                ]),

                TextInput::make('company_fa')
                    ->label(__('filament.labels.company_fa'))
                    ->prefixIcon('heroicon-m-globe-alt')
                    ->nullable()
                    ->visible(fn ($state, $get) => $get('customer_type') == 1),

                TextInput::make('company_en')
                    ->label(__('filament.labels.company_en'))
                    ->prefixIcon('heroicon-m-globe-alt')
                    ->nullable()
                    ->visible(fn ($state, $get) => $get('customer_type') == 1),

                TextInput::make('name_fa')
                    ->label(fn ($get) => $get('customer_type') == 1 && $get('nationality') == 0 ? __('filament.labels.name_fa') : __('filament.labels.name_fa'))
                    ->required(),
                
                TextInput::make('family_fa')
                    ->label(fn ($get) => $get('customer_type') == 1 && $get('nationality') == 0 ? __('filament.labels.family_fa') : __('filament.labels.family_fa'))
                    ->required(),
                
                TextInput::make('name_en')
                    ->label(fn ($get) => $get('customer_type') == 1 && $get('nationality') == 0 ? __('filament.labels.name_en') : __('filament.labels.name_en'))
                    ->nullable(),
                
                TextInput::make('family_en')
                    ->label(fn ($get) => $get('customer_type') == 1 && $get('nationality') == 0 ? __('filament.labels.family_en') : __('filament.labels.family_en'))
                    ->nullable(),

                TextInput::make('national_code')
                    ->label(__('filament.labels.national_code'))
                    ->numeric()
                    ->nullable()
                    ->reactive()
                    ->required()
                    ->mask(9999999999)
                    ->visible(fn ($state, $get) => $get('customer_type') == 0 && $get('nationality') == 0)
                    ->rules(function ($get, $record) {
                        return [
                            'required',
                            'numeric',
                            'digits:10',
                            Rule::unique('users', 'name')->ignore($record ? $record->user_id : null),  // Ignore user if editing
                            new ValidNationalCode(),
                        ];
                    })
                    ->readonly(fn ($get) => $get('record') !== null),  // Make it readonly when editing
                
                TextInput::make('national_id')
                    ->label(__('filament.labels.national_id'))
                    ->numeric()
                    ->required()
                    ->unique('users', 'name')
                    ->reactive()
                    ->visible(fn ($state, $get) => $get('customer_type') == 1)
                    ->rules(function ($get, $record) {
                        return [
                            'required',
                            'numeric',
                            Rule::unique('users', 'name')->ignore($record ? $record->user_id : null),  // Ignore user if editing
                        ];
                    })
                    ->readonly(fn ($get) => $get('record') !== null),  // Make it readonly when editing
                
                TextInput::make('passport')
                    ->label(__('filament.labels.passport'))
                    ->required() // Required field
                    ->unique('users', 'name') // Unique passport number
                    ->visible(fn ($state, $get) => $get('nationality') == 1) // Conditional visibility based on nationality
                    ->reactive()
                    ->rules(function ($get, $record) {
                        return [
                            'required', // Ensures passport number is numeric (if you want alphanumeric, replace with 'regex')
                            Rule::unique('users', 'name')->ignore($record ? $record->user_id : null), // Ignore current record when editing
                            // Custom validation rule for passport format (alphanumeric, 6-9 characters)
                            function ($attribute, $value, $fail) {
                                // Updated regex for passport number: letter (any) followed by 6-9 digits
                                if (!preg_match('/^[A-Z][0-9]{6,9}$/', $value)) {
                                    $fail(__('filament.alert.passport_alert', ['attribute' => $attribute]));
                                }
                            },
                        ];
                    })
                    ->readonly(fn ($get) => $get('record') !== null) // Set it as readonly when the record exists
                    ->placeholder(__('filament.placeholders.passportplacehoder')),
                
        
                TextInput::make('economy_code')
                    ->label(__('filament.labels.economy_code'))
                    ->numeric()
                    ->required()
                    ->visible(fn ($state, $get) => $get('customer_type') == 1 && $get('nationality') == 0), 

                tap(DatePicker::make('birth_date')
                    ->label(__('filament.labels.birth_date'))
                    ->nullable()
                    ->visible(fn ($state, $get) => $get('customer_type') == 0 && $get('nationality') == 0), 
                    function ($datePicker) use ($isPersian) {
                        if ($isPersian) {
                            $datePicker->jalali(); // Apply Jalali only if Persian is selected
                        }
                    }
                ),

                PhoneInput::make('mobile')
                    ->label(__('filament.labels.mobile'))
                    ->placeholder(__('filament.placeholders.mobile'))
                    ->required()
                    ->countrySearch(true)
                    ->allowDropdown(true)
                    ->autoPlaceholder('polite')
                    ->initialCountry('ir')
                    ->nationalMode(true)
                    ->showFlags(true)
                    ->separateDialCode(true)
                    ->formatAsYouType(true)
                    ->locale('fa')
                    ->i18n([
                        'en' => 'English',
                        'fa' => 'فارسی',
                    ])
                    ->validateFor('ir', ['lenient' => false, 'type' => 'mobile', 'length' => 10])
                    ->strictMode(true)
                    ->extraAttributes([
                        'oninput' => "if (this.value.startsWith('0')) { this.value = this.value.substring(1); }"
                    ]),
                
                TextInput::make('phone')
                    ->label(__('filament.labels.phone'))
                    ->tel()
                    ->numeric()
                    ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]{0,11}$/')
                    ->maxLength(11)
                    ->nullable()
                    ->visible(fn ($state, $get) => $get('customer_type') == 1),

                TextInput::make('email')
                    ->label(__('filament.labels.email'))
                    ->email()
                    ->nullable(),

                TextInput::make('postal_code')
                    ->label(__('filament.labels.postal_code'))
                    ->numeric()
                    ->mask(9999999999)
                    ->nullable(),

                TextInput::make('address')
                    ->label(__('filament.labels.address'))
                    ->nullable(),
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
                Tables\Columns\BadgeColumn::make('customer_type')
                ->label(__('filament.labels.customer_type'))
                ->formatStateUsing(function ($state) {
                    return $state == 0 ? __('filament.labels.customer_type_options.0') : __('filament.labels.customer_type_options.1');
                })
                ->color(fn($state) => $state == 0 ? 'success' : 'danger')
                ->icon(fn($state) => $state == 0 ? 'heroicon-o-user' : 'heroicon-o-building-office')
                ->wrap()
                ->badge()
                ->searchable(),
                
                Tables\Columns\TextColumn::make('nationality')
                    ->label(__('filament.labels.nationality'))
                    ->wrap()
                    ->formatStateUsing(function ($state) {
                        return $state == 0 ? __('filament.labels.nationality_options.0') : __('filament.labels.nationality_options.1');
                    }),

                Tables\Columns\TextColumn::make('clearing_type')
                    ->label(__('filament.labels.clearing_type'))
                    ->wrap()
                    ->formatStateUsing(function ($state) {
                        return $state == 0 ? __('filament.labels.clearing_type_options.0') : __('filament.labels.clearing_type_options.1');
                    }),

                Tables\Columns\TextColumn::make('name_fa')
                    ->label(__('filament.labels.name_fa'))
                    ->wrap()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('family_fa')
                    ->label(__('filament.labels.family_fa'))
                    ->wrap()
                    ->searchable(),

                Tables\Columns\TextColumn::make('national_code')
                    ->label(__('filament.labels.national_code'))
                    ->toggleable()
                    ->wrap()
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        return $record->national_code ?: '-';
                    }),

                Tables\Columns\TextColumn::make('national_id')
                    ->label(__('filament.labels.national_id'))
                    ->toggleable()
                    ->wrap()
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        return $record->national_id ?: '-';
                    }),

                Tables\Columns\TextColumn::make('passport')
                    ->label(__('filament.labels.passport'))
                    ->toggleable()
                    ->wrap()
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        return $record->passport ?: '-';
                    }),
                
                PhoneColumn::make('mobile')
                    ->displayFormat(PhoneInputNumberType::NATIONAL)
                    ->label(__('filament.labels.mobile'))
                    ->wrap()
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
                BulkAction::make('Export')
                ->requiresConfirmation()
                ->icon('heroicon-o-document')
                ->action(fn (\Illuminate\Database\Eloquent\Collection $records) => (new CustomersExport($records))->download('customers.xlsx')),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomers::route('/create'),
            'edit' => Pages\EditCustomers::route('/{record}/edit'),
        ];
    }
}
