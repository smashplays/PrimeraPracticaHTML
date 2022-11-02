addEventListener('load', () => {
    insertHtmlFetch();
    // insertHtmlXml();
});

function insertHtmlFetch() {
    fetch('nav.html')
        .then(response => response.text())
        .then((html) => {
            const figure = document.getElementById('figure');
            figure.insertAdjacentHTML('afterend', html);
            const form = document.getElementsByClassName('form');
            const table = document.getElementsByClassName('table');
            const gallery = document.getElementsByClassName('gallery');
            const definition = document.getElementsByClassName('definition');
            const aboutus = document.getElementsByClassName('aboutus');

            if (window.location.href.includes('form')) {
                form[0].id = 'formItem';
            } else if (window.location.href.includes('table')) {
                table[0].id = 'tableItem';
            } else if (window.location.href.includes('gallery')) {
                gallery[0].id = 'galleryItem';
            } else if (window.location.href.includes('definition')) {
                definition[0].id = 'definitionItem';
            } else if (window.location.href.includes('aboutus')) {
                aboutus[0].id = 'aboutusItem';
            } else {
                console.log('La ruta que intenta acceder no es correcta');
            }
        });
};

function insertHtmlXml() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "nav.html");
    xhr.send();
    xhr.onreadystatechange = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            const figure = document.getElementById('figure');
            figure.insertAdjacentHTML('afterend', xhr.responseText);
            const form = document.getElementsByClassName('form');
            const table = document.getElementsByClassName('table');
            const gallery = document.getElementsByClassName('gallery');
            const definition = document.getElementsByClassName('definition');
            const aboutus = document.getElementsByClassName('aboutus');

            if (window.location.href.includes('form')) {
                form[0].id = 'formItem';
            } else if (window.location.href.includes('table')) {
                table[0].id = 'tableItem';
            } else if (window.location.href.includes('gallery')) {
                gallery[0].id = 'galleryItem';
            } else if (window.location.href.includes('definition')) {
                definition[0].id = 'definitionItem';
            } else if (window.location.href.includes('aboutus')) {
                aboutus[0].id = 'aboutusItem';
            } else {
                console.log('La ruta que intenta acceder no es correcta');
            }
        }
    }
}