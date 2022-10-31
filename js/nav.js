window.onload = () => {
    insertHtml();
}

function insertHtml() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "nav.html");
    xhr.send();
    xhr.onreadystatechange = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            const figure = document.getElementById('figure');
            figure.insertAdjacentHTML('afterend', xhr.responseText);

            if (window.location.href.includes('form')) {
                const form = document.getElementById('formItem');
                form.style = 'background-color: white;';
            } else if (window.location.href.includes('table')) {
                const table = document.getElementById('tableItem');
                table.style = 'background-color: white;';
            } else if (window.location.href.includes('gallery')) {
                const gallery = document.getElementById('galleryItem');
                gallery.style = 'background-color: white;';
            } else if (window.location.href.includes('definition')) {
                const definition = document.getElementById('definitionItem');
                definition.style = 'background-color: white;';
            } else if (window.location.href.includes('aboutus')) {
                const aboutus = document.getElementById('aboutusItem');
                aboutus.style = 'background-color: white;';
            } else {
                console.log('La ruta que intenta acceder no es correcta');
            }
        }
    }
}