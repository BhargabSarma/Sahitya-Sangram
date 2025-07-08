// Complete JavaScript code for the shelf book animations
document.addEventListener("DOMContentLoaded", function () {
  // Register GSAP plugins
  gsap.registerPlugin(ScrollTrigger, CustomEase);
  CustomEase.create("bookEase", "0.25, 1, 0.5, 1");

  // Scroll-in animation for each shelf book item
  gsap.utils.toArray(".shelf-books__item").forEach((book, i) => {
    gsap.from(book, {
      scrollTrigger: {
        trigger: book,
        start: "top 85%",
        toggleActions: "play none none none"
      },
      y: 60,
      opacity: 0,
      duration: 0.8,
      delay: i * 0.1,
      ease: "power3.out"
    });
  });

  // Apply hover animations to each shelf book item
  const books = document.querySelectorAll(".shelf-books__item");
  books.forEach((book) => {
    const hitbox = book.querySelector(".shelf-books__hitbox");
    const bookImage = book.querySelector(".shelf-books__image");
    const bookEffect = book.querySelector(".shelf-books__effect");
    const pages = book.querySelectorAll(".shelf-books__page");
    const bookLight = book.querySelector(".shelf-books__light");

    // Set initial state for cover image and light effect
    gsap.set(bookImage, {
      boxShadow:
        "0 10px 20px rgba(0,0,0,0.15), 0 30px 45px rgba(0,0,0,0.12), 0 60px 80px rgba(0,0,0,0.1)"
    });

    gsap.set(bookLight, {
      opacity: 0.1,
      rotateY: 0
    });

    // Set initial state for pages - all stacked at 0
    gsap.set(pages, {
      x: 0
    });

    // Create hover animation timeline (paused by default)
    const hoverIn = gsap.timeline({ paused: true });

    // Add the book cover animation
    hoverIn.to(
      bookImage,
      {
        duration: 0.7,
        rotateY: -12,
        translateX: -6,
        scaleX: 0.96,
        boxShadow:
          "10px 10px 20px rgba(0,0,0,0.25), 20px 20px 40px rgba(0,0,0,0.2), 40px 40px 60px rgba(0,0,0,0.15)",
        ease: "bookEase"
      },
      0
    );

    // Add the book effect animation
    hoverIn.to(
      bookEffect,
      {
        duration: 0.7,
        marginLeft: 10,
        ease: "bookEase"
      },
      0
    );

    // Add the light effect animation
    hoverIn.to(
      bookLight,
      {
        duration: 0.7,
        opacity: 0.2,
        rotateY: -12,
        ease: "bookEase"
      },
      0
    );

    // Add the page animations to the same timeline
    if (pages.length) {
      hoverIn.to(
        pages[0],
        {
          x: "4px",
          duration: 0.7,
          ease: "power1.inOut"
        },
        0
      );

      hoverIn.to(
        pages[1],
        {
          x: "2px",
          duration: 0.7,
          ease: "power1.inOut"
        },
        0
      );

      hoverIn.to(
        pages[2],
        {
          x: "0px",
          duration: 0.7,
          ease: "power1.inOut"
        },
        0
      );
    }

    // Hover triggers
    if (hitbox) {
      hitbox.addEventListener("mouseenter", () => hoverIn.play());
      hitbox.addEventListener("mouseleave", () => hoverIn.reverse());
    }
  });
});
