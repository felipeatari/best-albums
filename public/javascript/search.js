const url = 'http://192.168.0.100/mini-project/best-albums'

let contentCard = document.getElementById('content-card')
let contentAlbum = document.getElementById('content-album')
let spinHome = document.getElementById('spin-home')

window.onload = (event) => {
    event.preventDefault()
    fetch(`${url}/api/data`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
    })
        .then(response => { return response.json() })
        .then(json => {
            if (spinHome) {
                spinHome.style.display = 'none'
            }
            if (contentCard) {
                if (json.status === 'success' && json.total === null) {
                    contentCard.innerHTML = json.data
                } else {
                    json.data.forEach(items => {
                        contentCard.innerHTML +=
                        renderAlbum(items)
                    })
                }
            }
        })
        .catch(error => console.log(error))
}

let idOpenAlbum = document.getElementById('id-open-album')
let spinOpenAlbum = document.getElementById('spin-open-album')
if (idOpenAlbum) {
    const openAlbum = (id) => {
        contentAlbum.style.display = 'none'
        spinOpenAlbum.style.display = 'block'
        let imgAlbum = document.getElementById('img-album')
        let itemsAlbum = document.getElementById('items-album')
        fetch(`${url}/api/data/?id=${id}`, {
            method: 'GET',
        })
            .then(response => { return response.json() })
            .then(json => {
                spinOpenAlbum.style.display = 'none'
                contentAlbum.style.display = 'flex'
                json.data.forEach(items => {
                    imgAlbum.setAttribute('src', `${items.front_cover}`)
                    itemsAlbum.innerHTML +=
                        `<p class="p-home">Nome: ${items.name}</p>
                         <p class="p-home">Banda: ${items.band}</p>
                         <p class="p-home">Ano: ${items.year}</p>
                         <p class="p-home">Género: ${items.genre}</p>
                         <p class="title-tracks">Faixas:</p>
                         <textarea class="text-area tracks" readonly> ${items.tracks}</textarea>`
                })
            })
            .catch(error => console.error(error))
    }
    openAlbum(idOpenAlbum.value)
}

const back = () => {
    window.location.href = url
}

let searchAlbum = document.getElementById('search-album')
let btnBackSearch = document.getElementById('btn-back-search')
let btnSearch = document.getElementById('btn-search')
if (btnSearch) {
    btnSearch.addEventListener('click', () => {
        btnBackSearch.style.display = 'none'
        contentCard.innerHTML = ''
        spinHome.style.display = 'block'
        fetch(`${url}/api/data/?search=${searchAlbum.value}`)
            .then(result => { return result.json() })
            .then(json => {
                if (spinHome) {
                    spinHome.style.display = 'none'
                }
                if (contentCard) {
                    if (json.status !== 'success') {
                        btnBackSearch.style.display = 'block'
                        contentCard.innerHTML +=
                        `
                            <div class="no-success">
                                <p>${json.data}</p>
                            </div>
                        `
                    } else {
                        btnBackSearch.style.display = 'block'
                        json.data.forEach(items => {
                            contentCard.innerHTML += renderAlbum(items)
                        })
                    }
                }

            })
            .catch(error => console.log(error))
    })
}

const renderAlbum = (items) => {
    return `<div class="card">
    <div class="img-card">
        <img src="${items.front_cover}">
    </div>
    <div class="info-card">
        <h3>Informações iniciais</h3>
        <p>album: ${items.name}</p>
        <p>band: ${items.band}</p>
        <p class="p-open-album"><a href="${url}/open-album/?id=${items.id}" class="open-album">Ver completo</a></p>
    </div>
</div>`
}