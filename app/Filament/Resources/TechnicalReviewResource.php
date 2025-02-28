<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TechnicalReviewResource\Pages;
use App\Models\CustomerAnalysis;
use App\Models\TechnicalReview;
use App\Models\Customers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Morilog\Jalali\Jalalian;
class TechnicalReviewResource extends Resource
{
    protected static ?string $model = CustomerAnalysis::class;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('status', 5);
    }

    protected static ?string $label = 'تایید فنی ';

    protected static ?string $navigationGroup = 'مدیریت فنی';

    protected static ?string $navigationLabel = 'مدیریت فنی آنالیز مشتریان';

    protected static ?string $pluralLabel = 'مدیریت فنی آنالیز مشتریان';

    protected static ?string $singularLabel = 'مدیریت فنی آنالیز مشتریان';

    protected static ?string $navigationIcon = 'heroicon-o-wrench';

   

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('customers_id')
                ->label(__('filament.labels.customer'))
                ->options(
                    Customers::whereNotNull('name_fa')
                        ->whereNotNull('family_fa')
                        ->get()
                        ->mapWithKeys(function ($customer) {
                            return [$customer->id => $customer->name_fa . ' ' . $customer->family_fa];
                        })
                )
                ->required()
                ->searchable(),
            Forms\Components\DatePicker::make('acceptance_date')
                ->label(__('filament.labels.acceptance_date'))
                ->required(),
            Forms\Components\Select::make('get_answers_id')
                ->label(__('filament.labels.answer_method'))
                ->relationship('getAnswers', 'title')
                ->required(),
            Forms\Components\Select::make('analyze_id')
                ->label(__('filament.labels.analysis'))
                ->relationship('analyze', 'title')
                ->required(),
            Forms\Components\TextInput::make('samples_number')
                ->label(__('filament.labels.sample_number'))
                ->numeric()
                ->required(),
            Forms\Components\TextInput::make('analyze_time')
                ->label(__('filament.labels.analysis_time'))
                ->required(),
            Forms\Components\Toggle::make('value_added')
                ->label(__('filament.labels.value_added')),
            Forms\Components\Toggle::make('grant')
                ->label(__('filament.labels.grant')),
            Forms\Components\TextInput::make('additional_cost')
                ->label(__('filament.labels.additional_cost')),
            Forms\Components\TextInput::make('additional_cost_text')
                ->label(__('filament.labels.additional_cost_description')),
            Forms\Components\TextInput::make('total_cost')
                ->label(__('filament.labels.total_cost'))
                ->required(),
            Forms\Components\TextInput::make('applicant_share')
                ->label(__('filament.labels.applicant_share'))
                ->required(),
            Forms\Components\TextInput::make('network_share')
                ->label(__('filament.labels.network_share'))
                ->required(),
            Forms\Components\TextInput::make('network_id')
                ->label(__('filament.labels.network_id'))
                ->required(),
            
            Forms\Components\Toggle::make('discount')
                ->label(__('filament.labels.discount')),
            Forms\Components\Toggle::make('priority')
                ->label(__('filament.labels.priority')),
            Forms\Components\Select::make('payment_method_id')
                ->label(__('filament.labels.payment_method'))
                ->relationship('paymentMethod', 'title')
                ->required(),
            Forms\Components\TextInput::make('discount_num')
                ->label(__('filament.labels.discount_amount')),
            Forms\Components\Textarea::make('description')
                ->label(__('filament.labels.acceptance_description')),
            Forms\Components\Select::make('status')
                ->label(__('filament.labels.status'))
                ->options([
                    0 => __('filament.labels.waiting_for_payment'),
                    1 => __('filament.labels.full_acceptance'),
                    2 => __('filament.labels.pending'),
                    3 => __('filament.labels.canceled'),
                    4 => __('filament.labels.financial_approval'),
                    5 => __('filament.labels.waiting_for_technical_approval'),
                    8 => __('filament.labels.waiting_for_financial_approval'),
                ])
                ->required(),
            Forms\Components\TextInput::make('tracking_code')
                ->label(__('filament.labels.tracking_code')),
            Forms\Components\DatePicker::make('date_answer')
                ->label(__('filament.labels.answer_date')),
            Forms\Components\DatePicker::make('upload_answer')
                ->label(__('filament.labels.upload_date')),
            Forms\Components\FileUpload::make('scan_form')
                ->label(__('filament.labels.scan_form'))
                ->directory('uploads/scan_forms') // Save files to a specific directory
                ->required(),
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
                    ->label(__('filament.labels.row')),

                Tables\Columns\TextColumn::make('customer.name_fa')
                    ->label(__('filament.labels.customer'))
                    ->formatStateUsing(function ($state, $record) {
                        return $record->customer->name_fa . ' ' . $record->customer->family_fa;
                    }),

                Tables\Columns\TextColumn::make('analyze.title')
                    ->label(__('filament.labels.analysis')),

                Tables\Columns\TextColumn::make('total_cost')
                    ->label(__('filament.labels.total_cost')),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament.labels.status'))
                    ->formatStateUsing(fn ($state) => match ($state) {
                        0 => __('filament.labels.waiting_for_payment'),
                        1 => __('filament.labels.full_acceptance'),
                        2 => __('filament.labels.pending'),
                        3 => __('filament.labels.canceled'),
                        4 => __('filament.labels.financial_approval'),
                        5 => __('filament.labels.waiting_for_technical_approval'),
                        8 => __('filament.labels.waiting_for_financial_approval'),
                        default => __('filament.labels.unknown'),
                    }),

                Tables\Columns\TextColumn::make('tracking_code')
                    ->label(__('filament.labels.tracking_code')),

                // Tables\Columns\TextColumn::make('financialCheck.scan_form')
                //     ->label('دانلود فیش')
                //     ->formatStateUsing(fn ($state) => $state ? '<a href="' . asset("storage/$state") . '" target="_blank">دانلود</a>' : 'ندارد')
                //     ->html(),

                Tables\Columns\TextColumn::make('acceptance_date')
                    ->label(__('filament.labels.acceptance_date'))
                    ->formatStateUsing(fn ($state) => 
                        app()->getLocale() === 'fa' 
                            ? Jalalian::fromDateTime($state)->format('Y/m/d H:i') // Convert to Jalali
                            : \Carbon\Carbon::parse($state)->format('Y-m-d H:i') // Gregorian format
                ),
                
                BadgeColumn::make('technicalReview.state')
                    ->label('وضعیت')
                    ->getStateUsing(function ($record) {
                        $technicalReview = $record->technicalReview;
                        return $technicalReview ? ($technicalReview->state == 1 ? 'تایید' : 'عدم تایید') : 'بدون تایید';
                    })
                    ->color(function ($state) {
                        return $state == 'تایید' ? 'success' : 'danger';
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('approve_or_deny')
                    ->label('تایید/عدم تایید')
                    ->icon('heroicon-o-check-circle')
                    ->modalHeading('انتخاب وضعیت تایید')
                    ->modalButton('ثبت تغییرات')
                    ->action(function ($record, $data) {
                        // Find or create the TechnicalReview entry for this CustomerAnalysis record
                        $technicalReview = TechnicalReview::firstOrNew([
                            'customer_analysis_id' => $record->id,  // Ensure linkage to CustomerAnalysis
                            'analyze_id' => $record->analyze_id,    // Ensure linkage to Analyze
                        ]);

                        // Update the TechnicalReview state and text field
                        $technicalReview->state = $data['state'];
                        $technicalReview->text = $data['text'] ?? null; // Save optional explanation
                        $technicalReview->save();

                        // Update the CustomerAnalysis status based on the selected state
                        $newStatus = $data['state'] == 1 ? 6 : 4; // 6 for approval, 4 for denial
                        $record->update(['status' => $newStatus]);

                        // Show a notification based on approval or denial
                        \Filament\Notifications\Notification::make()
                            ->title($data['state'] == 1 ? 'تایید شد' : 'عدم تایید')
                            ->body($data['state'] == 1 
                                ? 'با موفقیت به وضعیت "تایید شده" تغییر یافت.' 
                                : 'با موفقیت به وضعیت "عدم تایید" تغییر یافت.')
                            ->success()
                            ->send();
                    })
                    ->form([
                        Forms\Components\Radio::make('state')
                            ->label('وضعیت تایید')
                            ->options([
                                1 => 'تایید',
                                0 => 'عدم تایید',
                            ])
                            ->required(),

                        Forms\Components\Textarea::make('text')
                            ->label('توضیحات')
                            ->placeholder('دلایل تایید یا عدم تایید را بنویسید')
                            ->rows(4)
                            ->nullable(),
                            ]),


            ])
            ->bulkActions([
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
            'index' => Pages\ListTechnicalReviews::route('/'),
        ];
    }
}
