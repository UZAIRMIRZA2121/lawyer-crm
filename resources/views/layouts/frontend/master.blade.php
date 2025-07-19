<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lawyers of Pakistan</title>
    <link rel="stylesheet" href="{{ asset('public/asset/css/styles.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>

    @include('layouts.frontend.header')

    @yield('main')

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
