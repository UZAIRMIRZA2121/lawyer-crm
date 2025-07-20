<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lawyers of Pakistan</title>
    <link rel="stylesheet" href="{{ asset('public/asset/css/styles.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        .whatsapp-float {
  position: fixed;
  width: 60px;
  height: 60px;
  bottom: 20px;
  right: 20px;
  background-color: #25d366;
  color: #fff;
  border-radius: 50px;
  text-align: center;
  font-size: 30px;
  z-index: 1000;
  box-shadow: 0 2px 5px rgba(0,0,0,0.3);
}

.whatsapp-icon {
  margin-top: 14px;
}

    </style>
</head>

<body>

    @include('layouts.frontend.header')

    @yield('main')
    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/923057191127" class="whatsapp-float" target="_blank" title="Chat with us on WhatsApp">
        <i class="fab fa-whatsapp whatsapp-icon"></i>
    </a>

    @include('layouts.frontend.footer')
    <script src="{{ asset('public/asset/js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openModal(member) {
            document.getElementById('modalName').innerText = member.name;
            document.getElementById('modalRole').innerText = member.role;
            document.getElementById('modalQualification').innerText = member.qualification;
            document.getElementById('modalContact').innerText = member.contact;
            document.getElementById('modalEmail').innerText = member.email;
            document.getElementById('modalImage').src = member.image;
            document.getElementById('modalFacebook').href = member.facebook;
            document.getElementById('modalTwitter').href = member.twitter;
            document.getElementById('modalLinkedIn').href = member.linkedin;
            const modal = new bootstrap.Modal(document.getElementById('teamModal'), {
                backdrop: false // disables the backdrop
            });
            modal.show();

        }
    </script>
</body>

</html>
