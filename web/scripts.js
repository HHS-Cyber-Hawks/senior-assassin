function deletePlayer(id) {
    if(confirm("Are you sure you want to delete this player?"))
    {
        location.href = "player_delete.php?id=" + id;
    }
}

function editPlayer(id) {
    location.href = "player_edit.php?id=" + id;
}
