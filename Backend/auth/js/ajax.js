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
            window.location.href = '../public/multiplayer.html';
        }
        else
        {
            document.querySelector('#nvc').style.display = 'none';
            document.querySelector('#wce').style.display = 'block';
        }
    })
}