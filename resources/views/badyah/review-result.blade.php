<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
<meta charset="utf-8">
<title>نتيجة المراجعة — البادية الذكية</title>
<style>
    body { font-family: Tahoma, Arial, sans-serif; background:#f4f5f7; display:flex; align-items:center; justify-content:center; height:100vh; margin:0; }
    .box { background:#fff; border-radius:10px; padding:28px 32px; max-width:420px; text-align:center; box-shadow:0 1px 3px rgba(0,0,0,.08); }
    h1 { font-size:18px; color:#0b1f3a; }
    p { color:#475467; font-size:14px; }
    .status { display:inline-block; margin-top:8px; padding:4px 12px; border-radius:14px; font-size:13px; font-weight:bold; }
    .approved { background:#ecfdf3; color:#027a48; }
    .needs_review { background:#fff4e5; color:#9a3412; }
    .rejected { background:#fef3f2; color:#b42318; }
</style>
</head>
<body>
    <div class="box">
        @if($alreadyDecided)
            <h1>تم اتخاذ قرار على هذا السجل مسبقًا</h1>
            <p>السجل #{{ $record->id }} — {{ $record->name_ar }}</p>
            <span class="status {{ $record->verification_status }}">{{ $record->verification_status }}</span>
        @else
            <h1>تم تسجيل قرارك بنجاح</h1>
            <p>السجل #{{ $record->id }} — {{ $record->name_ar }}</p>
            <span class="status {{ $decision }}">{{ $decision }}</span>
        @endif
    </div>
</body>
</html>
