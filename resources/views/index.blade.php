<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sahityaa Sangramm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Modern custom styles -->
    <link rel="stylesheet" href="{{ asset('css/header-hero-shelf.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header-hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('css/void-cards.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('css/shelf-books.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('css/index.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('css/plans-books.css') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Login page custom CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/logins/login-12/assets/css/login-12.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- TailwindCSS (if you use Tailwind utility classes) -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <!-- <div id="preloader" style="position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:9999;background:#fff;display:flex;align-items:center;justify-content:center;">
    <div class="book">
        <div class="book__pg-shadow"></div>
        <div class="book__pg"></div>
        <div class="book__pg book__pg--2"></div>
        <div class="book__pg book__pg--3"></div>
        <div class="book__pg book__pg--4"></div>
        <div class="book__pg book__pg--5"></div>
    </div>
</div> -->

    @include('components.header')

    @include('components.hero')

    <!-- <div>
        @include('components.void-cards')
    </div>
     -->
    <div class="bg-white">
        @include('components.shelf')
    </div>
    <div class="bg-white">
        @include('components.plans')
    </div>


    <!-- Footer -->
    @include('components.footer')

    <!-- JS dependencies -->
    <!-- jQuery, Popper, Bootstrap JS (if you use Bootstrap JS) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- GSAP & ScrollTrigger for animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/CustomEase.min.js"></script>

    <!-- SplitType for animated text lines -->
    <script src="https://unpkg.com/split-type"></script>

    <!-- Your custom JS (should be after all libraries) -->
    <!-- If you have a separate hero parallax JS, include it here -->
    <script src="{{ asset('js/index.js') }}"></script>
    <script src="{{ asset('js/hero-gsap.js') }}"></script>
    <script src="{{ asset('js/shelf-books.js') }}"></script>
    <script src="{{ asset('js/plans-books.js') }}"></script>
    <!-- <script src="{{ asset('js/index.js') }}"></script> -->
</body>

</html>