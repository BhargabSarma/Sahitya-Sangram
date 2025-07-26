// Make sure GSAP and ScrollTrigger are loaded before this script
document.addEventListener("DOMContentLoaded", function() {
  gsap.registerPlugin(ScrollTrigger);

  // Headline reveal from left
  if (document.getElementById("hero-headline")) {
    gsap.from("#hero-headline", {
      x: -40,
      opacity: 0,
      duration: 0.9,
      ease: "power2.out",
      scrollTrigger: {
        trigger: "#hero-headline",
        start: "top 85%",
        toggleActions: "play none none reverse"
      }
    });
  }

  // Description fade up
  if (document.getElementById("hero-desc")) {
    gsap.from("#hero-desc", {
      y: 24,
      opacity: 0,
      duration: 0.8,
      delay: 0.15,
      ease: "power2.out",
      scrollTrigger: {
        trigger: "#hero-desc",
        start: "top 88%",
        toggleActions: "play none none reverse"
      }
    });
  }

  // Parallax effect for Swiper hero slider (optional, subtle)
  if (document.querySelector(".hero-slider-container")) {
    gsap.to(".hero-slider-container", {
      y: -30,
      scale: 1.02,
      ease: "none",
      scrollTrigger: {
        trigger: ".hero-slider-container",
        start: "top 95%",
        end: "bottom top",
        scrub: 0.6
      }
    });
  }
});