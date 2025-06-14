@extends('layouts.app')

@section('title')
    {{ $dish->dish }} - Details
@endsection

@section('extra-css')
    <link rel="stylesheet" href="{{ url('/dashboard/plugins/magnific-popup/css/magnific-popup.css') }}">
    <style>
        .dish-detail-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .dish-header {
            position: relative;
            padding: 25px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #eee;
        }

        .dish-header h2 {
            margin: 0 0 5px;
            color: #333;
            font-weight: 600;
        }

        .dish-header .category-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #fff;
            background-color: #5b6be8;
        }

        .dish-content {
            padding: 25px;
        }

        .dish-image-container {
            position: relative;
            overflow: hidden;
            border-radius: 6px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .dish-image {
            width: 100%;
            height: auto;
            transition: transform 0.3s ease;
        }

        .dish-image:hover {
            transform: scale(1.03);
        }

        .price-list-title {
            margin-bottom: 20px;
            color: #333;
            font-weight: 600;
            border-bottom: 2px solid #5b6be8;
            padding-bottom: 10px;
            display: inline-block;
        }

        .price-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .price-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            margin-bottom: 10px;
            border-radius: 6px;
            background-color: #f8f9fa;
            transition: all 0.2s ease;
        }

        .price-item:hover {
            background-color: #eef1f5;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .price-type {
            font-weight: 500;
            color: #444;
        }

        .price-value {
            font-weight: 700;
            color: #28a745;
            background-color: rgba(40, 167, 69, 0.1);
            padding: 5px 12px;
            border-radius: 20px;
        }

        .gallery-container {
            padding: 25px;
        }

        .gallery-title {
            margin-bottom: 20px;
            color: #333;
            font-weight: 600;
            text-align: center;
        }

        .gallery-item {
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .gallery-item:hover {
            transform: translateY(-5px);
        }

        .gallery-thumb {
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .gallery-thumb img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .gallery-thumb:hover img {
            transform: scale(1.05);
        }

        .gallery-thumb::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.2);
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 6px;
        }

        .gallery-thumb:hover::after {
            opacity: 1;
        }

        .gallery-caption {
            padding: 12px;
            text-align: center;
            font-weight: 500;
        }

        .mfp-title {
            font-size: 16px;
            font-weight: 500;
        }

        /* Responsive adjustments */
        @media (max-width: 767px) {
            .dish-content {
                padding: 15px;
            }

            .dish-image-container {
                margin-bottom: 20px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/all-dish')}}" class="btn btn-default waves-effect">All Dish <span class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">Edit Dish </h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    <a href="{{url('/all-dish')}}">Dishes</a>
                </li>
                <li class="active">
                    {{$dish->dish}}
                </li>
            </ol>
        </div>
    </div>

    <div class="dish-detail-container">
        <div class="dish-header">
            <h2>{{ $dish->dish }} ({{$dish->category->name}})</h2>
            @if(isset($dish->category))
                <span class="category-badge">{{ $dish->category->name ?? 'Uncategorized' }}</span>
            @endif
        </div>

        <div class="dish-content">
            <div class="row">
                <div class="col-md-5">
                    <div class="dish-image-container">
                        <img src="{{ url($dish->thumbnail) }}" alt="{{ $dish->dish }}" class="dish-image img-responsive">
                    </div>
                </div>

                <div class="col-md-7">
                    <h4 class="price-list-title">Available Options & Pricing</h4>

                    <ul class="price-list">
                        @forelse($dish->dishPrices as $dishPrice)
                            <li class="price-item">
                                <span class="price-type">{{ $dishPrice->dish_type }}</span>
                                <span class="price-value">
                                    {{ config('restaurant.currency.symbol') }} {{ $dishPrice->price }} {{ config('restaurant.currency.currency') }}
                                </span>
                            </li>
                        @empty
                            <li class="price-item">
                                <span class="price-type">No pricing information available</span>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @if(count($dish->dishImages) > 0)
        <div class="dish-detail-container">
            <div class="gallery-container">
                <h4 class="gallery-title">Photo Gallery</h4>

                <div class="row">
                    @foreach($dish->dishImages as $image)
                        <div class="col-sm-6 col-lg-3 col-md-4 gallery-item">
                            <div class="gallery-thumb">
                                <a href="{{ url($image->image) }}" class="image-popup" title="{{ $image->title }}">
                                    <img src="{{ url($image->image) }}" alt="{{ $image->title }}" class="img-responsive">
                                </a>
                            </div>
                            @if($image->title)
                                <div class="gallery-caption">{{ $image->title }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection

@section('extra-js')
    <script type="text/javascript" src="{{ url('/dashboard/plugins/isotope/js/isotope.pkgd.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('/dashboard/plugins/magnific-popup/js/jquery.magnific-popup.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize Magnific Popup for gallery
            $('.image-popup').magnificPopup({
                type: 'image',
                closeOnContentClick: true,
                mainClass: 'mfp-fade',
                gallery: {
                    enabled: true,
                    navigateByImgClick: true,
                    preload: [0, 1]
                },
                image: {
                    titleSrc: 'title'
                },
                zoom: {
                    enabled: true,
                    duration: 300
                }
            });

            // Initialize Isotope if needed
            $('.portfolioContainer').isotope({
                itemSelector: '.gallery-item',
                layoutMode: 'fitRows'
            });
        });
    </script>
@endsection
