<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="اكتشف جمال سلطنة عُمان - مواقع سياحية رائعة وخدمات متميزة">
    <meta name="keywords" content="عُمان، سياحة، مواقع سياحية، خدمات سياحية، محافظات، ولايات">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'البادية - السياحة في عُمان')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/loogo.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/loogo.png') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('images/loogo.png') }}">
    <meta name="msapplication-TileImage" content="{{ asset('images/loogo.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Font Awesome Brands (للأيقونات التجارية) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/brands.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #614c39;
            --secondary-color: #a1815a;
            --accent-color: #deb47a;
            --highlight-color: #c19b6c;
            --neutral-color: #6b8b8a;
            --text-dark: #2c3e50;
            --text-light: #7f8c8d;
            --bg-light: #f8f9fa;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background-color: var(--white);
        }

        /* Header Styles */
        .header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--accent-color) 100%);
            color: white;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header .navbar-brand {
            font-size: 1.8rem;
            font-weight: 700;
            color: white !important;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex !important;
            align-items: center !important;
            gap: 1rem;
        }

        .header .navbar-brand:hover {
            color: var(--accent-color) !important;
            transform: scale(1.05);
        }

        .logo-img {
            height: 70px !important;
            width: auto !important;
            object-fit: contain !important;
            filter: brightness(1.3) contrast(1.2) drop-shadow(0 3px 6px rgba(0, 0, 0, 0.4));
            transition: all 0.3s ease;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.15);
            padding: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .navbar-brand:hover .logo-img {
            filter: brightness(1.4) contrast(1.2) drop-shadow(0 4px 8px rgba(0, 0, 0, 0.4));
            transform: scale(1.05);
            background: rgba(255, 255, 255, 0.2);
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .brand-name {
            font-size: 2.2rem !important;
            font-weight: 800 !important;
            color: white !important;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            margin: 0 !important;
            line-height: 1.2 !important;
        }

        .brand-subtitle {
            font-size: 1rem !important;
            font-weight: 500 !important;
            color: rgba(255, 255, 255, 0.9) !important;
            margin: 0 !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            line-height: 1.2 !important;
        }

        .navbar-brand:hover .brand-name {
            color: var(--accent-color) !important;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.6);
        }

        .navbar-brand:hover .brand-subtitle {
            color: rgba(255, 255, 255, 1) !important;
        }

        .navbar-nav .nav-link {
            color: white !important;
            font-weight: 500;
            margin: 0 0.5rem;
            padding: 0.5rem 1rem !important;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--accent-color) !important;
        }

        .navbar-nav .nav-link.active {
            background-color: var(--accent-color);
            color: var(--primary-color) !important;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(97, 76, 57, 0.7), rgba(161, 129, 90, 0.6), rgba(222, 180, 122, 0.5)),
            url('{{ asset("images/AL-badyah.jpg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 70vh;
            display: flex;
            align-items: center;
            color: white;
            text-align: center;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .hero-content p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .card-img-top {
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .card:hover .card-img-top {
            transform: scale(1.05);
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color), var(--accent-color));
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(97, 76, 57, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, var(--accent-color), var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(97, 76, 57, 0.4);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        /* Sections */
        .section {
            padding: 80px 0;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
            text-align: center;
        }

        .section-subtitle {
            font-size: 1.2rem;
            color: var(--text-light);
            text-align: center;
            margin-bottom: 3rem;
        }

        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--accent-color) 100%);
            color: white;
        }

        .stat-item {
            text-align: center;
            padding: 2rem 1rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--accent-color);
            display: block;
        }

        .stat-label {
            font-size: 1.1rem;
            margin-top: 0.5rem;
        }

        /* Footer */
        .footer {
            background: var(--text-dark);
            color: white;
            padding: 3rem 0 1rem;
        }

        .footer h5 {
            color: var(--accent-color);
            margin-bottom: 1rem;
        }

        .footer a {
            color: #bdc3c7;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: var(--accent-color);
        }

        /* إحصائيات الزيارات في الفوتر */
        .visit-stats-footer {
            display: flex;
            flex-direction: row;
            gap: 2rem;
            margin: 1.5rem 0;
            padding: 0;
            background: transparent;
            border-radius: 0;
            backdrop-filter: none;
            width: 100%;
            justify-content: center;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 1.5rem 2rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            flex: 1;
            max-width: 200px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .stat-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .stat-item i {
            font-size: 2rem;
            color: var(--accent-color);
            margin-bottom: 1rem;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }

        .stat-item span {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            line-height: 1;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .stat-item small {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 768px) {
            .visit-stats-footer {
                gap: 1.5rem;
                flex-direction: column;
                align-items: center;
            }
            
            .stat-item {
                padding: 1.25rem 1.5rem;
                max-width: 250px;
                width: 100%;
            }
            
            .stat-item span {
                font-size: 2rem;
            }
            
            .stat-item i {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .visit-stats-footer {
                gap: 1rem;
            }
            
            .stat-item {
                padding: 1rem 1.25rem;
                max-width: 200px;
            }
            
            .stat-item span {
                font-size: 1.8rem;
            }
            
            .stat-item i {
                font-size: 1.3rem;
            }
            
            .stat-item small {
                font-size: 0.9rem;
            }
        }

        /* Social Links */
        .social-links {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1.5rem;
        }

        .social-link {
            color: white;
            font-size: 2.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(15px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15), 
                        0 0 0 1px rgba(255, 255, 255, 0.1),
                        inset 0 1px 0 rgba(255, 255, 255, 0.2);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .social-link:hover {
            color: white;
            transform: translateY(-8px) scale(1.1);
            filter: drop-shadow(0 8px 20px rgba(255, 255, 255, 0.4));
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2), 
                        0 0 0 1px rgba(255, 255, 255, 0.2),
                        inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }

        .social-link i {
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }

        /* تكبير أيقونة الواتساب */
        .social-link.whatsapp-link {
            width: 85px;
            height: 85px;
            font-size: 3rem;
            background: linear-gradient(135deg, #25D366, #128C7E);
            border: 2px solid #25D366;
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.3);
        }

        .social-link.whatsapp-link:hover {
            background: linear-gradient(135deg, #128C7E, #25D366);
            transform: translateY(-8px) scale(1.15);
            box-shadow: 0 10px 25px rgba(37, 211, 102, 0.4);
            border-color: #128C7E;
        }

        /* Fallback للأيقونات في حالة عدم تحميل Font Awesome */
        .fa-fallback .social-links a i::before {
            content: "🔗";
            font-family: "Apple Color Emoji", "Segoe UI Emoji", "Noto Color Emoji", sans-serif;
            font-size: 1.4em;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }

        .fa-fallback .social-links a i.fa-x-twitter::before {
            content: "🐦";
        }

        .fa-fallback .social-links a i.fa-instagram::before {
            content: "📷";
        }

        .fa-fallback .social-links a i.fa-whatsapp::before {
            content: "📱";
        }

        /* تحسين مظهر الأيقونات العادية */
        .social-links a i {
            font-size: 1.2em;
            filter: brightness(1.1) contrast(1.1);
        }

        @media (max-width: 768px) {
            .social-links {
                gap: 1rem;
            }
            
            .social-link {
                width: 60px;
                height: 60px;
                font-size: 2rem;
            }
            
            .social-link.whatsapp-link {
                width: 75px;
                height: 75px;
                font-size: 2.5rem;
            }
        }

        @media (max-width: 576px) {
            .social-links {
                gap: 0.75rem;
            }
            
            .social-link {
                width: 55px;
                height: 55px;
                font-size: 1.8rem;
            }
            
            .social-link.whatsapp-link {
                width: 70px;
                height: 70px;
                font-size: 2.2rem;
            }
        }

        /* Search Box */
        .search-box {
            background: white;
            border-radius: 50px;
            padding: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin: 2rem 0;
        }

        .search-input {
            border: none;
            outline: none;
            padding: 10px 20px;
            width: 100%;
            font-size: 1.1rem;
        }

        .search-btn {
            background: var(--primary-color);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            background: var(--secondary-color);
        }

        /* Badges */
        .badge {
            font-size: 0.9rem;
            padding: 8px 12px;
            border-radius: 20px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }

            .hero-content p {
                font-size: 1.1rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .stat-number {
                font-size: 2rem;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-color);
        }

        /* Omani Style Elements */
        .omani-pattern {
            background-image: linear-gradient(45deg, var(--accent-color) 25%, transparent 25%),
                linear-gradient(-45deg, var(--accent-color) 25%, transparent 25%),
                linear-gradient(45deg, transparent 75%, var(--accent-color) 75%),
                linear-gradient(-45deg, transparent 75%, var(--accent-color) 75%);
            background-size: 20px 20px;
            background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
            opacity: 0.1;
        }

        .desert-glow {
            box-shadow: 0 0 20px rgba(222, 180, 122, 0.3);
        }

        .mountain-shadow {
            box-shadow: 0 4px 15px rgba(97, 76, 57, 0.2);
        }

        /* Footer Logo */
        .footer-logo {
            height: 45px;
            width: auto;
            object-fit: contain;
            filter: brightness(1.2) contrast(1.1) drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
            opacity: 0.9;
            transition: all 0.3s ease;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.1);
            padding: 4px;
        }

        .footer-brand:hover .footer-logo {
            opacity: 1;
            transform: scale(1.05);
            filter: brightness(1.4) contrast(1.2) drop-shadow(0 4px 8px rgba(0, 0, 0, 0.4));
            background: rgba(255, 255, 255, 0.2);
        }

        .footer-brand-text h5 {
            color: var(--accent-color);
            font-weight: 700;
        }

        .footer-brand-text small {
            font-size: 0.75rem;
            color: #bdc3c7;
        }

        /* Newsletter Styling */
        .newsletter-title {
            color: var(--accent-color) !important;
            font-weight: 700 !important;
            font-size: 1.25rem !important;
            margin-bottom: 0.75rem !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3) !important;
        }

        .newsletter-subtitle {
            color: rgba(255, 255, 255, 0.9) !important;
            font-size: 0.95rem !important;
            margin-bottom: 1.5rem !important;
            line-height: 1.5 !important;
        }

        .newsletter-form {
            display: flex !important;
            background: rgba(255, 255, 255, 0.95) !important;
            border-radius: 25px !important;
            overflow: hidden !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
            transition: all 0.3s ease !important;
            backdrop-filter: blur(10px) !important;
        }

        .newsletter-form:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2) !important;
        }

        .newsletter-input {
            flex: 1 !important;
            border: none !important;
            outline: none !important;
            padding: 15px 20px !important;
            font-size: 0.95rem !important;
            background: transparent !important;
            color: #333 !important;
            font-weight: 500 !important;
        }

        .newsletter-input::placeholder {
            color: #666 !important;
            font-style: italic !important;
            font-weight: 400 !important;
        }

        .newsletter-btn {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%) !important;
            border: none !important;
            padding: 15px 25px !important;
            color: white !important;
            font-weight: 700 !important;
            font-size: 0.95rem !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            position: relative !important;
            overflow: hidden !important;
            min-width: 80px !important;
        }

        .newsletter-btn::before {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: -100% !important;
            width: 100% !important;
            height: 100% !important;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent) !important;
            transition: left 0.6s ease !important;
        }

        .newsletter-btn:hover::before {
            left: 100% !important;
        }

        .newsletter-btn:hover {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%) !important;
            transform: scale(1.05) !important;
            box-shadow: 0 5px 15px rgba(97, 76, 57, 0.4) !important;
        }

        .newsletter-btn:active {
            transform: scale(0.98) !important;
        }

        @media (max-width: 768px) {
            .newsletter-title {
                font-size: 1.1rem !important;
            }

            .newsletter-subtitle {
                font-size: 0.9rem !important;
                margin-bottom: 1.25rem !important;
            }

            .newsletter-form {
                flex-direction: column !important;
                border-radius: 20px !important;
            }

            .newsletter-input {
                padding: 12px 18px !important;
                font-size: 0.9rem !important;
                border-radius: 20px 20px 0 0 !important;
            }

            .newsletter-btn {
                padding: 12px 20px !important;
                font-size: 0.9rem !important;
                border-radius: 0 0 20px 20px !important;
                min-width: auto !important;
            }
        }

        /* Floating WhatsApp Icon */
        .floating-whatsapp {
            position: fixed;
            bottom: 30px;
            left: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(37, 211, 102, 0.4);
            z-index: 1000;
            text-decoration: none;
            transition: all 0.3s ease;
            animation: whatsappFloat 2s ease-in-out infinite;
        }

        .floating-whatsapp:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(37, 211, 102, 0.6);
            animation-play-state: paused;
        }

        .floating-whatsapp i {
            color: white;
            font-size: 28px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .floating-whatsapp::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border-radius: 50%;
            background: rgba(37, 211, 102, 0.2);
            animation: whatsappPulse 2s ease-in-out infinite;
        }

        @keyframes whatsappFloat {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes whatsappPulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.7;
            }
        }

        /* Responsive WhatsApp Icon */
        @media (max-width: 768px) {
            .floating-whatsapp {
                bottom: 20px;
                left: 20px;
                width: 55px;
                height: 55px;
            }

            .floating-whatsapp i {
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {
            .floating-whatsapp {
                bottom: 15px;
                left: 15px;
                width: 50px;
                height: 50px;
            }

            .floating-whatsapp i {
                font-size: 22px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Header -->
    <header class="header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ route('tourism.index') }}">
                    <img src="{{ asset('images/loogo.png') }}" alt="البادية" class="logo-img me-3">
                    <div class="brand-text">
                        <div class="brand-name">البادية</div>
                        <div class="brand-subtitle">السياحة في عُمان</div>
                    </div>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('tourism.index') ? 'active' : '' }}" href="{{ route('tourism.index') }}">
                                الرئيسية
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('tourism.search*') ? 'active' : '' }}" href="{{ route('tourism.search') }}">
                                البحث
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('tourism.governorates*') ? 'active' : '' }}" href="{{ route('tourism.governorates') }}">
                                المحافظات
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('tourism.tourist-sites*') ? 'active' : '' }}" href="{{ route('tourism.tourist-sites') }}">
                                المواقع السياحية
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('tourism.tourist-services*') ? 'active' : '' }}" href="{{ route('tourism.tourist-services') }}">
                                الخدمات السياحية
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('tourism.about') ? 'active' : '' }}" href="{{ route('tourism.about') }}">
                                من نحن
                            </a>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-brand d-flex align-items-center mb-3">
                        <img src="{{ asset('images/loogo.png') }}" alt="البادية" class="footer-logo me-3">
                        <div class="footer-brand-text">
                            <h5 class="mb-0">البادية</h5>
                            <small class="text-white">السياحة في عُمان</small>
                        </div>
                    </div>
                    <p>اكتشف جمال سلطنة عُمان من الجبال الشامخة إلى السواحل الذهبية، من الصحراء الذهبية إلى الواحات الخضراء. نقدم لك تجربة سياحية لا تُنسى عبر تراث عُمان العريق.</p>
                    
                    <!-- إحصائيات الزيارات -->
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6">
                            <div class="visit-stats-footer">
                                <div class="stat-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span id="total-cities">-</span>
                                    <small>مدينة</small>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-eye"></i>
                                    <span id="total-visits">-</span>
                                    <small>زيارة</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="social-links"> 
                        <a href="https://x.com/alyasi_mbrmj?s=21" class="social-link"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="https://www.instagram.com/alyasi_mbrmj?igsh=MWVoc3diY2Joam02NQ%3D%3D&utm_source=qr" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="https://wa.me/96871568883" target="_blank" class="social-link"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>روابط سريعة</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('tourism.index') }}">الرئيسية</a></li>
                        <li><a href="{{ route('tourism.governorates') }}">المحافظات</a></li>
                        <li><a href="{{ route('tourism.tourist-sites') }}">المواقع السياحية</a></li>
                        <li><a href="{{ route('tourism.tourist-services') }}">الخدمات السياحية</a></li>
                        <!-- رابط مخفي للوحة التحكم -->
                        <li style="opacity: 0.1; font-size: 0.1rem; line-height: 0.1rem;">
                            <a href="/admin/login" style="color: transparent; text-decoration: none;" title="Admin Panel">.</a>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>معلومات الاتصال</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2"></i><a href="https://maps.app.goo.gl/VMs4iu4cCwCa5Q4o7" target="_blank" style="color: #bdc3c7; text-decoration: none;">الياسي للبرمجيات</a></li>
                        <li><i class="fas fa-envelope me-2"></i><a href="mailto:alyasiforchargers@gmail.com" style="color: #bdc3c7; text-decoration: none;">alyasiforchargers@gmail.com</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="newsletter-title">اشترك في النشرة الإخبارية</h5>
                    <p class="newsletter-subtitle">احصل على آخر الأخبار والعروض السياحية</p>
                    <div class="newsletter-form">
                        <input type="email" class="newsletter-input" placeholder="بريدك الإلكتروني">
                        <button class="newsletter-btn" type="button">اشتراك</button>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2025 البادية. جميع الحقوق محفوظة.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="me-3">سياسة الخصوصية</a>
                    <a href="#">شروط الاستخدام</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome JS (لضمان تحميل الأيقونات) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in-up');
                }
            });
        }, observerOptions);

        // Observe all cards and sections
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.card, .section').forEach(el => {
                observer.observe(el);
            });
        });
    </script>

    @stack('scripts')
    
    <!-- Visit Tracking Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // التحقق من وجود session storage لتجنب العد المكرر
            if (!sessionStorage.getItem('visit_recorded')) {
                trackVisit();
            }
            
            // جلب إحصائيات الزيارات
            loadVisitStats();
        });

        function trackVisit() {
            // جلب معلومات الموقع
            fetch('https://ipapi.co/json/')
                .then(response => response.json())
                .then(data => {
                    const country = data.country_name || 'Unknown';
                    const city = data.city || 'Unknown';
                    
                    // إرسال البيانات للخادم
                    fetch('/save-visit', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            country: country,
                            city: city
                        })
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            console.log('Visit recorded successfully:', result);
                            // تسجيل في session storage لتجنب العد المكرر
                            sessionStorage.setItem('visit_recorded', 'true');
                        } else {
                            console.log('Visit already recorded or error:', result.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error recording visit:', error);
                    });
                })
                .catch(error => {
                    console.error('Error getting location info:', error);
                    // في حالة فشل جلب الموقع، إرسال زيارة بدون معلومات الموقع
                    fetch('/save-visit', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            country: 'Unknown',
                            city: 'Unknown'
                        })
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            sessionStorage.setItem('visit_recorded', 'true');
                        }
                    })
                    .catch(err => console.error('Error recording visit:', err));
                });
        }

        function loadVisitStats() {
            // جلب إجمالي الزيارات
            fetch('/total-visits')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('total-visits').textContent = formatNumber(data.total_visits);
                    }
                })
                .catch(error => {
                    console.error('Error loading total visits:', error);
                });

            // جلب إحصائيات مفصلة لعدد المدن
            fetch('/visit-stats')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data.visits_by_city) {
                        const uniqueCities = data.data.visits_by_city.length;
                        document.getElementById('total-cities').textContent = formatNumber(uniqueCities);
                    }
                })
                .catch(error => {
                    console.error('Error loading city stats:', error);
                });
        }

        function formatNumber(num) {
            if (num >= 1000000) {
                return (num / 1000000).toFixed(1) + 'M';
            } else if (num >= 1000) {
                return (num / 1000).toFixed(1) + 'K';
            }
            return num.toString();
        }

        // Font Awesome fallback check
        function checkFontAwesome() {
            const testIcon = document.createElement('i');
            testIcon.className = 'fas fa-check';
            testIcon.style.position = 'absolute';
            testIcon.style.visibility = 'hidden';
            testIcon.style.left = '-9999px';
            document.body.appendChild(testIcon);
            
            const computedStyle = window.getComputedStyle(testIcon, ':before');
            const fontFamily = computedStyle.getPropertyValue('font-family');
            
            if (!fontFamily.includes('Font Awesome') && !fontFamily.includes('fa-')) {
                console.log('Font Awesome not loaded, using emoji fallbacks');
                document.body.classList.add('fa-fallback');
            }
            
            document.body.removeChild(testIcon);
        }

        // تشغيل الفحص عند تحميل الصفحة
        setTimeout(checkFontAwesome, 2000);
    </script>
    
</body>

</html>