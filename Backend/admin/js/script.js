function checkUserExistance(user)
{
    return fetch('../../Backend/admin/php/check_user_existance.php',
        {
            method: 'POST',
            headers: 
            {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({username: user})
        }
    )
    .then(response => response.json())
    .then(data => {
        if(!data.success)
        {
            alert(">> Error checking user existance >>" + data.error);
        }
        return data.status;
    })
    .catch(error =>
    {
        console.log(error);
        return false;
    }
    );
}

function deleteUser(user)
{
    fetch('../../Backend/admin/php/delete_user.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({username: user})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('>> Operation /DeleteUser/ Success');
        } else {
            alert('>> Error during operation /DeleteUser/ >>' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function getUsers(user) {
    return fetch('../../Backend/admin/php/retrive_users.php', 
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({username: user})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                return data.users;
            }
            console.log(data.error)
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function setPoints(user, pts)
{
    fetch('../../Backend/admin/php/set_points.php',
        {
            method: 'POST', 
            headers: {
                'Content-Type': 'application/json'
            }, 
            body: JSON.stringify({username: user, points: pts})
        }
    )
    .then(response => response.json())
    .then(data =>
    {
        console.log(data.success);
        if(data.success)
        {
            alert(">> Operation - Set Points - Success");
        } else
        {
            alert(">> Error during operation - Set Points - >>" + data.error);
        }
    }
    )
    .catch(error =>
    {
        console.log("Error: ", error);
    }
    );
}

function manageAdmin(user, priv)
{
    fetch('../../Backend/admin/php/manage_admin.php', 
        {
            method: 'POST',
            headers:
            {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({username: user, privilege: priv})
        }
    )
    .then(response => response.json())
    .then(data => {
        if(data.success)
        {
            alert(">> Operation - Manage Privileges - Success");
        }
        else
        {
            alert(">> Error during operation - Manage Privileges - >>" + data.error)
        }
    }
    )
    .catch(error =>
    {
        console.log("Error: " + error);
    }
    )
}