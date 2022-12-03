addEventListener('load', () => {
    generateTable();
});

function deleteTable() {
    const element = document.querySelectorAll('tbody .row');
    for (let i = 0; i < element.length; i++) {
        element[i].remove();
    }
}

function generateTable() {
    deleteTable();
    const table = document.querySelector('tbody');
    fetch('ws/getElement.php')
        .then((response) => response.json())
        .then((row) => {
            for (let i = 0; i < row.data.length; i++) {
                let rows = `<tr id="${i}" class="row">
                                <td><button class="button" onclick="removeRow(${row.data[i].id})">X</button><button id="showModal" onclick="editRow(this, ${row.data[i].id})">Edit</button></td>
            					<td id="name${i}">${row.data[i].nombre}</td>
            					<td id="description${i}">${row.data[i].descripcion}</td>
            					<td id="serial${i}">${row.data[i].nserie}</td>
                                <td id="status${i}">${row.data[i].estado}</td>
                                <td id="priority${i}">${row.data[i].prioridad}</td>
            				</tr>`;
                table.innerHTML += rows;
            }
        });
}


function removeRow(id) {
    Swal.fire({
        title: '¿Seguro que quieres eliminar el elemento?',
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        icon: 'question'
    }).then((result) => {
        if (result.isConfirmed) {
            removeFetch(id);
        }
    });
}

function removeFetch(id) {
    fetch('ws/deleteElement.php/?id=' + id)
        .then((response) => response.json())
        .then((removed) => {
            if (removed.success) {
                generateTable();
                Swal.fire('Elemento eliminado correctamente', '', 'success');
            } else {
                Swal.fire('El elemento no ha podido ser eliminado debido a un error', '', 'error');
            }
        });
}

function editRow(button, id) {
    const formModal = document.querySelector('#formModal');
    const closeBtn = document.querySelector('#closeBtn');

    selectedRow = button.parentElement.parentElement;

    formModal.classList.add('showModal');

    closeBtn.addEventListener('click', () => {
        formModal.classList.remove('showModal');
    });

    // Valores al abrir el formulario

    document.getElementById("name").value = selectedRow.cells[1].innerText;
    document.getElementById("description").value = selectedRow.cells[2].innerText;
    document.getElementById("serial").value = selectedRow.cells[3].innerText;

    const active = document.getElementById('status');

    if (selectedRow.cells[4].innerText.toLowerCase() === 'activo') {
        active.checked = true;
    } else {
        active.checked = false;
    }

    const low = document.getElementById('low');
    const medium = document.getElementById('medium');
    const high = document.getElementById('high');

    low.checked = false;
    medium.checked = false;
    high.checked = false;

    if (selectedRow.cells[5].innerText.toLowerCase() === 'alta') {
        high.checked = true;
    } else if (selectedRow.cells[5].innerText.toLowerCase() === 'media') {
        medium.checked = true;
    } else if (selectedRow.cells[5].innerText.toLowerCase() === 'baja') {
        low.checked = true;
    }

    const form = document.getElementById('form');

    // Evento tras guardar los datos
    form.addEventListener('submit', (e) => {

        e.preventDefault();

        Swal.fire({
            title: '¿Seguro que quieres guardar el elemento?',
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            icon: 'question'
        }).then((result) => {
            if (result.isConfirmed) {
                saveFetch(form, id);
                formModal.classList.remove('showModal');
            }
        })
    });
}

function saveFetch(form, id) {

    let data = new FormData(form);

    fetch('ws/modifyElement.php?id=' + id, {
            method: 'POST',
            body: data
        })
        .then((response) => response.json())
        .then((json) => {
            if (json.success) {
                generateTable();
                Swal.fire('Elemento guardado correctamente', '', 'success');
            } else {
                Swal.fire('El elemento no ha podido ser modificado debido a un error', '', 'error');
            }
        });
}

// function filterTable() {
//     const filter = document.getElementById('filter');
//     const search = filter.value.toLowerCase();

//     if (search.length >= 3) {
//         const filteredObjects = objectArray.filter((data) => {
//             return (
//                 data.name.toLowerCase().includes(search) ||

//                 data.description.toLowerCase().includes(search) ||

//                 data.serial.toLowerCase().includes(search) ||

//                 data.status.toLowerCase().includes(search) ||

//                 data.priority.toLowerCase().includes(search)
//             );
//         });
//         generateTable(filteredObjects);
//     } else {
//         generateTable(objectArray);
//     }
// }