document.addEventListener('DOMContentLoaded', function () {
  const searchButton = document.getElementById('search-button')
  const mobileSearch = document.getElementById('mobile-search')

  // const menuButton = document.getElementById('menu-button')
  // const menuDrawer = document.getElementById('menu-drawer-id')

  const menuButton = document.getElementById('menu-button')
  const overlay = document.getElementById('overlay')
  const closeButton = document.getElementById('close-button')
  const body = document.body

  const carousel = document.getElementById('carousel')
  const prevButton = document.getElementById('prev-button')
  const nextButton = document.getElementById('next-button')
  const totalSlides = document.querySelectorAll('#carousel > div').length
  let currentSlide = 0
  let autoSlideInterval

  searchButton.addEventListener('click', function (event) {
    console.log('is clicking?')
    event.stopPropagation()
    mobileSearch.classList.toggle('hidden')
    mobileSearch.classList.toggle('flex')
  })

  // menuButton.addEventListener('click', function (event) {
  //   event.stopPropagation()
  //   menuDrawer.classList.toggle('hidden')
  //   menuDrawer.classList.toggle('show')
  // })

  menuButton.addEventListener('click', () => {
    overlay.classList.remove('hidden')
    body.classList.add('no-scroll')
  })

  closeButton.addEventListener('click', () => {
    overlay.classList.add('hidden')
    body.classList.remove('no-scroll')
  })

  // Optional: Close the overlay when clicking outside of it
  overlay.addEventListener('click', (e) => {
    if (e.target === overlay) {
      overlay.classList.add('hidden')
      body.classList.remove('no-scroll')
    }
  })

  document.addEventListener('click', function (event) {
    if (
      !mobileSearch.contains(event.target) &&
      !searchButton.contains(event.target)
    ) {
      mobileSearch.classList.add('hidden')
      mobileSearch.classList.remove('show')
    }

    // if (
    //   !menuButton.contains(event.target) &&
    //   menuDrawer.contains(event.target)
    // ) {
    //   menuDrawer.classList.toggle('hidden')
    // }
  })

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

function updateValue(value) {
  document.getElementById('rangeValue').innerText = value
}

const allHoverImages = document.querySelectorAll('.hover-container div img')
const imgContainer = document.querySelector('.img-container')

window.addEventListener('DOMContentLoaded', () => {
  allHoverImages[0].parentElement.classList.add('active')
})

allHoverImages.forEach((image) => {
  image.addEventListener('mouseover', () => {
    imgContainer.querySelector('img').src = image.src
    resetActiveImg()
    image.parentElement.classList.add('active')
  })
})

function resetActiveImg() {
  allHoverImages.forEach((img) => {
    img.parentElement.classList.remove('active')
  })
}

function openTab(evt, tabName) {
  var i, tabContent, tabLinks

  // Hide all tab content
  tabContent = document.getElementsByClassName('tab-content')
  for (i = 0; i < tabContent.length; i++) {
    tabContent[i].style.display = 'none'
  }

  // Deactivate all tab links
  tabLinks = document.getElementsByClassName('tab')
  for (i = 0; i < tabLinks.length; i++) {
    tabLinks[i].classList.remove('active')
  }

  // Show the selected tab content and mark the tab as active
  document.getElementById(tabName).style.display = 'block'
  evt.currentTarget.classList.add('active')

  // Add animation to the tab content
  var activeTabContent = document.getElementById(tabName)
  activeTabContent.classList.add('fadeIn')
}
document.addEventListener('DOMContentLoaded', function () {
  // Trigger click event on tab 1 button
  document.querySelector('.tab').click()
})
