var imageCounter = document.querySelectorAll('button[data-action="delete"]').length;

document.getElementById('add-image').addEventListener('click', function(){
    const addImagesElement = document.getElementById('ad_images')
    // Récupération du prototype des entrées
    const tmpl = addImagesElement.dataset.prototype.replaceAll('__name__', imageCounter)
    // J'injecte le code dans la div
    addImagesElement.innerHTML += tmpl
    imageCounter++

    // Je gere le bouton supprimé
    handleDeleteButtons()
    handleRefresh()
})

function handleDeleteButtons(){
    document.querySelectorAll('button[data-action="delete"]').forEach(function (button) {
        button.addEventListener('click', function () {
            const target = this.dataset.target
            document.getElementById(target).remove()
        })
    })
}

function handleRefresh(){
    document.querySelectorAll('#ad_images input').forEach(function (input) {
        input.addEventListener('change', function (event) {
            document.getElementById(event.target.id).setAttribute('value', event.target.value)
        })
    })
}

handleDeleteButtons()