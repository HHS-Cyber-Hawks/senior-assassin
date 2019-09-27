function deletePlayer(id) {
    if(confirm("Are you sure you want to delete this player?"))
    {
        location.href = "delete.php?id=" + id;
    }
}

function editPlayer(id) {
    location.href = "edit.php?id=" + id;
}
