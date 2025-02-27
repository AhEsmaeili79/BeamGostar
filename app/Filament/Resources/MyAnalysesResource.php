<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MyAnalysesResource\Pages;
use App\Models\CustomerAnalysis;
use App\Models\ReturnRequest;
use App\Models\Customers;
use App\Models\Analyze;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\App;
use Morilog\Jalali\Jalalian;
use Carbon\Carbon;
use App\Models\PaymentAnalyze;
use App\Http\Controllers\PaymentController;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Textarea;

class MyAnalysesResource extends Resource
{
    protected static ?string $model = CustomerAnalysis::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    
    // Move dynamic expressions like this one into methods
    protected static ?string $navigationGroup = 'مشتریان';
    protected static ?string $slug = 'my-analysis';
    
    protected static ?int $navigationSort = 2;

    // Method to set navigation label dynamically
    public static function getNavigationGroup(): string
    {
        return __('filament.labels.customers');  // Make sure you add this translation to the files
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.labels.my_analyses_management');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.labels.my_analyses_management');
    }

    public static function getLabel(): string
    {
        return __('filament.labels.my_analyses');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.labels.my_analyses');
    }

    public static function getSingularLabel(): string
    {
        return __('filament.labels.my_analyses');
    }

    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')->label(__('filament.labels.row'))->disabled(),
                Forms\Components\TextInput::make('customer.name_fa')
                    ->label(__('filament.labels.customer_name'))
                    ->disabled()
                    ->formatStateUsing(fn ($state, $record) => $record->customer->name_fa . ' ' . $record->customer->family_fa),
                Forms\Components\TextInput::make('acceptance_date')
                    ->label(__('filament.labels.acceptance_date'))
                    ->disabled()
                    ->formatStateUsing(fn ($state) => Jalalian::fromCarbon(Carbon::parse($state))->format('Y/m/d')),
                Forms\Components\TextInput::make('analyze.title')
                    ->label(__('filament.labels.analyze'))
                    ->disabled(),
                Forms\Components\TextInput::make('total_cost')
                    ->label(__('filament.labels.total_cost'))
                    ->disabled(),
                Forms\Components\TextInput::make('tracking_code')
                    ->label(__('filament.labels.tracking_code'))
                    ->disabled(),
                Forms\Components\TextInput::make('status')
                    ->label(__('filament.labels.acceptance_status'))
                    ->disabled()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        0 => __('filament.labels.awaiting_payment'),
                        1 => __('filament.labels.full_acceptance'),
                        2 => __('filament.labels.awaiting'),
                        3 => __('filament.labels.canceled'),
                        4 => __('filament.labels.financial_approval'),
                        5 => __('filament.labels.awaiting_technical_management_approval'),
                        6 => __('filament.labels.technical_management_approval'),
                        7 => __('filament.labels.analysis_complete'),
                        8 => __('filament.labels.awaiting_financial_management_approval'),
                        default => __('filament.labels.unknown'),
                    }),
                Forms\Components\TextInput::make('samples_number')
                    ->label(__('filament.labels.samples_number'))
                    ->disabled(),
                Forms\Components\TextInput::make('analyze_time')
                    ->label(__('filament.labels.analysis_time'))
                    ->disabled(),
                Forms\Components\TextInput::make('applicant_share')
                    ->label(__('filament.labels.applicant_share'))
                    ->disabled(),
                Forms\Components\TextInput::make('network_share')
                    ->label(__('filament.labels.network_share'))
                    ->disabled(),
                Forms\Components\TextInput::make('network_id')
                    ->label(__('filament.labels.network_id'))
                    ->disabled(),
                Forms\Components\TextInput::make('payment_method_id')
                    ->label(__('filament.labels.payment_method'))
                    ->disabled(),
                Forms\Components\TextInput::make('description')
                    ->label(__('filament.labels.acceptance_description'))
                    ->disabled(),
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
                TextColumn::make('id')->label(__('filament.labels.row')),
                TextColumn::make('customer.name_fa')
                    ->label(__('filament.labels.customer_name'))
                    ->formatStateUsing(fn ($state, $record) => $record->customer->name_fa . ' ' . $record->customer->family_fa),
                TextColumn::make('acceptance_date')
                    ->label(__('filament.labels.acceptance_date'))
                    ->dateTime()
                    ->unless(App::isLocale('en'), fn (TextColumn $column) => $column->jalaliDate()),
                TextColumn::make('analyze.title')
                    ->label(__('filament.labels.analyze')),
                TextColumn::make('total_cost')
                    ->label(__('filament.labels.total_cost')),
                TextColumn::make('status')
                    ->label(__('filament.labels.acceptance_status'))
                    ->formatStateUsing(fn ($state) => match ($state) {
                        0 => __('filament.labels.awaiting_payment'),
                        1 => __('filament.labels.full_acceptance'),
                        2 => __('filament.labels.awaiting'),
                        3 => __('filament.labels.canceled'),
                        4 => __('filament.labels.financial_approval'),
                        5 => __('filament.labels.awaiting_technical_management_approval'),
                        6 => __('filament.labels.technical_management_approval'),
                        7 => __('filament.labels.analysis_complete'),
                        8 => __('filament.labels.awaiting_financial_management_approval'),
                        default => __('filament.labels.unknown'),
                    }),
                TextColumn::make('tracking_code')
                    ->label(__('filament.labels.tracking_code')),
                
                    Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.labels.created_at'))
                    ->formatStateUsing(fn ($state) => 
                        app()->getLocale() === 'fa' 
                            ? Jalalian::fromDateTime($state)->format('Y/m/d H:i') // Convert to Jalali
                            : Carbon::parse($state)->format('Y-m-d H:i') // Gregorian format
                    )
                    ->sortable(),
            ])
            ->filters([
                Filter::make('status')
                    ->label(__('filament.labels.acceptance_status'))
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label(__('filament.labels.status'))
                            ->options([
                                0 => __('filament.labels.awaiting_payment'),
                                1 => __('filament.labels.full_acceptance'),
                                2 => __('filament.labels.awaiting'),
                                3 => __('filament.labels.canceled'),
                                4 => __('filament.labels.financial_approval'),
                                5 => __('filament.labels.awaiting_technical_management_approval'),
                                6 => __('filament.labels.technical_management_approval'),
                                7 => __('filament.labels.analysis_complete'),
                                8 => __('filament.labels.awaiting_financial_management_approval'),
                            ]),
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('add_payment_details')
                ->label(__('filament.labels.add_payment_details'))
                
                ->modalHeading(__('filament.labels.add_payment_details'))
                ->modalButton(__('filament.labels.save'))
                ->modalWidth('lg')
                ->form([
                    Forms\Components\FileUpload::make('upload_fish')
                        ->label(__('filament.labels.payment_receipt'))
                        ->disk('public')
                        ->directory('payment_receipts')
                        ->required(),
                    Forms\Components\TextInput::make('transaction_id')
                        ->label(__('filament.labels.transaction_id'))
                        ->required(),
                    Forms\Components\DateTimePicker::make('datepay')
                        ->label(__('filament.labels.payment_date'))
                        ->jalali()
                        ->required(),
                ])
                ->action(function (array $data, $record) {
                    PaymentAnalyze::create([
                        'customer_analysis_id' => $record->id,
                        'upload_fish' => $data['upload_fish'],
                        'transaction_id' => $data['transaction_id'],
                        'datepay' => $data['datepay'],
                    ]);
                }),
        
                Tables\Actions\Action::make('pay_now')
                    ->label(__('filament.labels.pay_now'))
                    ->icon('heroicon-o-currency-dollar')
                    ->action(function ($record) {
                        // Redirect to the specified URL
                        return redirect('https://zarinp.al/beamgostartabanlab');
                    }),


                Tables\Actions\Action::make('print_invoice')
                    ->label(__('filament.labels.print_invoice'))
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('invoice.print', ['id' => $record->id]))
                    ->openUrlInNewTab(),
                
                Tables\Actions\Action::make('download_pdf')
                ->label(__('filament.labels.download_result'))
                ->url(fn ($record) => route('analysis.download', ['id' => $record->id]))
                ->openUrlInNewTab(),
                
                Tables\Actions\Action::make('request_return')
                ->label('عودت')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('درخواست عودت')
                ->modalSubheading('آیا مطمئن هستید که می‌خواهید درخواست عودت دهید؟ این عملیات غیرقابل برگشت است.')
                ->modalButton('تایید')
                ->form([
                    Forms\Components\Textarea::make('reason')
                        ->label('علت عودت')
                        ->required()
                        ->rows(3),
                ])
                ->action(function (array $data, $record) {
                    // Check if a ReturnRequest with the same tracking_code already exists
                    $existingRequest = ReturnRequest::where('tracking_code', $record->tracking_code)->first();
                    $statusTranslations = [
                        'requested' => 'در انتظار',
                        'canceled' => 'لغو شده',
                        'ready_for_return' => 'آماده جهت عودت',
                        'returned' => 'عودت شده',
                    ];
                    if ($existingRequest) {
                        // If a ReturnRequest exists, show the status instead of the action button
                        $status = $statusTranslations[$existingRequest->status] ?? $existingRequest->status; // Use translation if available
                        return Notification::make()
                            ->title('درخواست عودت قبلاً ثبت شده است')
                            ->body('وضعیت: ' . $status)
                            ->success()
                            ->send();
                    } else {
                        // If no existing request, create a new one
                        $record->returnRequest()->create([
                            'reason' => $data['reason'],
                            'tracking_code' => $record->tracking_code,
                            'requested_at' => now(),
                        ]);
                        
                        Notification::make()
                            ->title('درخواست عودت با موفقیت ثبت شد')
                            ->success()
                            ->send();
                    }
                }),
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
            'index' => Pages\ListMyAnalyses::route('/'),
        ];
    }
}
