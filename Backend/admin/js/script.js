function checkUserExistance(user)
{
    // IMPLEMETARE QUELLO CHE STA IN ADMIN
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
            // admin version (w/ alert)
            alert(">> Error checking user existance >>" + data.error);
        }
        else
        {
            return data.status;
        }
    })
    .catch(error =>
    {
        alert("Error: " + data.error);
        console.log(error);
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
    // IMPLEMENTARE AWAIT ASYNC
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

async function getLastGlobalLogins()
{
    try
    {

        const response = await fetch('../../Backend/admin/php/retrive_last_logins.php', 
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        if(!response.ok)
            window.location.href = "./internal_error.html";
    
        const data = await response.json();
    
        return data.logins;
    }
    catch(error)
    {
        window.location.href = "./internal_error.html";
    }

}

async function getLastUserLogins(user)
{
    try
    {

        const response = await fetch('../../Backend/admin/php/retrive_access_logs.php', 
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({username: user})
        });
        if(!response.ok)
            window.location.href = "./internal_error.html";
    
        const data = await response.json();
    
        return data.logins;
    }
    catch(error)
    {
        window.location.href = "./internal_error.html";
    }

}

function setPoints(user, pts)
{
    try
    {
        const response =  fetch('../../Backend/admin/php/set_points.php',
            {
                method: 'POST', 
                headers: {
                    'Content-Type': 'application/json'
                }, 
                body: JSON.stringify({username: user, points: pts})
            }
        );
        if(!response.ok)
            alert(">> Error during operation - Set Points - >>" + data.error);
        else
            alert(">> Operation - Set Points - Success");

    } catch(error)
    {
        console.log("Error: ", error);
        window.location.href = "./internal_error.html";
    }
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