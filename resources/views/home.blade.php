@extends('layouts.frontend.master')

@section('main')
    <!-- Hero Section -->
    <section class="hero">
        <img src="{{ asset('imgs/hero.jpg') }}" alt="Team of Lawyers" />
        <div class="hero-text">
            <h1>WE ARE VOICE OF JUSTICE</h1>
            <p>Lawyer / Law Firm / Attorney</p>
            <a href="#" class="btn-primary">Contact Us</a>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services container">
        <div class="service-grid">
            <div class="service-card">
                <i class="fas fa-gavel"></i>
                <h3>Court Marriage Service</h3>
                <p>Our law firm provides legal services for court marriages.</p>
                <a href="#">Read More</a>
            </div>
            <div class="service-card">
                <i class="fas fa-briefcase"></i>
                <h3>Tax & Corporate</h3>
                <p>Helping you comply with tax laws and corporate regulations.</p>
                <a href="#">Read More</a>
            </div>
            <div class="service-card">
                <i class="fas fa-balance-scale"></i>
                <h3>Criminal & Civil Matters</h3>
                <p>We cover all aspects of civil and criminal litigation.</p>
                <a href="#">Read More</a>
            </div>
            <div class="service-card">
                <i class="fas fa-child"></i>
                <h3>Child Adoption Service</h3>
                <p>Complete assistance with child adoption processes.</p>
                <a href="#">Read More</a>
            </div>
            <div class="service-card">
                <i class="fas fa-shield-alt"></i>
                <h3>Cyber Crimes</h3>
                <p>High protection for cybercrime-related matters.</p>
                <a href="#">Read More</a>
            </div>
            <div class="service-card">
                <i class="fas fa-users"></i>
                <h3>Family Matters</h3>
                <p>Supporting families in legal issues and disputes.</p>
                <a href="#">Read More</a>
            </div>
        </div>
    </section>

    <!-- Blogs Section -->
    <section class="blogs container">
        <h2>Recent Blogs</h2>
        <div class="blog-grid">
            <div class="blog-card">
                <img src="{{ asset('imgs/hero.jpg') }}" alt="Blog Image" />
                <h3>2024 MLD 1316</h3>
                <p>Details about case law and legal references.</p>
                <a href="#">Read More</a>
            </div>
            <div class="blog-card">
                <img src="{{ asset('imgs/hero.jpg') }}" alt="Blog Image" />
                <h3>2024 SCMR 1413</h3>
                <p>Important observations about case verdicts.</p>
                <a href="#">Read More</a>
            </div>
            <div class="blog-card">
                <img src="{{ asset('imgs/1hero.jpg') }}" alt="Blog Image" />
                <h3>2024 YLR 1139</h3>
                <p>Breaking of fetters and related implications.</p>
                <a href="#">Read More</a>
            </div>
        </div>
    </section>
@endsection
