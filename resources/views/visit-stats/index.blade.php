@extends('layouts.app')

@section('title', 'إحصائيات الزيارات')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <h1>إحصائيات الزيارات</h1>
                <p class="text-muted">تتبع زيارات الموقع والإحصائيات التفصيلية</p>
            </div>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ number_format($totalVisits) }}</h3>
                    <p>إجمالي الزيارات</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ number_format($visitsToday) }}</h3>
                    <p>زيارات اليوم</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="fas fa-calendar-week"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ number_format($visitsThisWeek) }}</h3>
                    <p>زيارات هذا الأسبوع</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ number_format($visitsThisMonth) }}</h3>
                    <p>زيارات هذا الشهر</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- الزيارات حسب الدولة -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-globe me-2"></i>الزيارات حسب الدولة</h5>
                </div>
                <div class="card-body">
                    @if($visitsByCountry->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>الدولة</th>
                                        <th>عدد الزيارات</th>
                                        <th>النسبة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($visitsByCountry as $visit)
                                        <tr>
                                            <td>{{ $visit->country }}</td>
                                            <td>{{ number_format($visit->count) }}</td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar" role="progressbar" 
                                                         style="width: {{ ($visit->count / $totalVisits) * 100 }}%"
                                                         aria-valuenow="{{ $visit->count }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="{{ $totalVisits }}">
                                                        {{ round(($visit->count / $totalVisits) * 100, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">لا توجد بيانات زيارات متاحة</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- الزيارات حسب المدينة -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-map-marker-alt me-2"></i>الزيارات حسب المدينة</h5>
                </div>
                <div class="card-body">
                    @if($visitsByCity->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>المدينة</th>
                                        <th>عدد الزيارات</th>
                                        <th>النسبة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($visitsByCity as $visit)
                                        <tr>
                                            <td>{{ $visit->city }}</td>
                                            <td>{{ number_format($visit->count) }}</td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-success" role="progressbar" 
                                                         style="width: {{ ($visit->count / $totalVisits) * 100 }}%"
                                                         aria-valuenow="{{ $visit->count }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="{{ $totalVisits }}">
                                                        {{ round(($visit->count / $totalVisits) * 100, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">لا توجد بيانات زيارات متاحة</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- الزيارات الأخيرة -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-history me-2"></i>الزيارات الأخيرة</h5>
                </div>
                <div class="card-body">
                    @if($recentVisits->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الدولة</th>
                                        <th>المدينة</th>
                                        <th>تاريخ الزيارة</th>
                                        <th>وقت الزيارة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentVisits as $index => $visit)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $visit->country }}</td>
                                            <td>{{ $visit->city }}</td>
                                            <td>{{ $visit->created_at->format('Y-m-d') }}</td>
                                            <td>{{ $visit->created_at->format('H:i:s') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">لا توجد زيارات مسجلة بعد</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stats-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    transition: transform 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: 1rem;
}

.stats-icon i {
    color: white;
    font-size: 1.5rem;
}

.stats-content h3 {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

.stats-content p {
    color: #6c757d;
    margin: 0;
    font-size: 0.9rem;
}

.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px 15px 0 0 !important;
    border: none;
}

.card-header h5 {
    margin: 0;
    font-weight: 600;
}

.progress {
    border-radius: 10px;
    background-color: #e9ecef;
}

.progress-bar {
    border-radius: 10px;
    font-size: 0.75rem;
    font-weight: 600;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.table td {
    vertical-align: middle;
}
</style>
@endsection
