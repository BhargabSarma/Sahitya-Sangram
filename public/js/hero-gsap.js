// Make sure GSAP and ScrollTrigger are loaded before this script
document.addEventListener("DOMContentLoaded", function() {
  gsap.registerPlugin(ScrollTrigger);

  // Headline reveal from left
  gsap.from("#hero-headline", {
    x: -80,
    opacity: 0,
    duration: 1.2,
    ease: "power3.out",
    scrollTrigger: {
      trigger: "#hero-headline",
      start: "top 80%",
      end: "bottom 60%",
      toggleActions: "play none none reverse"
    }
  });

  // Description fade up
  gsap.from("#hero-desc", {
    y: 40,
    opacity: 0,
    duration: 1,
    delay: 0.3,
    ease: "power3.out",
    scrollTrigger: {
      trigger: "#hero-desc",
      start: "top 85%",
      end: "bottom 70%",
      toggleActions: "play none none reverse"
    }
  });

  // Parallax hero image
  gsap.to("#hero-img-wrap", {
    y: -60,
    scale: 1.06,
    ease: "none",
    scrollTrigger: {
      trigger: "#hero-img-wrap",
      start: "top 90%",
      end: "bottom top",
      scrub: 0.5
    }
  });

  // Optional: glass badge parallax
  gsap.to("#hero-img-wrap .absolute", {
    y: -20,
    opacity: 1,
    ease: "none",
    scrollTrigger: {
      trigger: "#hero-img-wrap",
      start: "top 90%",
      end: "bottom top",
      scrub: 0.6
    }
  });

  // Header collapse on scroll
  const header = document.getElementById('blur-header');
  const headerContent = document.getElementById('header-content');
  let collapsed = false;

  window.addEventListener('scroll', function () {
    if (window.scrollY > 24 && !collapsed) {
      collapsed = true;
      header.classList.add('scrolled');
      if (window.gsap && headerContent) {
        gsap.to(headerContent, {paddingTop: 8, paddingBottom: 8, duration: 0.3, ease: "power2.out"});
      }
    } else if (window.scrollY <= 24 && collapsed) {
      collapsed = false;
      header.classList.remove('scrolled');
      if (window.gsap && headerContent) {
        gsap.to(headerContent, {paddingTop: 24, paddingBottom: 24, duration: 0.3, ease: "power2.out"});
      }
    }
  });

  // Animate the right-side "Why Choose Us" section on scroll
  gsap.from(".why-choose-us", {
    scrollTrigger: {
      trigger: ".why-choose-us",
      start: "top 80%",
      toggleActions: "play none none none"
    },
    x: 80,
    opacity: 0,
    duration: 1,
    ease: "power3.out"
  });

  // Animate each card in the void-cards section on scroll
  gsap.utils.toArray("#card-list .card").forEach((card, i) => {
    gsap.from(card, {
      scrollTrigger: {
        trigger: card,
        start: "top 85%",
        toggleActions: "play none none none"
      },
      y: 60,
      opacity: 0,
      duration: 0.7,
      delay: i * 0.12,
      ease: "power3.out"
    });
  });
});
