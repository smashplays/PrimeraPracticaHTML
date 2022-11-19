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
            checkNavElement();
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
            checkNavElement();
        }
    }
}

function checkNavElement() {
    const item = document.getElementsByClassName('item');

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
        const about_us = document.getElementById('aboutUsItem');
        about_us.style = 'background-color: white;';
    }
}