document.addEventListener('DOMContentLoaded', function () {
    let slideIndex = 0;
        const slides = document.querySelectorAll(".slide");
        const dots = document.querySelectorAll(".dot");

        function showSlide(index) {
            // Hide all slides
            slides.forEach(slide => {
                slide.style.transform = `translateX(-${index * 100}%)`;
            });

            // Update active dot
            dots.forEach(dot => dot.classList.remove("active"));
            dots[index].classList.add("active");
        }

        function nextSlide() {
            slideIndex++;
            if (slideIndex >= slides.length) {
                slideIndex = 0;
            }
            showSlide(slideIndex);
        }

        function prevSlide() {
            slideIndex--;
            if (slideIndex < 0) {
                slideIndex = slides.length - 1;
            }
            showSlide(slideIndex);
        }

        // Automatic slideshow
        setInterval(nextSlide, 5000);

        // Arrow click events
        document.querySelector('.prev').addEventListener('click', prevSlide);
        document.querySelector('.next').addEventListener('click', nextSlide);

        // Dot click events
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                slideIndex = index;
                showSlide(slideIndex);
            });
        });

        // Initial slide display
        showSlide(slideIndex);
    });
