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
use Ysfkaya\FilamentPhoneInput\Infolists\PhoneEntry;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;


class CustomersResource extends Resource
{
    protected static ?string $model = Customers::class;

    protected static ?string $pluralLabel = 'مدیریت مشتریان';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'پذیرش';
    protected static ?string $navigationLabel = 'مدیریت مشتریان';
    protected static ?int $navigationSort = 1; 


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('نوع مشتریان')
                ->schema([
                    Radio::make('customer_type')
                        ->options([
                            0 => 'حقیقی',
                            1 => 'حقوقی',
                        ])
                        ->label('نوع مشتری')
                        ->inline()
                        ->required()
                        ->reactive()
                        ->default(0)
                        ->extraAttributes([
                            'class' => 'p-3 rounded-lg border border-gray-300 shadow-sm hover:border-indigo-500 transition-colors duration-300',
                        ]),
            
                    Radio::make('nationality')
                        ->options([
                            0 => 'ایرانی',
                            1 => 'خارجی',
                        ])
                        ->label('تابعیت')
                        ->inline()
                        ->required()
                        ->reactive()
                        ->default(0)
                        ->extraAttributes([
                            'class' => 'p-3 rounded-lg border border-gray-300 shadow-sm hover:border-indigo-500 transition-colors duration-300',
                        ]),
            
                    Radio::make('clearing_type')
                        ->options([
                            0 => 'نقدی',
                            1 => 'اعتباری',
                        ])
                        ->label('نوع تسویه')
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
                    ->label('نام شرکت (فارسی)')
                    ->prefix('نام شرکت (فارسی)')
                    ->suffix('نام شرکت (فارسی)')
                    ->nullable()
                    ->visible(fn ($state, $get) => $get('customer_type') == 1),  

                TextInput::make('company_en')
                    ->label('نام شرکت (انگلیسی)')
                    ->prefixIcon('heroicon-m-globe-alt')
                    ->suffixIcon('heroicon-m-globe-alt')
                    ->nullable()
                    ->visible(fn ($state, $get) => $get('customer_type') == 1), 

                TextInput::make('name_fa')
                    ->label(fn ($get) => $get('customer_type') == 1 && $get('nationality') == 0 ? 'نام رابط' : 'نام (فارسی)')
                    ->required(),
                
                TextInput::make('family_fa')
                    ->label(fn ($get) => $get('customer_type') == 1 && $get('nationality') == 0 ? 'نام خانوادگی رابط' : 'نام خانوادگی (فارسی)')
                    ->required(),
                
                TextInput::make('name_en')
                    ->label(fn ($get) => $get('customer_type') == 1 && $get('nationality') == 0 ? 'نام رابط (انگلیسی)' : 'نام (انگلیسی)')
                    ->nullable(),
                
                TextInput::make('family_en')
                    ->label(fn ($get) => $get('customer_type') == 1 && $get('nationality') == 0 ? 'نام خانوادگی رابط (انگلیسی)' : 'نام خانوادگی (انگلیسی)')
                    ->nullable(),

                TextInput::make('national_code')
                    ->label('کد ملی')
                    ->numeric()
                    ->nullable()
                    ->unique('users', 'name')
                    ->reactive()
                    ->visible(fn ($state, $get) => $get('customer_type') == 0 && $get('nationality') == 0),  
                
                TextInput::make('national_id')
                    ->label('شناسه ملی')
                    ->numeric()
                    ->nullable()
                    ->unique('users', 'name')
                    ->reactive()
                    ->visible(fn ($state, $get) => $get('customer_type') == 1),  

                    TextInput::make('passport')
                    ->label('شماره گذرنامه')
                    ->numeric()
                    ->nullable()
                    ->unique('users', 'name')
                    ->same('password')
                    ->visible(fn ($state, $get) => $get('nationality') == 1)
                    ->reactive(),
            
                TextInput::make('economy_code')
                    ->label('کد اقتصادی')
                    ->numeric()
                    ->nullable()
                    ->visible(fn ($state, $get) => $get('customer_type') == 1 && $get('nationality') == 0),  // Show for حقوقی & ایرانی

                    
                DatePicker::make('birth_date')
                    ->label('تاریخ تولد')
                    ->jalali()
                    ->nullable()
                    ->visible(fn ($state, $get) => $get('customer_type') == 0 && $get('nationality') == 0),  // Show for حقیقی & ایرانی
                
                PhoneInput::make('mobile')
                    ->label('شماره همراه') // Maximum length for the phone number
                    ->placeholder('9121234567') // Placeholder for the phone number input
                    ->required() // Make the field required
                    ->countrySearch(true) // Allow searching by country
                    ->allowDropdown(true) // Allow country dropdown selection
                    ->autoPlaceholder('polite') // Auto-placeholders (polite mode, shows placeholder when the field is empty)
                    ->initialCountry('ir') // Set the initial country to Iran (IR) for the phone number input
                    ->nationalMode(true) // Enable national mode for phone numbers
                    ->showFlags(true) // Show country flags in the dropdown
                    ->separateDialCode(true) // Display the dial code separately from the phone number
                    ->formatAsYouType(true) // Automatically format the number as you type
                    ->locale('fa') // Set the locale to Persian (Farsi) language for the input
                    ->i18n([
                        'en' => 'English',
                        'fa' => 'فارسی', // Customize the country selector language (here Persian)
                    ])
                    ->validateFor('ir', ['lenient' => false, 'type' => 'mobile', 'length' => 10]) // Validate to accept only 10 digits for Iran's mobile numbers
                    ->strictMode(true)
                    ->extraAttributes([
                        'oninput' => "if (this.value.startsWith('0')) { this.value = this.value.substring(1); }"
                    ]),
                                    
                TextInput::make('phone')
                    ->label('شماره تماس شرکت')
                    ->tel()
                    ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]{0,11}$/') // Limits to 11 digits
                    ->maxLength(11)
                    ->nullable()
                    ->visible(fn ($state, $get) => $get('customer_type') == 1),  // Show for حقوقی
                
                TextInput::make('email')
                    ->label('پست الکترونیک')
                    ->email()
                    ->nullable(),

                TextInput::make('postal_code')
                    ->label('کد پستی')
                    ->nullable(),

                TextInput::make('address')
                    ->label('آدرس')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                ->label('ردیف')
                ->sortable(),
                Tables\Columns\BadgeColumn::make('customer_type')
                ->label('نوع مشتری')
                ->formatStateUsing(function ($state) {
                    return $state == 0 ? 'حقیقی' : 'حقوقی';
                })
                ->color(fn($state) => $state == 0 ? 'success': 'danger' ) // 'primary' for 'حقیقی' and 'success' for 'حقوقی'
                ->icon(fn($state) => $state == 0 ? 'heroicon-o-user' : 'heroicon-o-building-office') // Correct icon name
                ->wrap()
                ->badge() // Ensures the state is displayed as a badge
                ->searchable(),
                
                Tables\Columns\TextColumn::make('nationality')
                    ->label('تابعیت')
                    ->wrap()
                    ->formatStateUsing(function ($state) {
                    return $state == 0 ? 'ایرانی' : 'خارجی';
                    }),

                Tables\Columns\TextColumn::make('clearing_type')
                    ->label('نوع تسویه')
                    ->wrap()
                    ->formatStateUsing(function ($state) {
                        return $state == 0 ? 'نقدی' : 'اعتباری';
                    }),

                Tables\Columns\TextColumn::make('name_fa')
                    ->label('نام (فارسی)')
                    ->wrap()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('family_fa')
                    ->label('نام خانوادگی (فارسی)')
                    ->wrap()
                    ->searchable(),

                    Tables\Columns\TextColumn::make('national_code')
                    ->label('کد ملی')
                    ->toggleable()
                    ->wrap()
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        return $record->national_code ?: '-';
                    }),
                
                Tables\Columns\TextColumn::make('national_id')
                    ->label('شناسه ملی')
                    ->toggleable()
                    ->wrap()
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        return $record->national_id ?: '-';
                    }),
                
                Tables\Columns\TextColumn::make('passport')
                    ->label('شماره گذرنامه')
                    ->toggleable()
                    ->wrap()
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        return $record->passport ?: '-';
                    }),

                Tables\Columns\TextColumn::make('mobile')
                    ->label('شماره همراه')
                    ->wrap()
                    ->searchable(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
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
