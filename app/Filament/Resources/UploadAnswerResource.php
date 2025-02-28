<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UploadAnswerResource\Pages;
use App\Filament\Resources\UploadAnswerResource\RelationManagers;
use App\Models\UploadAnswer;
use App\Models\CustomerAnalysis;
use App\Models\Customers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Modal;

class UploadAnswerResource extends Resource
{
    protected static ?string $model = CustomerAnalysis::class;
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('status', 0);
    }
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationLabel = 'مدیریت نتیجه آنالیز مشتریان';

    protected static ?string $navigationGroup = 'چوابدهی';

    protected static ?string $pluralModelLabel = 'مدیریت نتیجه آنالیز مشتریان';

    protected static ?string $label = 'آنالیز مشتریان';

    protected static ?string $pluralLabel = 'مدیریت نتیجه آنالیز مشتریان';

    protected static ?string $singularLabel = 'آنالیزگر';

    protected static ?string $slug = 'customer-analysis';

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
            Forms\Components\Select::make('payment_method_id')
                ->label(__('filament.labels.payment_method'))
                ->relationship('paymentMethod', 'title')
                ->required(),
            Forms\Components\Toggle::make('discount')
                ->label(__('filament.labels.discount')),
            Forms\Components\TextInput::make('discount_num')
                ->label(__('filament.labels.discount_amount')),
            Forms\Components\FileUpload::make('scan_form')
                ->label(__('filament.labels.scan_form'))
                ->directory('uploads/scan_forms') // Save files to a specific directory
                ->required(),
            Forms\Components\Textarea::make('description')
                ->label(__('filament.labels.acceptance_description')),
            Forms\Components\Toggle::make('priority')
                ->label(__('filament.labels.priority')),
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
                ->dateTime()
                ->unless(App::isLocale('en'), fn (Tables\Columns\TextColumn $column) => $column->jalaliDate()),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Action::make('upload_result')
                    ->label(fn ($record) => UploadAnswer::where('customer_analysis_id', $record->id)->exists() 
                        ? __('filament.labels.update_result') 
                        : __('filament.labels.upload_result')) // Change label dynamically
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        FileUpload::make('result')
                            ->label(__('filament.labels.upload_result'))
                            ->acceptedFileTypes(['application/pdf', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                            ->directory('uploads/results') // Auto-saves file
                            ->default(fn ($record) => UploadAnswer::where('customer_analysis_id', $record->id)->value('result')) // Show existing file
                            ->disk('public')
                            ->required(),
                    ])
                    ->modalHeading(__('filament.labels.upload_result_modal')) // Persian title
                    ->modalButton(__('filament.labels.save_result')) // Persian button
                    ->action(function ($record, $data) {
                        // Find or create an UploadAnswer record
                        UploadAnswer::updateOrCreate(
                            ['customer_analysis_id' => $record->id], // Find by customer_analysis_id
                            ['result' => $data['result']] // Save or update result file
                        );
                    }),

                // Download Action (This will show if a file exists)
                Action::make('download_result')
                    ->label(__('filament.labels.download_result'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn ($record) => UploadAnswer::where('customer_analysis_id', $record->id)->exists() 
                        ? asset('storage/' . UploadAnswer::where('customer_analysis_id', $record->id)->value('result')) 
                        : '#')
                    ->color('success')
                    ->visible(fn ($record) => UploadAnswer::where('customer_analysis_id', $record->id)->exists()) // Only visible if file exists

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
            'index' => Pages\ListUploadAnswers::route('/'),
        ];
    }
}
