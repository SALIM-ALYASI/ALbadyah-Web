<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
<meta charset="utf-8">
<title>تأكيد القرار — البادية الذكية</title>
<style>
    body { font-family: Tahoma, Arial, sans-serif; background:#f4f5f7; margin:0; padding:24px; }
    .box { background:#fff; border-radius:10px; padding:28px 32px; max-width:480px; margin:0 auto; box-shadow:0 1px 3px rgba(0,0,0,.08); }
    h1 { font-size:18px; color:#0b1f3a; margin-top:0; }
    .warn-banner { background:#fff4e5; color:#9a3412; padding:10px 14px; border-radius:6px; font-size:13px; margin-bottom:18px; }
    table { width:100%; border-collapse:collapse; font-size:14px; margin-bottom:18px; }
    table td { padding:6px 4px; border-bottom:1px solid #f0f1f3; vertical-align:top; }
    td.label { color:#667085; width:130px; white-space:nowrap; }
    .intent { display:inline-block; padding:4px 12px; border-radius:14px; font-weight:bold; font-size:13px; }
    .approved { background:#ecfdf3; color:#027a48; }
    .needs_review { background:#fff4e5; color:#9a3412; }
    .rejected { background:#fef3f2; color:#b42318; }
    label.field { display:block; font-size:13px; color:#344054; margin:14px 0 4px; }
    textarea { width:100%; box-sizing:border-box; border:1px solid #d0d5dd; border-radius:6px; padding:8px; font-family:inherit; font-size:13px; }
    .checkbox-row { display:flex; align-items:flex-start; gap:8px; margin-top:14px; font-size:13px; color:#344054; }
    .expiry { color:#98a2b3; font-size:12px; margin-top:16px; }
    button.confirm { margin-top:18px; width:100%; padding:12px; border:0; border-radius:6px; background:#0b1f3a; color:#fff; font-size:15px; font-weight:bold; cursor:pointer; }
    .source-link { color:#175cd3; word-break:break-all; font-size:13px; }
</style>
</head>
<body>
<div class="box">
    <h1>تأكيد قرار المراجعة</h1>
    <div class="warn-banner">فتح هذه الصفحة لم يغيّر أي شيء بعد. لتطبيق القرار فعليًا، لازم تضغط زر التأكيد بالأسفل.</div>

    <table>
        <tr><td class="label">record_id</td><td>#{{ $record->id }}</td></tr>
        <tr><td class="label">الاسم</td><td>{{ $record->name_ar }} <span style="color:#98a2b3">/ {{ $record->name_en }}</span></td></tr>
        <tr><td class="label">القرار المقترح</td><td><span class="intent {{ $intent }}">{{ $intent }}</span></td></tr>
        <tr><td class="label">المصدر</td><td><a class="source-link" href="{{ $record->source_url }}">{{ $record->source_url }}</a></td></tr>
    </table>

    <form method="POST" action="{{ $confirmUrl }}">
        @csrf

        @if ($record->name_ar_source !== 'official')
            <div class="checkbox-row">
                <input type="checkbox" id="confirm_arabic_name" name="confirm_arabic_name" value="1">
                <label for="confirm_arabic_name">أؤكد أنني تحققت من المصدر، والاسم العربي أعلاه رسمي ومطابق له (وليس مجرد ترجمة AI). لن يُعتمد هذا تلقائيًا لمجرد اعتماد السجل.</label>
            </div>
        @endif

        <label class="field" for="note">ملاحظة (اختياري)</label>
        <textarea id="note" name="note" rows="3" placeholder="أي ملاحظة تريد إضافتها على هذا القرار..."></textarea>

        <button type="submit" class="confirm">تأكيد القرار: {{ $intent }}</button>
    </form>

    <p class="expiry">هذا الرابط صالح حتى {{ $expiresAt->format('Y-m-d H:i') }} (بتوقيت السيرفر). بعدها لن يعمل.</p>
</div>
</body>
</html>
