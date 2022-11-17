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
        item[0].id = 'formItem';
    } else if (window.location.href.includes('table')) {
        item[1].id = 'tableItem';
    } else if (window.location.href.includes('gallery')) {
        item[2].id = 'galleryItem';
    } else if (window.location.href.includes('definition')) {
        item[3].id = 'definitionItem';
    } else if (window.location.href.includes('aboutus')) {
        item[4].id = 'aboutusItem';
    }
}