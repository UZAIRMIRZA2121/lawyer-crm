@extends('layouts.frontend.master')

@section('main')
<!-- Bootstrap Icons CDN (if not included globally) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
  .contact-section {
    max-width: 700px;
    margin: 50px auto;
    padding: 30px 25px;
    background: #f8f9fa;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(26, 77, 46, 0.15);
  }

  .contact-section h2 {
    color: #1a4d2e;
    font-weight: 700;
    margin-bottom: 40px;
    text-align: center;
  }

  .contact-item {
    margin-bottom: 25px;
  }

  .contact-icon {
    font-size: 1.7rem;
    color: #1a4d2e;
  }

  .contact-label {
    font-weight: 600;
    color: #1a4d2e;
    min-width: 100px;
  }

  .contact-info a,
  .contact-info {
    font-size: 1.1rem;
    color: #333;
    text-decoration: none;
  }

  .contact-info a:hover {
    color: #1a4d2e;
    text-decoration: underline;
  }

  @media (max-width: 575.98px) {
    .contact-label {
      min-width: 80px;
    }
  }
</style>

<section class="contact-section shadow-sm">
  <h2>Contact Us</h2>

  <div class="d-flex align-items-center contact-item">
    <i class="bi bi-envelope-fill contact-icon me-3"></i>
    <span class="contact-label">Email:</span>
    <a href="mailto:info@example.com" class="contact-info ms-3">info@example.com</a>
  </div>

  <div class="d-flex align-items-center contact-item">
    <i class="bi bi-telephone-fill contact-icon me-3"></i>
    <span class="contact-label">Phone:</span>
    <a href="tel:+1234567890" class="contact-info ms-3">+1 234 567 890</a>
  </div>

  <div class="d-flex align-items-center contact-item">
    <i class="bi bi-telephone-forward-fill contact-icon me-3"></i>
    <span class="contact-label">Telephone:</span>
    <span class="contact-info ms-3">+1 987 654 321</span>
  </div>

  <div class="d-flex align-items-center contact-item">
    <i class="bi bi-whatsapp contact-icon me-3"></i>
    <span class="contact-label">WhatsApp:</span>
    <a href="https://wa.me/1234567890" target="_blank" class="contact-info ms-3">+1 234 567 890</a>
  </div>

  <div class="d-flex align-items-start contact-item">
    <i class="bi bi-geo-alt-fill contact-icon me-3 mt-1"></i>
    <span class="contact-label">Address:</span>
    <address class="contact-info ms-3 mb-0">
      123 Green Street,<br>
      Suite 45,<br>
      Springfield, IL 62704,<br>
      USA
    </address>
  </div>
</section>
@endsection
