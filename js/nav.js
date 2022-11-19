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
    const form = document.getElementById('formItem');
    const table = document.getElementById('tableItem');
    const gallery = document.getElementById('galleryItem');
    const definition = document.getElementById('definitionItem');
    const about_us = document.getElementById('aboutUsItem');


    if (window.location.href.includes('form')) {
        form.style = 'background-color: white;';
    } else if (window.location.href.includes('table')) {
        table.style = 'background-color: white;';
    } else if (window.location.href.includes('gallery')) {
        gallery.style = 'background-color: white;';
    } else if (window.location.href.includes('definition')) {
        definition.style = 'background-color: white;';
    } else if (window.location.href.includes('aboutus')) {
        about_us.style = 'background-color: white;';
    }
}