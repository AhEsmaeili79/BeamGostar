<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerAnalysisResource\Pages;
use App\Http\Controllers\CustomerAnalysisController;
use App\Models\CustomerAnalysis;
use App\Models\Customers;
use App\Models\Analyze;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\App;
use Morilog\Jalali\Jalalian;
class CustomerAnalysisResource extends Resource
{
    protected static ?string $model = CustomerAnalysis::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'مدیریت آنالیز مشتریان';

    protected static ?string $navigationGroup = 'پذیرش';

    protected static ?string $pluralModelLabel = 'مدیریت آنالیز مشتریان';

    protected static ?string $label = 'آنالیز مشتریان';

    protected static ?string $pluralLabel = 'مدیریت آنالیز مشتریان';

    protected static ?string $singularLabel = 'آنالیزگر';

    protected static ?string $slug = 'customer-analysis';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {

        $customerAnalysis = new CustomerAnalysis(); 
        $controller = new CustomerAnalysisController();
        
        return $form
            ->schema([

                Forms\Components\Radio::make('grant')
                    ->options([0 => 'ندارد', 1 => 'دارد'])
                    ->label('گرنت')
                    ->inline()
                    ->required()
                    ->reactive()
                    ->default(0)
                    ->afterStateUpdated(function ($state, $set, $get) use ($controller) {
                        $controller->handleAfterStateUpdated($state, $set, $get);
                    }),
                    

                    Forms\Components\Radio::make('discount')
                    ->options([
                        0 => 'ندارد',
                        1 => 'درصد',
                        2 => 'مبلغ',
                    ])
                    ->label('تخفیف')
                    ->inline()
                    ->required()
                    ->reactive()
                    ->default(0), 

                    Forms\Components\Select::make('customers_id')
                    ->label('مشتری')
                    ->default(1)
                    ->options(
                        Customers::whereNotNull('name_fa')
                            ->whereNotNull('family_fa')
                            ->get()
                            ->mapWithKeys(function ($customer) {
                                return [$customer->id => $customer->name_fa . ' ' . $customer->family_fa];
                            })
                    )
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        // After customers_id is updated, recalculate the total_cost and applicant_share
                        $customerId = $state;
                
                        $analyzeId = $get('analyze_id');
                        $samplesNumber = $get('samples_number') ?? 1;
                
                        // Try to find a price for the selected analyze_id and customer_id
                        $price = \App\Models\price_analysis_credit::where('customers_id', $customerId)
                            ->where('analyze_id', $analyzeId)
                            ->value('price');
                
                        if ($price === null) {
                            // If no price found in price_analysis_credit, fall back to price_analysis
                            $price = \App\Models\price_analysis::where('analyze_id', $analyzeId)
                                ->value('price');
                        }
                
                        // Calculate total_cost based on the samples_number and price
                        if ($price !== null) {
                            $totalCost = $samplesNumber * $price;
                            $set('total_cost', $totalCost);
                
                            // Recalculate applicant_share based on grant and network_share
                            $grant = $get('grant');
                            $networkShare = $get('network_share') ?? 0;
                
                            if ($grant == 0) {
                                $set('applicant_share', $totalCost);  // If grant is 0, applicant_share = total_cost
                            } elseif ($grant == 1) {
                                $set('applicant_share', max($totalCost - $networkShare, 0));  // Deduct network_share if grant is 1
                            }
                        }
                    }),
                
                    
                Forms\Components\DatePicker::make('acceptance_date')
                    ->label('تاریخ پذیرش')
                    ->required()
                    ->jalali()
                    ->default(now())
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set) {
                        // Call the function to generate tracking code from the controller
                        $trackingCode = (new CustomerAnalysisController())->generateTrackingCode($state);
                        $set('tracking_code', $trackingCode);  
                    }),
                        

                Forms\Components\Select::make('get_answers_id')
                    ->label('نحوه دریافت جواب آنالیز')
                    ->relationship('getAnswers', 'title')
                    ->required(),
                    
                    Forms\Components\Select::make('analyze_id')
                    ->label('آنالیز')
                    ->options(Analyze::all()->pluck('title', 'id'))
                    ->default(1)
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        // Get the selected customer_id
                        $customerId = $get('customers_id'); 
                
                        // Check if customer_id is set, and recalculate total_cost and applicant_share if it changes
                        if ($customerId) {
                            // Try to find a price in price_analysis_credit
                            $price = \App\Models\price_analysis_credit::where('customers_id', $customerId)
                                ->where('analyze_id', $state)
                                ->value('price');
                
                            if ($price === null) {
                                // If no price found in price_analysis_credit, fall back to price_analysis
                                $price = \App\Models\price_analysis::where('analyze_id', $state)
                                    ->value('price');
                            }
                
                            if ($price !== null) {
                                // Calculate total_cost based on samples_number * price
                                $samplesNumber = $get('samples_number') ?? 1; // Default to 1 if samples_number is not set
                                $totalCost = $samplesNumber * $price;
                                $set('total_cost', $totalCost);
                
                                // Update applicant_share based on the grant
                                $grant = $get('grant');
                                $networkShare = $get('network_share') ?? 0;
                
                                if ($grant == 0) {
                                    $set('applicant_share', $totalCost);  // If grant is 0, applicant_share = total_cost
                                } elseif ($grant == 1) {
                                    $set('applicant_share', max($totalCost - $networkShare, 0));  // Deduct network_share if grant is 1
                                }
                            }
                        }
                    }),
                
                
                
                Forms\Components\TextInput::make('samples_number')
                    ->label('تعداد نمونه')
                    ->numeric()
                    ->required()
                    ->maxLength(15)
                    ->mask(mask: 99)
                    ->suffix('عدد')
                    ->default(1)
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        // Get the selected analyze_id and customer_id
                        $analyzeId = $get('analyze_id');
                        $customerId = $get('customers_id');

                        if ($analyzeId && $customerId) {
                            // Fetch the price for the selected analyze_id and customer_id
                            $price = \App\Models\price_analysis_credit::where('customers_id', $customerId)
                                ->where('analyze_id', $analyzeId)
                                ->value('price');

                            // If no price is found in price_analysis_credit, fall back to price_analysis
                            if ($price === null) {
                                $price = \App\Models\price_analysis::where('analyze_id', $analyzeId)
                                    ->value('price');
                            }

                            // Calculate total_cost by multiplying samples_number by the price
                            if ($price !== null) {
                                $totalCost = $state * $price;
                                $set('total_cost', $totalCost);  // Set the total cost based on samples_number * price

                                // Update applicant_share based on the grant
                                $grant = $get('grant');
                                $networkShare = $get('network_share') ?? 0;

                                if ($grant == 0) {
                                    $set('applicant_share', $totalCost);  // If grant is 0, applicant_share = total_cost
                                } elseif ($grant == 1) {
                                    $set('applicant_share', max($totalCost - $networkShare, 0));  // Deduct network_share if grant is 1
                                }
                            }
                        }
                    }),


                Forms\Components\TextInput::make('analyze_time')
                    ->label('کل زمان آنالیز')
                    ->numeric()
                    ->required()
                    ->default($customerAnalysis->analyze_time ?? 0)
                    ->id('analyze_time'),

                Forms\Components\Toggle::make('priority')
                    ->label('اولویت'),

                Forms\Components\Toggle::make('value_added')
                    ->label('ارزش افزوده')
                    ->id('value_added')
                    ->reactive()  // Make it reactive
                    ->afterStateUpdated(function ($state, $set, $get) {
                        // Recalculate total cost and applicant share after updating value_added
                        (new CustomerAnalysisController())->recalculateTotalCostAndApplicantShare($state, $set, $get);
                    }),
                
                Forms\Components\TextInput::make('additional_cost')
                    ->label('هزینه اضافه')
                    ->numeric()
                    ->suffix('ریال')
                    ->id('additional_cost')
                    ->default(0)
                    ->reactive()  // Make it reactive
                    ->afterStateUpdated(function ($state, $set, $get) {
                        // Recalculate total cost and applicant share after updating additional cost
                        (new CustomerAnalysisController())->recalculateTotalCostAndApplicantShare($state, $set, $get);
                    }),
                                
                

                    Forms\Components\TextInput::make('total_cost')
                    ->label('هزینه کل')
                    ->nullable()
                    ->required()
                    ->suffix('ریال')
                    ->reactive()
                    ->readonly()
                    ->extraAttributes(['class' => 'bg-gray-300'])
                    ->afterStateUpdated(function ($state, $set, $get) {
                        // Invoke the total cost calculation and applicant share update
                        (new CustomerAnalysisController())->calculateTotalCostAndApplicantShare($state, $set, $get);
                    }),
                


                Forms\Components\TextInput::make('applicant_share')
                    ->label('سهم متقاضی')
                    ->suffix('ریال')
                    ->required()
                    ->numeric()
                    ->reactive()
                    ->readonly()
                    ->default(function ($get) {
                        // Ensure applicant_share gets the value based on total_cost
                        $totalCost = $get('total_cost');
                
                        // Check if customers_id and analyze_id are set to 1 and 1 respectively
                        if ($get('customers_id') == 1 && $get('analyze_id') == 1) {
                            // Fetch the price for customer 1 and analyze 1
                            $price = \App\Models\price_analysis_credit::where('customers_id', 1)
                                ->where('analyze_id', 1)
                                ->value('price');
                
                            // If no record is found, fall back to price_analysis
                            if (!$price) {
                                $price = \App\Models\price_analysis::where('analyze_id', 1)
                                    ->value('price');
                            }
                
                            return $price ?? 0; // Return price or 0 if not found
                        }
                
                        // Default to total_cost if it's already set
                        return $totalCost;
                    })
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $totalCost = $get('total_cost');
                        $grant = $get('grant');
                        $networkShare = $get('network_share') ?? 0;
                
                        // Ensure applicant_share is updated correctly when grant or total_cost changes
                        if ($grant == 0) {
                            $set('applicant_share', $totalCost); // If grant is 0, applicant_share = total_cost
                        } elseif ($grant == 1) {
                            $set('applicant_share', max($totalCost - $networkShare, 0)); // If grant is 1, deduct network share from total cost
                        }
                    }),


                Forms\Components\TextInput::make('network_share')
                    ->label('سهم شبکه')
                    ->required()
                    ->numeric()
                    ->visible(fn ($state, $get) => $get('grant') == 1) 
                    ->reactive()
                    ->default(0)
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $totalCost = $get('total_cost');
                        $grant = $get('grant');
                        if ($grant == 1) {
                            $set('applicant_share', $totalCost - $state);
                        }
                    }),

                Forms\Components\TextInput::make('network_id')
                    ->label('ID شبکه')
                    ->required()
                    ->default(null)
                    ->visible(fn ($state, $get) => $get('grant') == 1),

                Forms\Components\Select::make('payment_method_id') 
                    ->label('نحوه پرداخت')
                    ->relationship('paymentMethod', 'title')
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $status = ($state == 1) ? 1 : 0;
                        
                        $set('status', $status);
                    }),
                    
                Forms\Components\TextInput::make('discount_num')
                    ->label('درصد تخفیف')
                    ->numeric()
                    ->nullable()
                    ->visible(fn ($state, $get) => $get('discount') == 1)  
                    ->reactive(),

                Forms\Components\TextInput::make('discount_num')
                    ->label('مبلغ تخفیف')
                    ->numeric()
                    ->nullable()
                    ->visible(fn ($state, $get) => $get('discount') == 2) 
                    ->reactive() ,

                Forms\Components\Textarea::make('description')
                    ->label('توضیحات پذیرش'),

                Forms\Components\Hidden::make('status')
                    ->required(),

                Forms\Components\Hidden::make('tracking_code')
                    ->default(fn($get) => (new CustomerAnalysisController)->generateTrackingCode($get('acceptance_date')))
            ]
        );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ردیف'),
                Tables\Columns\TextColumn::make('customer.name_fa')
                    ->label('نام مشتری')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->customer->name_fa . ' ' . $record->customer->family_fa;
                    }),
                
                Tables\Columns\TextColumn::make('acceptance_date')
                    ->label('تاریخ پذیرش')
                    ->dateTime()
                    ->unless(App::isLocale('en'), fn (Tables\Columns\TextColumn $column) => $column->jalaliDate()),
                
                Tables\Columns\TextColumn::make('date_answer')
                    ->label('تاریخ جوابدهی')
                    ->dateTime()
                    ->unless(App::isLocale('en'), fn (Tables\Columns\TextColumn $column) => $column->jalaliDate()),
                
                Tables\Columns\TextColumn::make('analyze.title')
                    ->label('آنالیز'),

                Tables\Columns\TextColumn::make('total_cost')
                    ->label('هزینه کل'),

                Tables\Columns\TextColumn::make('applicant_share')
                    ->label('سهم متقاضی'),

                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت پذیرش')
                    ->formatStateUsing(fn ($state) => CustomerAnalysisController::getStatusLabel($state)),
                    
                Tables\Columns\TextColumn::make('tracking_code')
                    ->label('کد پیگیری'),
                    
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
                Filter::make('status')
                    ->label('وضعیت پذیرش')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('وضعیت')
                            ->options(CustomerAnalysisController::statusOptions()),
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomerAnalyses::route('/'),
            'create' => Pages\CreateCustomerAnalysis::route('/create'),
        ];
    }

    public static function beforeSave($record, array $data): array
    {
        return (new CustomerAnalysisController)->beforeSave(request());
    }
}
