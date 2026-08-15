<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
<meta charset="utf-8">
<style>
    body { font-family: 'Tahoma', Arial, sans-serif; background:#f4f5f7; margin:0; padding:0; color:#1f2430; }
    .wrap { max-width: 680px; margin: 0 auto; padding: 24px 16px; }
    h1 { font-size: 18px; color:#0b1f3a; }
    .meta { color:#667085; font-size:13px; margin-bottom:24px; }
    .card { background:#fff; border:1px solid #e4e7ec; border-radius:8px; padding:16px; margin-bottom:18px; }
    .card h2 { font-size:16px; margin:0 0 4px; color:#0b1f3a; }
    .card .en { color:#667085; font-size:13px; margin-bottom:10px; }
    table.fields { width:100%; border-collapse: collapse; font-size:13px; }
    table.fields td { padding:4px 6px; vertical-align:top; border-bottom:1px solid #f0f1f3; }
    table.fields td.label { color:#667085; width:150px; white-space:nowrap; }
    .badge { display:inline-block; padding:2px 8px; border-radius:12px; font-size:11px; background:#eef2ff; color:#3730a3; }
    .badge.warn { background:#fff4e5; color:#9a3412; }
    .badge.ok { background:#ecfdf3; color:#027a48; }
    .actions { margin-top:12px; }
    .actions a { display:inline-block; padding:8px 14px; margin-inline-end:8px; border-radius:6px; font-size:13px; text-decoration:none; font-weight:bold; }
    .approve { background:#027a48; color:#fff; }
    .review { background:#b54708; color:#fff; }
    .reject { background:#b42318; color:#fff; }
    .source-link { color:#175cd3; word-break: break-all; }
    .footer { color:#98a2b3; font-size:12px; margin-top:16px; }
</style>
</head>
<body>
<div class="wrap">
    <h1>مراجعة بيانات محرك البادية الذكي — {{ $batchLabel }}</h1>
    <p class="meta">دفعة {{ $batchNumber }} من {{ $totalBatches }} — {{ count($rows) }} سجل بانتظار قرارك. كل سجل لا يزال بحالة Pending ولم يُنشر.</p>

    @foreach ($rows as $row)
        <div class="card">
            <h2>#{{ $row['record_id'] }} — {{ $row['name_ar'] }}</h2>
            <div class="en">{{ $row['name_en'] }}</div>

            <table class="fields">
                <tr><td class="label">التصنيف</td><td>{{ $row['category'] }}</td></tr>
                <tr><td class="label">المحافظة / الولاية</td><td>{{ $row['governorate'] }} / {{ $row['wilayat'] ?? '— (needs_review)' }}</td></tr>
                <tr><td class="label">الإحداثيات</td><td>{{ $row['coordinates'] ?? '— (needs_review)' }}</td></tr>
                <tr><td class="label">المصدر</td><td><a class="source-link" href="{{ $row['source_url'] }}">{{ $row['source_url'] }}</a></td></tr>
                <tr><td class="label">source_trust_score</td><td>{{ $row['source_trust_score'] }} <span style="color:#98a2b3">(المصدر رسمي وموثوق — لا يعني صحة كل حقل)</span></td></tr>
                <tr><td class="label">ai_confidence</td><td>{{ $row['ai_confidence'] }}</td></tr>
                <tr><td class="label">الاسم العربي</td>
                    <td>
                        @if ($row['name_ar_source'] === 'official')
                            <span class="badge ok">official — من المصدر مباشرة</span>
                        @else
                            <span class="badge warn">AI translation — يحتاج تأكيد</span>
                        @endif
                    </td>
                </tr>
                <tr><td class="label">الوصف العربي</td>
                    <td>
                        @if ($row['description_ar_generated'])
                            <span class="badge warn">AI generated</span>
                        @else
                            <span class="badge ok">نص رسمي</span>
                        @endif
                    </td>
                </tr>
                <tr><td class="label">needs_review_fields</td>
                    <td>{{ $row['needs_review_fields'] ? implode('، ', $row['needs_review_fields']) : '—' }}</td>
                </tr>
                <tr><td class="label">collected_at</td><td>{{ $row['collected_at'] }}</td></tr>
            </table>

            <div class="actions">
                <a class="approve" href="{{ $row['approve_url'] }}">✅ Approved</a>
                <a class="review" href="{{ $row['needs_review_url'] }}">🟠 Needs Review</a>
                <a class="reject" href="{{ $row['reject_url'] }}">⛔ Rejected</a>
            </div>
        </div>
    @endforeach

    <p class="footer">محرك البادية الذكي — al-badyah.com. كل رابط أعلاه موقّع (signed) ويحدّث السجل بمعرّفه (record_id) نفسه فقط، ولا يُنشئ سجلًا جديدًا.</p>
</div>
</body>
</html>
