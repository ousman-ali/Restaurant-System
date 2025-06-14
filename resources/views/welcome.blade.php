<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ config('app.name')  }} - A Culinary Adventure</title>
    <!-- Bootstrap 5 CSS -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"
        rel="stylesheet"
    />
    <!-- Bootstrap Icons -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css"
        rel="stylesheet"
    />
    <style>
        :root {
            --primary-color: #FF6B6B;
            --secondary-color: #4ECDC4;
            --accent-color: #FFE66D;
            --dark-color: #292F36;
            --light-color: #F7FFF7;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark-color);
            background-color: var(--light-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #ff5252;
            border-color: #ff5252;
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .bg-custom-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .bg-custom-secondary {
            background-color: var(--secondary-color);
            color: white;
        }

        .bg-custom-accent {
            background-color: var(--accent-color);
            color: var(--dark-color);
        }

        .bg-custom-dark {
            background-color: var(--dark-color);
            color: white;
        }

        .text-custom-primary {
            color: var(--primary-color);
        }

        .text-custom-secondary {
            color: var(--secondary-color);
        }

        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/api/placeholder/1920/600');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 150px 0;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.7), rgba(78, 205, 196, 0.7));
            z-index: -1;
        }

        .menu-card {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            margin-bottom: 30px;
        }

        .menu-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .menu-img {
            height: 200px;
            object-fit: cover;
        }

        .menu-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
        }

        .navbar {
            padding: 15px 0;
            transition: all 0.3s;
        }

        .navbar-brand {
            font-size: 28px;
            font-weight: bold;
        }

        .nav-link {
            font-size: 16px;
            font-weight: 600;
            margin: 0 10px;
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--accent-color);
            transition: width 0.3s;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .section-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 40px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 100px;
            height: 3px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            transform: translateX(-50%);
        }

        .portion-options {
            width: 100%;
        }

        .portion-option {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .portion-option:hover {
            background-color: var(--accent-color) !important;
            border-color: var(--secondary-color) !important;
            transform: translateX(5px);
        }

        .portion-option .badge {
            font-size: 0.9rem;
            padding: 6px 10px;
        }

        .footer {
            background-color: var(--dark-color);
            color: white;
            padding: 60px 0 30px;
        }

        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            margin-right: 10px;
            transition: all 0.3s;
        }

        .social-icon:hover {
            background-color: var(--primary-color);
            transform: translateY(-5px);
        }

        .category-tab {
            padding: 10px 20px;
            border-radius: 25px;
            margin: 0 5px 15px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .category-tab.active {
            background-color: var(--primary-color);
            color: white;
        }

        .dish-info {
            border-left: 3px solid var(--primary-color);
            padding-left: 15px;
        }

        .chef-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background-color: var(--accent-color);
            color: var(--dark-color);
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }

        .about-img {
            border-radius: 20px;
            box-shadow: 20px 20px 0px var(--primary-color);
        }
    </style>
</head>
<body>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-custom-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="bi bi-egg-fried text-custom-primary"></i>
            <span class="text-custom-primary">{{ config('app.name')  }}</span>
        </a>
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
        >
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @foreach($menus as $menu)
                    <li class="nav-item">
                        <a class="nav-link" href="#{{$menu->section_id}}">{{$menu->name}}</a>
                    </li>
                @endforeach

                @if (Route::has('login'))
                    <li class="nav-item ms-2">
                        @if (Auth::check())
                            <a href="{{url('/home')}}" class="btn btn-primary">Home</a>
                        @else
                            <a href="{{route('login')}}" class="btn btn-primary">Login</a>
                            @if(!\App\Models\User::first())
                                <a href="{{route('register')}}" class="btn btn-primary">Join</a>
                            @endif
                        @endif

                    </li>
                @endif

            </ul>
        </div>
    </div>
</nav>

@foreach($webPages as $section)
    @if($section->section_id == 'menu' || \Illuminate\Support\Str::contains($section->content, 'id="menus"'))
        @include('menus')
    @else
    {!! $section->content !!}
    @endif
@endforeach


<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    function filterDishes(categoryId) {
        // Get all menu items and category tabs
        const menuItems = document.querySelectorAll('.menu-item');
        const categoryTabs = document.querySelectorAll('.category-tab');
        const noResultsMessage = document.getElementById('no-results-message');

        // Update active tab
        categoryTabs.forEach(tab => {
            if (categoryId === 'all' && tab.getAttribute('data-category-id') === 'all') {
                tab.classList.add('active');
            } else if (tab.getAttribute('data-category-id') == categoryId) {
                tab.classList.add('active');
            } else {
                tab.classList.remove('active');
            }
        });

        // Track if we have visible items
        let visibleItems = 0;

        // Filter menu items
        menuItems.forEach(item => {
            const itemCategory = item.getAttribute('data-category');

            if (categoryId === 'all' || itemCategory == categoryId) {
                item.style.display = 'block';
                visibleItems++;

                // Add animation effect
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';

                // Delay each item slightly for staggered effect
                setTimeout(() => {
                    item.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, 50 * visibleItems); // Staggered delay
            } else {
                item.style.display = 'none';
            }
        });

        // Show or hide no results message
        if (visibleItems === 0) {
            noResultsMessage.style.display = 'block';
        } else {
            noResultsMessage.style.display = 'none';
        }
    }

    // Initialize with all dishes shown
    document.addEventListener('DOMContentLoaded', function () {
        // Initial animation for all items
        const menuItems = document.querySelectorAll('.menu-item');
        menuItems.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';

            setTimeout(() => {
                item.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 50 * index);
        });
    });
</script>
</body>
</html>
