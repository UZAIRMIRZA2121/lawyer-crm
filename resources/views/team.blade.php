@extends('layouts.frontend.master')

@section('main')
  

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
@endsection
