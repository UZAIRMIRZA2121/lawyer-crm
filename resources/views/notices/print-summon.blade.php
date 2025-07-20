<!DOCTYPE html>
<html lang="ur" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>عدالتی سمن</title>
    <style>
        body {
            direction: rtl;
            font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', serif;
            font-size: 20px;
            width: 80%;
            line-height: 1.5;
        }

        .line {
            display: block;

        }

        .signature {
            margin-top: 50px;
            float: left;
        }
    </style>
    <style>
        .underline p {
            display: inline;
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body onload="window.print();">

    <div class="line">بعد از جناب <span class="underline">{{ $data['judge_name'] }} سنئیر سول جج، سول جج، جج فیملی، جج
            گارڈین کنٹرول</span></div>
    <div class="line">مقدمہ نمبر <span class="underline">{{ $data['case_number'] }}</span></div>
    <div class="line">ساکن / رہائشی <span class="underline">{{ $data['plaintiff_address'] }}</span></div>
    <div class="line">مدعی <span class="underline">{{ $data['plaintiff_name'] }}</span></div>
    <div class="line">نام مدعا علیہ / مسئول علیہ <span class="underline">{{ $data['defendant_name'] }}</span></div>
    <div class="line">ولدیت / پتہ <span class="underline">{{ $data['defendant_father_address'] }}</span></div>
    <div class="line">مندرجہ بالا میں مسمیٰ / مسمان <span class="underline">{{ $data['defendant_role'] }}</span></div>



    درخواست دعویٰ عدالت ہٰذا میں برائے <span class="underline"><b>{{ strip_tags($data['notice']) }}</b></span>
    گزاری ہے۔


    <div class="line">
        اور آپ کا برائے جوابد ہی درخواست دعویٰ حاضری عدالت ہونا ضروری ہے۔ لہٰذا آپ کو بذریعہ نوٹس ہٰذا مطلع کیا
        جاتا ہے
        کہ آپ بتاریخ {{ $data['hearing_date'] }} بوقت {{ $data['hearing_time'] ?? '08:00' }} بجے دن، عدالت میں اصالتاً
        یا وکالتاً حاضر ہو کر
        حق دفاع کی درخواست گزاریں۔ اگر کوئی عذر ہو تو پیش کریں۔ بصورتِ عدم حاضری، کارروائی کی جائے گی۔
        (نقل در درخواست دعویٰ لف ہے)۔
    </div>

    <div class="line">
        آج بتاریخ {{ $data['hearing_date'] }} ماہ {{ \Carbon\Carbon::parse($data['hearing_date'])->format('m') }}
، یہ نوٹس ہمارے دستخط اور مہر عدالت سے جاری
        کیا گیا ہے۔
    </div>

    <div class="signature">دستخط</div>

    <div style="clear: both; margin-top: 80px;">مہر عدالت</div>

</body>

</html>
