@extends('layouts.frontend.master')

@section('main')

    <!-- Blogs Section -->
    <section class="blogs container">
        <h2>Recent Blogs</h2>
        <div class="blog-grid">
            <div class="blog-card">
                <img src="{{ asset('public/imgs/hero.jpg') }}" alt="Blog Image" />
                <h3>2024 MLD 1316</h3>
                <p>Details about case law and legal references.</p>
                <a href="#">Read More</a>
            </div>
            <div class="blog-card">
                <img src="{{ asset('public/imgs/hero.jpg') }}" alt="Blog Image" />
                <h3>2024 SCMR 1413</h3>
                <p>Important observations about case verdicts.</p>
                <a href="#">Read More</a>
            </div>
            <div class="blog-card">
                <img src="{{ asset('public/imgs/1hero.jpg') }}" alt="Blog Image" />
                <h3>2024 YLR 1139</h3>
                <p>Breaking of fetters and related implications.</p>
                <a href="#">Read More</a>
            </div>
        </div>
    </section>
@endsection
