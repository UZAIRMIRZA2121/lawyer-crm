@extends('layouts.frontend.master')

@section('main')
    <section class="team-section">
        <h2>Our Team</h2>
        @php
            use App\Models\User;
            use Illuminate\Support\Collection;

            $users = User::where('role', 'team')->get();

            // Group by position (null or empty becomes 'No Position')
            $grouped = $users->groupBy(function ($user) {
                return $user->position ?: 'No Position';
            });

            // Move 'No Position' group to the end manually
            $ordered = collect();

            foreach ($grouped as $key => $group) {
                if ($key !== 'No Position') {
                    $ordered->put($key, $group);
                }
            }

            // Add 'No Position' at the end
            if ($grouped->has('No Position')) {
                $ordered->put('No Position', $grouped->get('No Position'));
            }
        @endphp

        @foreach ($ordered as $position => $members)
            <div class="mb-4">
              
                <div class="team-grid d-flex flex-wrap gap-3">
                    @foreach ($members as $member)
                        <div class="team-card text-center" onclick='openModal(@json($member))'>
                            <img src="{{ $member->profile_img ? asset('storage/' . $member->profile_img) : asset('default.png') }}"
                                alt="{{ $member->name }}" class="rounded-circle mb-2"
                                style="width: 100px; height: 100px; object-fit: cover;">
                            <h4 class="font-semibold mb-0">{{ $member->name }}</h4>
                            <p class="text-sm text-muted">{{ ucfirst($member->role) }}</p>
                        </div>
                    @endforeach
                </div>
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
