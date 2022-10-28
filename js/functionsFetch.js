const get_url = "ws/getElement.php";
const delete_url = "ws/deleteElement.php";
const create_url = "ws/createElement2.php";
const modify_url = "ws/modifyElement.php";


async function getAPI(url) {

    const response = await fetch(url);

    let data = await response.json();
    console.log(data);
}

getAPI(get_url);