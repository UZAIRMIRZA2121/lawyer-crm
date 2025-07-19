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
<section class="services-nav container my-5 d-flex flex-column flex-sm-row justify-content-center gap-3">
  <button type="button" class="btn btn-primary flex-fill" data-target="services">Service</button>
  <button type="button" class="btn btn-primary flex-fill" data-target="blogs">Blog</button>
  <button type="button" class="btn btn-primary flex-fill" data-target="team">Team</button>
</section>

    <style>
        /* Make buttons full width on xs screens */
        @media (max-width: 575.98px) {
            section.container>button {
                width: 100% !important;
            }
        }

        .services-nav button {
            background-color: #a3c0af;
            /* light inactive */
            color: #fff;
            border: none;
            transition: background-color 0.3s ease;
        }

        .services-nav button:hover {
            background-color: #1a4d2e;
            /* dark on hover */
        }

        .services-nav button.active {
            background-color: #1a4d2e;
            /* dark active */
            color: #fff;
        }
    </style>





    <!-- Optional future sections like "links" or "disabled" -->
    <section id="team" class="container section-content d-none">

        <section class="team-section">
            <h2>Our Team</h2>
            @php
                $groups = include resource_path('views/team-data.php');
            @endphp

            @foreach ($groups as $groupTitle => $members)
                <div class="team-grid">
                    @foreach ($members as $member)
                        <div class="team-card" onclick='openModal(@json($member))'>
                            <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}">
                            <h4>{{ $member['name'] }}</h4>
                            <p>{{ $member['role'] }}</p>
                        </div>
                    @endforeach
                </div>
            @endforeach



        </section>

        <!-- Team Modal -->
        <div class="modal fade" id="teamModal" tabindex="-1" aria-labelledby="teamModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-4">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalName">Member Name</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="modalImage" src="" alt="Member Image" class="rounded-circle mb-3" width="100"
                            height="100">
                        <p class="fw-bold mb-1" id="modalRole">Role Here</p>
                        <p class="text-muted mb-2" id="modalQualification">Qualification</p>
                        <p><strong>Contact:</strong> <span id="modalContact"></span></p>
                        <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                        <div class="social-icons mt-3">
                            <a href="#" id="modalFacebook" class="me-3" target="_blank"><i
                                    class="fab fa-facebook fa-lg"></i></a>
                            <a href="#" id="modalTwitter" class="me-3" target="_blank"><i
                                    class="fab fa-twitter fa-lg"></i></a>
                            <a href="#" id="modalLinkedIn" target="_blank"><i class="fab fa-linkedin fa-lg"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <section id="disabled" class="container section-content d-none">
        <p>This section is disabled.</p>
    </section>

    <!-- Services Section -->
    <section class="services container d-none" id="services">
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
    <section class="blogs container d-none" id="blogs">
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const buttons = document.querySelectorAll(".services-nav button");
        const sections = document.querySelectorAll("section.container:not(.services-nav)");

        buttons.forEach(button => {
            button.addEventListener("click", function() {
                // Remove active class from all buttons
                buttons.forEach(btn => btn.classList.remove("active"));

                // Add active class to clicked button
                this.classList.add("active");

                // Hide all sections
                sections.forEach(section => section.classList.add("d-none"));

                // Show the target section by ID
                const targetId = this.getAttribute("data-target");
                const targetSection = document.getElementById(targetId);
                if (targetSection) {
                    targetSection.classList.remove("d-none");
                }
            });
        });

        // Optionally, trigger click on the first button to show initial section
        if (buttons.length > 0) buttons[0].click();
    });
</script>
