const form = document.getElementById('form');

form.addEventListener('submit', (e) => {
    e.preventDefault();
    Swal.fire({
        title: '¿Seguro que quieres crear el elemento?',
        showCancelButton: true,
        confirmButtonText: 'Crear',
        icon: 'question'
    }).then((result) => {
        if (result.isConfirmed) {
            createFetch();
        }
    })
})

function createFetch() {
    let data = new FormData(form);

    fetch('ws/createElement2.php', {
            method: 'POST',
            body: data
        })
        .then(response => response.json())
        .then((created) => {
            if (created.success) {
                Swal.fire('Elemento creado correctamente', '', 'success')
            } else {
                Swal.fire('El elemento no ha podido ser creado debido a un error', '', 'error');
            }
        });
}