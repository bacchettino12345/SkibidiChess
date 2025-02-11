function verifySessionActive(){
    console.log('verifying session');
    fetch('./php/verify_session_status.php')
    .then(response => response.json())
    .then(data => {
        console.log(data);
        if(data != -1)
            {
                document.getElementById('insert_token').style.display = 'none';
                document.getElementById('generate_token').style.display = 'none';
                document.getElementById('session_id').style.display = 'block';
                document.getElementById('generated_token').innerText = data.token;
                setInterval(updateClock, 1000);
                time = data.time * 1000;
            }
        else
            {
                document.getElementById('insert_token').style.display = 'block';
                document.getElementById('generate_token').style.display = 'block';
                document.getElementById('session_id').style.display = 'none';
            }
        })
    .catch(error => { 
        console.error(error);
    }); 
}

document.getElementById('validate').addEventListener('click', function(){
    token = document.getElementById('token').value;
    fetch('./php/enter_session.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({token: token}),
    }
    )
    .then(response => response.json())
    .then(data => {
        console.log(data);
        if(data != 0)
            {
                document.getElementById('insert_token').style.display = 'none';
                document.getElementById('generate_token').style.display = 'none';
                document.getElementById('session_id').style.display = 'block';
                document.getElementById('generated_token').innerText = data.token;
                time = data.time * 1000;
                setInterval(updateClock, 1000);
            }
        })

});


document.getElementById('generate').addEventListener('click', function(){
    
    document.getElementById('insert_token').style.display = 'none';
    document.getElementById('generate_token').style.display = 'none';
    fetch('./php/generate_session.php')
    .then(response => response.json())
    .then(data => {
        console.log(data);
        console.log('session generated');
        document.getElementById('session_id').style.display = 'block';
        document.getElementById('generated_token').innerText = data.token;
        time = data.time * 1000;
        setInterval(updateClock, 1000);
    })
    .catch(error => console.error(error));
});

document.getElementById('destroy').addEventListener('click', function(){
    
    document.getElementById('insert_token').style.display = 'block';
    document.getElementById('generate_token').style.display = 'block';
    document.getElementById('session_id').style.display = 'none';
    fetch('./php/destroy_session.php')
    .then(response => response.text())
    .then(data => {
        console.log(data);
        console.log('session destroyed');
    })
    .catch(error => console.error(error));
});

setInterval(verifySessionActive, 500);

let updateClock = () => {
    let now = new Date().getTime();
    let elapsedTime = now - time;

    let hours = Math.floor(elapsedTime / (1000 * 60 * 60));
    let minutes = Math.floor((elapsedTime % (1000 * 60 * 60)) / (1000 * 60));
    let seconds = Math.floor((elapsedTime % (1000 * 60)) / 1000);
    document.getElementById('time').innerHTML = hours + 'h ' + minutes + 'm ' + seconds + 's';
}
