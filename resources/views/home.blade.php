@extends('layouts.frontend.master')

@section('main')
    <!-- Hero Section -->
    <section class="hero">
        <img src="{{ asset('public/imgs/hero.jpg') }}" alt="Team of Lawyers" />
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
            <i class="fas fa-balance-scale"></i>
            <h3>Criminal Cases</h3>
            <p>Comprehensive representation in all types of criminal cases.</p>
            <a href="#">Read More</a>
        </div>
        <div class="service-card">
            <i class="fas fa-gavel"></i>
            <h3>Civil Matters</h3>
            <p>Handling a wide range of civil disputes and litigation.</p>
            <a href="#">Read More</a>
        </div>
        <div class="service-card">
            <i class="fas fa-briefcase"></i>
            <h3>Tax and Corporation</h3>
            <p>Advising and representing clients in tax and corporate law.</p>
            <a href="#">Read More</a>
        </div>
        <div class="service-card">
            <i class="fas fa-shield-alt"></i>
            <h3>Cyber Crimes</h3>
            <p>Expert assistance in cybercrime investigations and defense.</p>
            <a href="#">Read More</a>
        </div>
        <div class="service-card">
            <i class="fas fa-bomb"></i>
            <h3>Anti Terrorism and Anti Narcotics Cases</h3>
            <p>Specialized services in anti-terrorism and anti-narcotics cases.</p>
            <a href="#">Read More</a>
        </div>
        <div class="service-card">
            <i class="fas fa-hand-holding-usd"></i>
            <h3>Anti Corruption</h3>
            <p>Legal support in anti-corruption investigations and proceedings.</p>
            <a href="#">Read More</a>
        </div>
        <div class="service-card">
            <i class="fas fa-university"></i>
            <h3>Banking Cases</h3>
            <p>Advising and representing clients in banking and finance cases.</p>
            <a href="#">Read More</a>
        </div>
        <div class="service-card">
            <i class="fas fa-shopping-cart"></i>
            <h3>Consumer Cases</h3>
            <p>Protecting consumer rights and resolving disputes effectively.</p>
            <a href="#">Read More</a>
        </div>
        <div class="service-card">
            <i class="fas fa-briefcase-medical"></i>
            <h3>Labour Cases</h3>
            <p>Handling labour disputes and employment law issues.</p>
            <a href="#">Read More</a>
        </div>
        <div class="service-card">
            <i class="fas fa-users"></i>
            <h3>Family Cases</h3>
            <p>Resolving family matters with care and professionalism.</p>
            <a href="#">Read More</a>
        </div>
    </div>
</section>


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
