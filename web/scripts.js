function deletePlayer(id) {
    if(confirm("Are you sure you want to delete this player?"))
    {
        location.href = "player_delete.php?id=" + id;
    }
}

function editPlayer(id, round) {
    location.href = "player_edit.php?id=" + id + "&round=" + round;
}

function updateStatus(id, status, round) {
    var timestamp = Date.now();
    location.href = "assignment_update_status.php?id=" + id + "&status=" + status + "&round=" + round + "&time=" + timestamp;
}

function showHistory(id, round) {
    location.href = "player_history.php?id=" + id + "&round=" + round;
}

function newRound() {
    location.href = "new_round.php";
}
