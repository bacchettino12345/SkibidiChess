function checkLogin()
{
    let username = document.querySelector('#username').value;
    let password = document.querySelector('#password').value;
    
    fetch('../Backend/auth/php/login_check.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({username: username, password: password})
    })
    .then(response => response.text())  
    .then(data => {
        console.log(data);
        if(data == true)
        {
            window.location.href = './index.php';
        }
        else
        {
            document.querySelector('#nvc').style.display = 'none';
            document.querySelector('#wce').style.display = 'block';
        }
    })
}

function checkUserExistanceOnRegister(user)
{
    return fetch('../Backend/auth/php/check_user_existance_npv.php',
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
            // redirect
        }
        else
        {
            return data.status;
        }
    })
    .catch(error =>
    {
        // red
    }
    );
}

function registerAccount()
{
    // not used yet
    let username = document.querySelector('#username').value;
    let password = document.querySelector('#password').value;
    
    fetch('../Backend/auth/php/login_check.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({username: username, password: password})
    })
    .then(response => response.text())  
    .then(data => {
        console.log(data);
        if(data == true)
        {
            window.location.href = './index.php';
        }
        else
        {
            document.querySelector('#nvc').style.display = 'none';
            document.querySelector('#wce').style.display = 'block';
        }
    })
}