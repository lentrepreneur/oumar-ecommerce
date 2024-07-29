document.addEventListener('DOMContentLoaded', function () {
  const searchButton = document.getElementById('search-button')
  const searchOverlay = document.getElementById('overlaySearch')

  const menuButton = document.getElementById('menu-button')
  const overlay = document.getElementById('overlay')
  const closeButton = document.getElementById('close-button')
  const body = document.body

  // searchButton.addEventListener('click', function (event) {
  //   console.log('is clicking?')
  //   event.stopPropagation()
  //   mobileSearch.classList.toggle('hidden')
  //   mobileSearch.classList.toggle('flex')
  // })

  menuButton.addEventListener('click', () => {
    overlay.classList.remove('hidden')
    body.classList.add('no-scroll')
  })

  closeButton.addEventListener('click', () => {
    overlay.classList.add('hidden')
    body.classList.remove('no-scroll')
  })

  overlay.addEventListener('click', (e) => {
    if (e.target === overlay) {
      overlay.classList.add('hidden')
      body.classList.remove('no-scroll')
    }
  })

  // -----Search
  searchButton.addEventListener('click', () => {
    searchOverlay.classList.remove('hidden')
    body.classList.add('no-scroll')
  })

  searchOverlay.addEventListener('click', (e) => {
    if (e.target === searchOverlay) {
      searchOverlay.classList.add('hidden')
      body.classList.remove('no-scroll')
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
