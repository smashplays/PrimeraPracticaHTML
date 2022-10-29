window.onload = () => {
    insertHtml();
    console.log(window.location.href.includes('form'));
}

function insertHtml(){
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "nav.html");
    xhr.send();
    xhr.onreadystatechange = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            const figure = document.getElementById('figure');
            figure.insertAdjacentHTML('afterend', xhr.responseText);
        }
    }
}

// function modifyStyle(route, xhr){
//     if(window.location.href.includes(route)){
//         const route = xhr.getElementById(route + 'Item');
//         route.style = 'background-color: green; color: white';
//     }
// }