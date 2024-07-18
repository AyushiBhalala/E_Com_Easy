<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f6f9;
        /* background-image: url('public\admin-assets\img\photo3.jpg'); */
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
    }
    .container-fluid {
        padding: 20px;
    }
    .small-box {
        position: relative;
        display: block;
        margin-bottom: 20px;
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease-in-out;
        /* background-image: url('../photo3.jpg'); */
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
    }

    .small-box:hover {
        transform: translateY(-10px);
    }

    .small-box .inner {
        padding: 20px;
        text-align: center;
    }

    .small-box .inner h3 {
        font-size: 2.2em;
        font-weight: bold;
        margin: 0;
        padding: 0;
    }
    .small-box .inner p {
        font-size: 1.1em;
        color: #888;
        margin: 10px 0 0;
    }
    .small-box .icon {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 60px;
        color: rgba(0, 0, 0, 0.15);
    }
    .small-box-footer {
        display: block;
        padding: 10px 0;
        text-align: center;
        background: #f7f7f7;
        border-top: 1px solid #eee;
        color: #007bff;
        text-decoration: none;
        border-radius: 0 0 8px 8px;
        transition: background 0.3s ease;
    }
    .small-box-footer:hover {
        background: #e9ecef;
    }
    @media (max-width: 767px) {
        .col-6 {
            width: 100%;
            padding: 0 15px;
            margin-bottom: 20px;
        }
    }
    @keyframes fadeInSlideUp {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        50% {
            opacity: 1;
            transform: translateY(0);
        }
        100% {
            opacity: 0;
            transform: translateY(-20px);
        }
    }
    .animated-header {
        text-align: center;
        color: #343a40;
        font-weight: bold;
        animation: fadeInSlideUp 2s ease-in-out infinite;
    }
</style>
@extends('supplier.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="animated-header">Supplier Dashboard</h1>
                </div>
                <div class="col-sm-6">

                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-6">
                    <div class="small-box card">
                        <div class="inner">

                            <h3>{{ $totalCategories }}</h3>
                            <p>Total Category</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{ route('categories.index') }}" class="small-box-footer text-dark">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box card">
                        <div class="inner">
                            <h3>{{ $totalSubCategories }}</h3>
                            <p>Total SubCategory</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{ route('sub-categories.index') }}" class="small-box-footer text-dark">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="small-box card">
                        <div class="inner">
                            <h3>{{ $totalBrands }}</h3>
                            <p>Total Brands</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('brands.index') }}" class="small-box-footer text-dark">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="small-box card">
                        <div class="inner">
                            <h3>{{ $totalProducts }}</h3>
                            <p>Total Product</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ route('products.index') }}" class="small-box-footer text-dark">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                        {{-- <a href="javascript:void(0);" class="small-box-footer">&nbsp;</a> --}}
                    </div>
                </div>

            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJs')
    <script>
        console.log("hello")
    </script>
@endsection
