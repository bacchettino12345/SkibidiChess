async function checkLogin(username, password) {
    try {
        const response = await fetch('../Backend/auth/php/login_check.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({username: username, password: password})
        });

        if(!response.ok)
            window.location.href = "./internal_error.html";
        
        const data = await response.json();

        if (data.err_status) {
            console.log(data.error);
            // window.location.href = "./internal_error.html";
        } else {
            if (data.status === true) {
               return true;
            } else {
                return false;
            }
        }
    } catch (error) {
        alert("Error: " + error);
        // window.location.href = "./internal_error.html";
    }
}


async function handleLogin(username, password) {

    const result = await checkLogin(username, password);
    
    if (result) {
        window.location.href = "./index.php";
    } else {
        document.querySelector('#wce').style.display = "block";
        document.querySelector('#nvc').style.display = 'none';
    }
}



async function checkUserExistanceOnRegister(user)
{
    try 
    {
        const response = await fetch('../Backend/auth/php/check_user_existance_npv.php',
            {
                method: 'POST',
                headers: 
                {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({username: user})
            }
        )
    
        if(!response.ok)
            window.location.href = "./internal_error.html";
    
        const data = await response.json();
    
        return data.status;

    }
    catch(error)
    {
        window.location.href = "./internal_error.html";
    }
}


async function registerAccount(username, password, email, firstname, lastname) {
    try {

        const response = await fetch('../Backend/auth/php/new_login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                username: username, 
                password: password, 
                email: email, 
                firstname: firstname, 
                lastname: lastname
            })
        });

        if (!response.ok) {
            window.location.href = "./internal_error.html";
        }

        
        const data = await response.json();
        console.log(data);

        return data.success;

    } catch (error) {
        window.location.href = "./internal_error.html";
    }
}

async function handleRegister(firstname, lastname, username, email, password)
{
    const result = await checkUserExistanceOnRegister(username);
    if(result)
    {
        document.querySelector('#nvc').style.display = 'none';
        document.querySelector('#exu').style.display = 'block';
    }
    else
    {
        if (await registerAccount(username, password, email, firstname, lastname))  
            window.location.href = "./accountCreatedRedirect.html";
        else
            window.location.href = "./internal_error.html";
    }
}