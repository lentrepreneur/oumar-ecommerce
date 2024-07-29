const products = document.querySelectorAll('[data-product-id]')

products.forEach((product) => {
  const productId = product.getAttribute('data-product-id')
  const counterElement = product.querySelector('.counter')
  const addButton = product.querySelector('.addBtn')
  const removeButton = product.querySelector('.removeBtn')

  const addToCartButton = document.querySelector('.addToCartBtn')

  // Charger la valeur du compteur depuis le localStorage
  let counterValue = localStorage.getItem('counterValue_' + productId)
  if (counterValue === null) {
    counterValue = 1 // Valeur initiale si rien n'est stocké
  } else {
    counterValue = parseInt(counterValue)
  }
  counterElement.textContent = counterValue

  addButton.addEventListener('click', () => {
    counterValue++
    counterElement.textContent = counterValue
    localStorage.setItem('counterValue_' + productId, counterValue)
  })

  removeButton.addEventListener('click', () => {
    if (counterValue > 1) {
      counterValue--
      counterElement.textContent = counterValue
      localStorage.setItem('counterValue_' + productId, counterValue)
    }
  })

  addToCartButton.addEventListener('click', () => {
    const url = `/cart/add/${productId}`
    const data = { quantity: counterValue }

    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert(
            'Produit ajouté au panier ! Quantité totale dans le panier : ' +
              data.cart[productId]
          )
        } else {
          alert("Erreur lors de l'ajout au panier.")
        }
      })
      .catch((error) => {
        alert("Erreur lors de l'ajout au panier.")
      })
  })
})

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
