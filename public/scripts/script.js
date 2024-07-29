document.addEventListener('DOMContentLoaded', function () {
  const searchButton = document.getElementById('search-button')
  const mobileSearch = document.getElementById('mobile-search')

  const menuButton = document.getElementById('menu-button')
  const overlay = document.getElementById('overlay')
  const closeButton = document.getElementById('close-button')
  const body = document.body

  searchButton.addEventListener('click', function (event) {
    console.log('is clicking?')
    event.stopPropagation()
    mobileSearch.classList.toggle('hidden')
    mobileSearch.classList.toggle('flex')
  })

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
  })

  // -------------------
  function updateValue(value) {
    document.getElementById('rangeValue').innerText = value
  }

  // -----cart

  // ---carousel

  // ---profile update
})
