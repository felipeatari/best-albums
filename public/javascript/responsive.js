let btnOpen = document.getElementById('btn-open')
let btnClose = document.getElementById('btn-close')
let content = document.getElementById('content')
let footer = document.getElementById('footer')
let itemsMobile = document.getElementById('items-mobile')
btnOpen.addEventListener('click', () => {
    itemsMobile.style.display = 'flex'
    content.style.display = 'none'
    footer.style.display = 'none'
})
btnClose.addEventListener('click', () => {
    itemsMobile.style.display = 'none'
    content.style.display = 'flex'
    footer.style.display = 'flex'
})
