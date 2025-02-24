<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body { font-family: 'Vazir',Arial, sans-serif; direction: rtl; text-align: right; }
        .container { width: 100%; padding: 20px; }
        .header { font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 10px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">فاکتور پرداخت</div>
        <p>نام مشتری: {{ $customerAnalysis->customer->name_fa }} {{ $customerAnalysis->customer->family_fa }}</p>
        <p>کد پیگیری: {{ $customerAnalysis->tracking_code }}</p>
        <p>مبلغ کل: {{ number_format($customerAnalysis->total_cost) }} تومان</p>
        <table>
            <thead>
                <tr>
                    <th>عنوان تحلیل</th>
                    <th>تعداد نمونه</th>
                    <th>وضعیت</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $customerAnalysis->analyze->title }}</td>
                    <td>{{ $customerAnalysis->samples_number }}</td>
                    <td>{{ __('filament.labels.acceptance_status') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
