<!--
  Hero Image Slider - Full screen, no overlay navigation, clean large arrow buttons (no round backgrounds)
  - Uses Swiper.js
  - 100vw x 100vh images (cover)
  - Navigation arrows: large, no circle background, shadow on hover
  - White background
-->

<style>
    .hero-slider-container {
        position: relative;
        width: 100vw;
        height: 100vh;
        overflow: hidden;
        background: white;
    }
    .swiper,
    .swiper-wrapper,
    .swiper-slide {
        width: 100vw !important;
        height: 100vh !important;
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
        height: 100vh;
        object-fit: cover;
        border-radius: 0;
    }
    /* Large, clean arrows with shadow on hover, no round background */
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
    }
    .swiper-pagination {
        bottom: 40px !important;
    }
</style>

<div class="hero-slider-container">
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            <!-- Slide 1 -->
            <div class="swiper-slide">
                <img src="/images/c1fa7122e99e55076a1ffb0c17544ea1.jpg" alt="Slide 1" />
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
    });
</script>