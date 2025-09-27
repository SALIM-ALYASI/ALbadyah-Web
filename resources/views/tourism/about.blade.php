@extends('layouts.tourism')

@section('title', 'من نحن - البادية')
@section('description', 'تعرف على البادية - منصة السياحة في سلطنة عُمان')

@section('content')
<!-- Hero Section -->
<section class="hero-section" style="min-height: 60vh;">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-10">
                <div class="hero-content fade-in-up">
                    <div class="about-logo-container mb-4">
                        <img src="{{ asset('images/loogo.png') }}" alt="البادية" class="about-logo-img">
                    </div>
                    <h1 class="hero-title">مرحباً بك في البادية</h1>
                    <p class="hero-subtitle">منصة البادية للسياحة في سلطنة عُمان - جسر يربط بين التراث العريق والطبيعة الساحرة</p>
                    
                    <!-- Statistics in Hero -->
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-number">{{ $stats['total_governorates'] }}</div>
                            <div class="stat-label">محافظة</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $stats['total_wilayats'] }}</div>
                            <div class="stat-label">ولاية</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $stats['total_tourist_sites'] }}</div>
                            <div class="stat-label">موقع سياحي</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $stats['total_tourist_services'] }}</div>
                            <div class="stat-label">خدمة سياحية</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Content -->
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="content-card">
                    <h2 class="section-title">من نحن</h2>
                    <p class="lead">
                        البادية هي منصة سياحية شاملة مخصصة لعرض جمال سلطنة عُمان وثرائها الثقافي والطبيعي. 
                        من الجبال الشامخة إلى السواحل الذهبية، من الصحراء الذهبية إلى الواحات الخضراء، 
                        نسعى لتقديم تجربة سياحية فريدة تساعد الزوار على اكتشاف أجمل الأماكن في السلطنة.
                    </p>
                    
                    <h3 class="mt-4 mb-3">رؤيتنا</h3>
                    <p>
                        أن نكون المنصة السياحية الأولى في سلطنة عُمان التي تربط بين الزوار والوجهات السياحية المذهلة، 
                        ونقدم معلومات شاملة ودقيقة عن جميع المحافظات والمواقع السياحية والخدمات المتاحة.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="content-card">
                    <h2 class="section-title">مهمتنا</h2>
                    <p class="mb-4">
                        نسعى لتسهيل عملية التخطيط للرحلات السياحية من خلال توفير معلومات موثوقة وشاملة عن:
                    </p>
                    <ul class="mission-list">
                        <li class="mission-item">
                            <div class="mission-icon">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <div class="mission-text">
                                <h5>جميع المحافظات والولايات</h5>
                                <p>معلومات شاملة عن جميع محافظات وولايات سلطنة عُمان</p>
                            </div>
                        </li>
                        <li class="mission-item">
                            <div class="mission-icon">
                                <i class="fas fa-camera"></i>
                            </div>
                            <div class="mission-text">
                                <h5>المواقع السياحية الرائعة</h5>
                                <p>صور وأوصاف تفصيلية لأجمل المواقع السياحية</p>
                            </div>
                        </li>
                        <li class="mission-item">
                            <div class="mission-icon">
                                <i class="fas fa-concierge-bell"></i>
                            </div>
                            <div class="mission-text">
                                <h5>الخدمات السياحية المتميزة</h5>
                                <p>فنادق ومطاعم وخدمات سياحية عالية الجودة</p>
                            </div>
                        </li>
                        <li class="mission-item">
                            <div class="mission-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="mission-text">
                                <h5>معلومات دقيقة ومحدثة</h5>
                                <p>معلومات موثوقة ومحدثة باستمرار عن كل وجهة</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">لماذا البادية؟</h2>
                <p class="section-subtitle">المميزات التي تجعلنا الخيار الأفضل لك</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h5>شامل ومتكامل</h5>
                    <p>نغطي جميع محافظات وولايات السلطنة مع معلومات مفصلة عن كل منطقة</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-images"></i>
                    </div>
                    <h5>صور عالية الجودة</h5>
                    <p>نقدم صوراً رائعة للمواقع السياحية تساعدك على اختيار وجهتك المثالية</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h5>بحث متقدم</h5>
                    <p>يمكنك البحث والفلترة بسهولة للعثور على ما تبحث عنه</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h5>متجاوب مع الأجهزة</h5>
                    <p>يمكنك تصفح الموقع بسهولة من أي جهاز - هاتف، تابلت، أو كمبيوتر</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">قيمنا</h2>
                <p class="section-subtitle">المبادئ التي نؤمن بها في البادية</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5>الموثوقية</h5>
                    <p>نقدم معلومات دقيقة وموثوقة من مصادر رسمية ومحلية</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h5>الشغف</h5>
                    <p>نحن شغوفون بجمال عُمان ونسعى لنقل هذا الجمال للزوار</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h5>الشمولية</h5>
                    <p>نضمن وصول المعلومات لجميع الزوار بسهولة ووضوح</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="section bg-light">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h3>ابدأ رحلتك السياحية الآن</h3>
                <p>اكتشف أجمل الأماكن في سلطنة عُمان واستمتع بتجربة سياحية لا تُنسى</p>
                <div class="cta-buttons">
                    <a href="{{ route('tourism.tourist-sites') }}" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-camera me-2"></i>استكشف المواقع السياحية
                    </a>
                    <a href="{{ route('tourism.governorates') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-building me-2"></i>تصفح المحافظات
                    </a>
                </div>
                
                <!-- رابط مخفي للوحة التحكم -->
                <div style="position: absolute; top: 0; left: 0; width: 1px; height: 1px; opacity: 0; overflow: hidden; pointer-events: none;">
                    <a href="/admin/login" style="color: transparent; text-decoration: none;">Admin Panel</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(rgba(97, 76, 57, 0.7), rgba(161, 129, 90, 0.6), rgba(222, 180, 122, 0.5)),
        url('{{ asset("images/albadyah.jpg") }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }
    
    /* Hero Section */
    .hero-title {
        font-size: 3rem;
        font-weight: 700;
        color: white;
        margin-bottom: 1.5rem;
        text-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }
    
    .hero-subtitle {
        font-size: 1.3rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 2rem;
        line-height: 1.6;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    /* Hero Stats */
    .hero-stats {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 2rem;
        margin: 2rem auto;
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 20px;
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        max-width: 800px;
        flex-wrap: wrap;
    }
    
    .hero-stats .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        flex: 1;
        min-width: 120px;
        max-width: 150px;
    }
    
    .hero-stats .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        line-height: 1;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .hero-stats .stat-label {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    }
    
    /* Content Cards */
    .content-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: 1px solid rgba(0,0,0,0.05);
        height: 100%;
        transition: all 0.3s ease;
    }
    
    .content-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .section-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        position: relative;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
        border-radius: 2px;
    }
    
    .section-subtitle {
        font-size: 1.1rem;
        color: #666;
        margin-bottom: 3rem;
    }
    
    /* Mission List */
    .mission-list {
        list-style: none;
        padding: 0;
    }
    
    .mission-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: rgba(97, 76, 57, 0.05);
        border-radius: 15px;
        transition: all 0.3s ease;
    }
    
    .mission-item:hover {
        background: rgba(97, 76, 57, 0.1);
        transform: translateX(5px);
    }
    
    .mission-icon {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 1rem;
        flex-shrink: 0;
        backdrop-filter: blur(15px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15), 
                    0 0 0 1px rgba(255, 255, 255, 0.1),
                    inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }
    
    .mission-icon i {
        color: white;
        font-size: 1.2rem;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .mission-text h5 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }
    
    .mission-text p {
        color: #666;
        margin: 0;
        line-height: 1.6;
    }
    
    /* Feature Cards */
    .feature-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: 1px solid rgba(0,0,0,0.05);
        height: 100%;
        transition: all 0.3s ease;
    }
    
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .feature-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        backdrop-filter: blur(15px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15), 
                    0 0 0 1px rgba(255, 255, 255, 0.1),
                    inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }
    
    .feature-icon i {
        color: white;
        font-size: 2rem;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .feature-card h5 {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }
    
    .feature-card p {
        color: #666;
        line-height: 1.6;
    }
    
    /* Value Cards */
    .value-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: 1px solid rgba(0,0,0,0.05);
        height: 100%;
        transition: all 0.3s ease;
    }
    
    .value-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .value-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        backdrop-filter: blur(15px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15), 
                    0 0 0 1px rgba(255, 255, 255, 0.1),
                    inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }
    
    .value-icon i {
        color: white;
        font-size: 2rem;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .value-card h5 {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }
    
    .value-card p {
        color: #666;
        line-height: 1.6;
    }
    
    /* About Logo */
    .about-logo-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .about-logo-img {
        height: 120px;
        width: auto;
        object-fit: contain;
        filter: brightness(1.1) contrast(1.1) drop-shadow(0 4px 8px rgba(0,0,0,0.3));
        transition: all 0.3s ease;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.1);
        padding: 8px;
        backdrop-filter: blur(5px);
    }
    
    .about-logo-img:hover {
        transform: scale(1.1);
        filter: brightness(1.3) contrast(1.2) drop-shadow(0 6px 12px rgba(0,0,0,0.4));
        background: rgba(255, 255, 255, 0.2);
    }
    
    /* Lead Text */
    .lead {
        font-size: 1.2rem;
        line-height: 1.8;
        color: #333;
        margin-bottom: 1.5rem;
    }
    
    /* Responsive Design */
    @media (max-width: 992px) {
        .hero-stats {
            max-width: 700px;
            gap: 1.75rem;
        }
        
        .hero-stats .stat-item {
            min-width: 110px;
            max-width: 140px;
        }
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.2rem;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
        }
        
        .hero-stats {
            gap: 1.5rem;
            padding: 1rem;
            max-width: 600px;
        }
        
        .hero-stats .stat-item {
            min-width: 100px;
            max-width: 130px;
        }
        
        .hero-stats .stat-number {
            font-size: 2rem;
        }
        
        .about-logo-img {
            height: 90px;
        }
        
        .cta-buttons .btn {
            display: block;
            width: 100%;
            margin-bottom: 1rem;
        }
        
        .content-card {
            padding: 2rem;
        }
        
        .mission-item {
            flex-direction: column;
            text-align: center;
        }
        
        .mission-icon {
            margin: 0 auto 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .hero-stats {
            flex-direction: column;
            gap: 1rem;
            max-width: 300px;
            padding: 1rem 0.5rem;
        }
        
        .hero-stats .stat-item {
            min-width: 80px;
            max-width: 120px;
            padding: 0.5rem;
        }
        
        .hero-stats .stat-number {
            font-size: 1.8rem;
        }
        
        .content-card {
            padding: 1.5rem;
        }
        
        .feature-card,
        .value-card {
            padding: 1.5rem;
        }
    }
</style>
@endpush
