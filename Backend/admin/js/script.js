function deleteUser(user)
{
    fetch('../../Backend/admin/php/delete_user.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({username: user})
    })
}

function getUsers()
{
    fetch()
}