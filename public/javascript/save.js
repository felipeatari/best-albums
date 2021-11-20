let btnSubmitOne = document.getElementById('btn-submit-one')
let btnSubmitTwo = document.getElementById('btn-submit-two')
let btnConfirm = document.getElementById('btn-confirm')
let formOne = document.getElementById('form-on')
let formTwo = document.getElementById('form-two')
let confirmSave = document.getElementById('confirm')
let contentOk = document.getElementById('content-ok')
if (contentOk) {
    contentOk.style.display = 'none'
}
let spinSaveOn = document.getElementById('spin-save-one')
let spinSaveTwo = document.getElementById('spin-save-two')

if (btnSubmitOne) {
    btnSubmitOne.addEventListener('click', (event) => {
        event.preventDefault()
        spinSaveOn.style.display = 'block';
        let data = {
            name: document.getElementById('name').value,
            band: document.getElementById('band').value,
            year: document.getElementById('year').value,
            genre: document.getElementById('genre').value,
            tracks: document.getElementById('tracks').value,
        }
        fetch(`${url}/set-data`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        })
            .then(response => { return response.json() })
            .then(json => {
                spinSaveOn.style.display = 'none';
                if (json.status === 'error') {
                    alert('Os dados não foram recebidos com sucesso!')
                }
                if (json.status === 'success') {
                    spinSaveOn.style.display = 'none';
                    formTwo.style.display = 'flex'
                }
                window.scrollTo(0,document.body.scrollHeight)
            })
            .catch(error => console.error(error))
    })

    let image = document.getElementById('image')
    let preview = document.getElementById('preview')
    let add = document.getElementById('add')
    let contentPreview = document.getElementById('content-preview')
    contentPreview.addEventListener('click', () => {
        image.click()
    })
    image.addEventListener('change', () => {
        preview.style.display = 'block'
        add.style.display = 'none'
        const file = image.files[0]
        if (file) {
            const reader = new FileReader()
            reader.onload = () => {
                const result = reader.result
                preview.src = result
            }
            reader.readAsDataURL(file)
        }
    })

    btnSubmitTwo.addEventListener('click', (event) => {
        event.preventDefault()
        spinSaveTwo.style.display = 'block';
        if (image.files[0] === undefined) {
            spinSaveTwo.style.display = 'none';
            alert('A imagem não foi recebida com sucesso!')
        }
        if (image.files[0] !== undefined) {
            const formData = new FormData()
            formData.append('image', image.files[0])
            fetch(`${url}/set-data`, {
                method: 'POST',
                body: formData,
            })
                .then(response => { return response.json() })
                .then(json => {
                    if (json.status === 'success') {
                        spinSaveTwo.style.display = 'none';
                        confirmSave.style.display = 'flex'
                    }
                    window.scrollTo(0, document.body.scrollHeight)
                })
                .catch(error => console.error(error))
        }
    })

    btnConfirm.addEventListener('click', (event) => {
        event.preventDefault()
        let messageSuccess = document.getElementById('message-success')
        let messageError = document.getElementById('message-error')
        let contentConfirm = document.getElementById('content-confirm')
        const formData = new FormData()
        formData.append('confirm', btnConfirm.value)
        fetch(`${url}/api/data`, {
            method: 'POST',
            body: formData,
        })
            .then(response => { return response.json() })
            .then(json => {
                if (json.status === 'error') {
                    messageSuccess.style.display = 'none'
                    messageError.style.display = 'block'
                    messageError.innerHTML = json.data
                    contentConfirm.style.display = 'none'
                    contentOk.style.display = 'block'
                }
                if (json.status === 'success') {
                    messageError.style.display = 'none'
                    messageSuccess.style.display = 'block'
                    messageSuccess.innerHTML = json.data
                    contentConfirm.style.display = 'none'
                    contentOk.style.display = 'block'
                }
            })
            .catch(error => console.error(error))
    })
}

const confirmOk = ()=>{
    window.location.href = `${url}/cadastrar`
}