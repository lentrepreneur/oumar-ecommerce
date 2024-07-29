document.addEventListener('DOMContentLoaded', function () {
  const carousel = document.getElementById('carousel')
  const prevButton = document.getElementById('prev-button')
  const nextButton = document.getElementById('next-button')
  const totalSlides = document.querySelectorAll('#carousel > div').length

  let currentSlide = 0
  let autoSlideInterval

  function updateCarousel() {
    const translateXValue = -currentSlide * 100 // déplacer de 100vw par slide
    carousel.style.transform = `translateX(${translateXValue}vw)`
  }

  function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides
    updateCarousel()
  }

  function prevSlide() {
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides
    updateCarousel()
  }

  nextButton.addEventListener('click', function () {
    nextSlide()
    resetAutoSlide()
  })

  prevButton.addEventListener('click', function () {
    prevSlide()
    resetAutoSlide()
  })

  function startAutoSlide() {
    autoSlideInterval = setInterval(nextSlide, 5000)
  }

  function resetAutoSlide() {
    clearInterval(autoSlideInterval)
    startAutoSlide()
  }

  // startAutoSlide()
})
