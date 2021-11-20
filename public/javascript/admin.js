let user = document.getElementById('user')
let password = document.getElementById('password')
let loginInto = document.getElementById('login-into')
let painelDashboard = document.getElementById('painel-dashboard')
let logged = document.getElementById('logged')
let msgErr = document.getElementById('message-error')
let formLogged = document.getElementById('form-logged')
let dataUpdateBlock = document.getElementById('data-update')
let saveUpdateData = document.getElementById('save-update-data')
let saveUpdateImg = document.getElementById('save-update-img')

if (loginInto) {
    loginInto.addEventListener('click', (event) => {
        event.preventDefault()
        let data = {
            user: user.value,
            password: password.value,
        }
        fetch(`${url}/admin/login-into`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
            .then(response => { return response.json() })
            .then(json => {
                if (json.status === 'success') {
                    window.location.href = `${url}/admin`
                }
                if (json.status === 'error') {
                    msgErr.style.display = 'block'
                    msgErr.innerHTML = json.msg
                }
            })
            .catch(error => console.error(error))
    })
}
let spinLoadUpdate = document.getElementById('spin-load-update')
const loadData = () => {
    fetch(`${url}/api/data`, {
        method: 'GET',
    })
        .then(response => { return response.json() })
        .then(json => {
            if (spinLoadUpdate) {
                spinLoadUpdate.style.display = 'none';
            }
            if (logged) {
                if (json.status === 'success' && json.total === null) {
                    logged.innerHTML = json.data
                } else {
                    json.data.forEach(items => {
                        logged.innerHTML +=
                            `<div class="card">
                                    <div class="img-card">
                                        <img src="${items.front_cover}">
                                    </div>
                                <div class="info-card">
                                    <h3>Informações iniciais</h3>
                                    <p>id: ${items.id}</p>
                                    <p>album: ${items.name}</p>
                                    <p class="p">
                                    <a href="${url}/admin/album-update?id=${items.id}"><button class="btn btn-options">editar</button></a>
                                    <button class="btn btn-options" onclick="dataDelete(${items.id})">apagar</button>
                                    </p>
                                </div>
                                </div>`
                    })
                }
            }
        })
        .catch(error => console.log(error))
}

loadData()

let spinUpdate = document.getElementById('spin-update')
let id = document.getElementById('id-album-update')
if (id) {
    const getDataUpdate = () => {
        spinUpdate.style.display = 'block'
        fetch(`${url}/api/data/?id=${id.value}`, {
            method: 'GET',
        })
            .then(response => { return response.json() })
            .then(json => {
                spinUpdate.style.display = 'none';
                dataUpdateBlock.style.display = 'block'
                json.data.forEach(items => {
                    dataUpdate(items.front_cover, items.id, items.name, items.band, items.year, items.genre, items.tracks)
                })
            })
            .catch(error => console.error(error))
    }
    getDataUpdate()
}

let imgUpdate = document.getElementById('img-update')
let nameUpdate = document.getElementById('name-update')
let bandUpdate = document.getElementById('band-update')
let yearUpdate = document.getElementById('year-update')
let genreUpdate = document.getElementById('genre-update')
let tracksUpdate = document.getElementById('tracks-update')
let idUpdate = undefined
let imgPreviewUpdate = document.getElementById('image-preview-update')

const dataUpdate = (img, id, name, band, year, genre, tracks) => {
    idUpdate = id
    imgUpdate.src = img
    nameUpdate.value = name
    bandUpdate.value = band
    yearUpdate.value = year
    genreUpdate.value = genre
    tracksUpdate.value = tracks
    previewImgUpdate()
}

const previewImgUpdate = () => {
    imgPreviewUpdate.addEventListener('change', () => {
        const file = imgPreviewUpdate.files[0]
        if (file) {
            const reader = new FileReader()
            reader.onload = () => {
                const result = reader.result
                imgUpdate.src = result
            }
            reader.readAsDataURL(file)
        }
    })
}

let messageSuccessUpdate = document.getElementById('message-success-update')
let messageErrorUpdate = document.getElementById('message-error-update')

if (saveUpdateImg) {
    saveUpdateImg.addEventListener('click', (event) => {
        event.preventDefault()
        if (imgPreviewUpdate.files[0] === undefined) {
            alert('Para alterar a capa do album, é necessário selecionar uma imagem primeiro!')
        }
        if (imgPreviewUpdate.files[0] !== undefined) {
            spinUpdate.style.display = 'block';
            const formData = new FormData()
            formData.append('imgUpdate', imgPreviewUpdate.files[0])
            formData.append('idImg', idUpdate)
            fetch(`${url}/set-data`, {
                method: 'POST',
                body: formData,
            })
                .then(response => { return response.json() })
                .then(json => {
                    console.log(json)
                    spinUpdate.style.display = 'none';
                    if (json.status === 'success') {
                        messageSuccessUpdate.style.display = 'block';
                        messageSuccessUpdate.innerText = json.data
                    }
                    if (json.status === 'error') {
                        messageErrorUpdate.style.display = 'block';
                        messageSuccessUpdate.innerText = json.data
                    }
                    window.scrollTo({
                        top: 0,
                        left: 0,
                        behavior: "smooth"
                    })
                })
                .catch(error => console.error(error))
        }
    })
}

if (saveUpdateData) {
    saveUpdateData.addEventListener('click', (event) => {
        event.preventDefault()
        spinUpdate.style.display = 'block';
        let data = {
            id: idUpdate,
            name: nameUpdate.value,
            band: bandUpdate.value,
            year: yearUpdate.value,
            genre: genreUpdate.value,
            tracks: tracksUpdate.value
        }

        fetch(`${url}/api/data`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        })
            .then(response => { return response.json() })
            .then(json => {
                console.log(json)
                spinUpdate.style.display = 'none';
                if (json.status === 'success') {
                    messageSuccessUpdate.style.display = 'block';
                    messageSuccessUpdate.innerText = json.data
                }
                if (json.status === 'error') {
                    messageErrorUpdate.style.display = 'block';
                    messageSuccessUpdate.innerText = json.data
                }
                window.scrollTo({
                    top: 0,
                    left: 0,
                    behavior: "smooth"
                })
            })
            .catch(error => console.error(error))
    })
}

const signOut = () => {
    if (spinUpdate) {
        spinUpdate.style.display = 'block'
    }
    fetch(`${url}/admin/sign-out`, {
        method: 'GET',
    })
        .then(response => { return response.json() })
        .then(json => {
            if (json.status === 'ok') {
                if (spinUpdate) {
                    spinUpdate.style.display = 'none'
                }
                window.location.href = `${url}/admin`
            }
        })
        .catch(error => console.error(error))
}

const backUpdate = () => {
    window.location.href = `${url}/admin`
}

let dataDeleteModal = document.getElementById('data-delete-modal')
let idDelete = document.getElementById('id-delete')
let contentModalInfo = document.getElementById('content-modal-info')
let spinDelete = document.getElementById('spin-delete')
let idDeleteAlbum = null;
let btnCloseModal = document.getElementById('btn-close-modal')
let messageSuccessDelete = document.getElementById('message-success-delete')
let messageErrorDelete = document.getElementById('message-error-delete')

const dataDelete = (id) => {
    dataDeleteModal.style.display = 'flex'
    idDelete.innerHTML = `Id: ${id}`
    idDeleteAlbum = id
}

const yesDelete = () => {
    contentModalInfo.style.display = 'none'
    spinDelete.style.display = 'block'
    let data = {
        id: idDeleteAlbum
    }
    fetch(`${url}/api/data`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
        .then(response => { return response.json() })
        .then(json => {
            console.log(json)
            spinDelete.style.display = 'none';
            if (json.status === 'success') {
                messageSuccessDelete.style.display = 'block';
                messageSuccessDelete.innerText = json.data
                btnCloseModal.style.display = 'block';
            }
            if (json.status === 'error') {
                messageSuccessDelete.style.display = 'block';
                messageSuccessDelete.innerText = json.data
                btnCloseModal.style.display = 'block';
            }
            window.scrollTo({
                top: 0,
                left: 0,
                behavior: "smooth"
            })
        })
        .catch(error => console.error(error))
}
const noDelete = () => {
    dataDeleteModal.style.display = 'none'
}

const closeDeleteModal = () => {
    window.location.href = `${url}/admin`
}