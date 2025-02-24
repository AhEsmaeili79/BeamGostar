<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analysis Result</title>
    <style>
        body { font-family: Arial, sans-serif; direction: rtl; text-align: right; }
        .container { width: 100%; padding: 20px; }
        .header { font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 20px; }
        .content { margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">نتیجه تحلیل</div>
        <p>نام مشتری: {{ $customerAnalysis->customer->name_fa }} {{ $customerAnalysis->customer->family_fa }}</p>
        <p>عنوان تحلیل: {{ $customerAnalysis->analyze->title }}</p>
        <div class="content">
            <p>توضیحات:</p>
            <p>{{ $customerAnalysis->description }}</p>
        </div>
    </div>
</body>
</html>
