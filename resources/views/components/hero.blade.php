<style>
    .hero-slider-container {
        position: relative;
        width: 100vw;
        max-width: 100vw;
        overflow: hidden;
        background: white;
    }
    .swiper,
    .swiper-wrapper,
    .swiper-slide {
        width: 100vw !important;
        max-width: 100vw;
    }
    .swiper-slide {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        margin: 0;
    }
    .swiper-slide img {
        width: 100vw;
        object-fit: cover;
        object-position: center;
        border-radius: 0;
        display: block;
        max-width: 100vw;
    }

    /* Desktop: Use a nice aspect ratio and cap height */
    @media (min-width: 1024px) {
        .hero-slider-container,
        .swiper,
        .swiper-wrapper,
        .swiper-slide,
        .swiper-slide img {
            height: 600px !important;
            min-height: 700px !important;
            max-height: 600px !important;
        }
    }
    /* Tablet: a bit shorter */
    @media (max-width: 1023px) and (min-width: 501px) {
        .hero-slider-container,
        .swiper,
        .swiper-wrapper,
        .swiper-slide,
        .swiper-slide img {
            height: 320px !important;
            min-height: 240px !important;
            max-height: 500px !important;
        }
    }
    /* Phone: show only the first image, big square, no swiper */
    @media (max-width: 500px) {
        .hero-slider-container,
        .swiper,
        .swiper-wrapper,
        .swiper-slide,
        .swiper-slide img {
            width: 100vw !important;
            max-width: 100vw !important;
            height: 100vw !important; /* square, covers half or more of the viewport */
            min-height: 100vw !important;
            max-height: 100vw !important;
        }
        /* Hide all slides except the first */
        .swiper-slide:not(:first-child) {
            display: none !important;
        }
        /* Hide swiper controls and dots */
        .swiper-button-next,
        .swiper-button-prev,
        .swiper-pagination {
            display: none !important;
        }
    }
    /* Large, clean arrows with shadow on hover */
    .swiper-button-next,
    .swiper-button-prev {
        width: 54px;
        height: 54px;
        background: none !important;
        color: #222 !important;
        box-shadow: none;
        font-size: 3rem !important;
        opacity: 0.9;
        border-radius: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        top: 50%;
        transform: translateY(-50%);
        transition: box-shadow 0.2s, background 0.2s;
        z-index: 2;
    }
    .swiper-button-next:hover,
    .swiper-button-prev:hover {
        box-shadow: 0 4px 18px rgba(0,0,0,0.12);
        background: rgba(255,255,255,0.06);
    }
    .swiper-pagination {
        bottom: 28px !important;
    }
</style>

<div class="hero-slider-container">
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            <!-- Slide 1 -->
            <div class="swiper-slide">
                <img src="/images/52f32580fdac1deae602b9f9a6631c49.jpg" alt="Slide 1" />
            </div>
            <!-- Slide 2 -->
            <div class="swiper-slide">
                <img src="/images/image.png" alt="Slide 2" />
            </div>
            <!-- Slide 3 -->
            <div class="swiper-slide">
                <img src="/images/image copy.png" alt="Slide 3" />
            </div>
        </div>
        <!-- Built-in navigation buttons -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
        <!-- Pagination Dots -->
        <div class="swiper-pagination"></div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Only initialize Swiper if not on a phone
        if (window.innerWidth > 500) {
            new Swiper('.mySwiper', {
                slidesPerView: 1,
                loop: true,
                effect: "slide",
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                }
            });
        }
    });
</script>