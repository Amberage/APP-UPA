function login() {
    // Datos de la mascota
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;
    var sessionTimeCheck = document.getElementById('sessionTime').checked;
    let sessionTime = 'off';
    
    if(sessionTimeCheck === true) {
        sessionTime = 'on';
    } else {
        sessionTime = 'off';
    }

    const urLogin = "/php/st_login.php";

    let formData = new FormData();
    formData.append("username", username);
    formData.append("password", password);
    formData.append("sessionTime", sessionTime);
    
    //Enviarlos via POST
    fetch(urLogin, {
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((responseLogin) => {
            document.getElementById('errorMsg').innerHTML = responseLogin.errorMsg;
            let loginResult = responseLogin.successLog;
            let userType = responseLogin.userType;
            if(loginResult === true) {
                document.getElementById('username').value = "";
                document.getElementById('password').value = "";
                document.getElementById('sessionTime').checked = false;

                if(userType === 'ts') {
                    window.location.href = "/views/ts/dashboard.php";
                } else if(userType === 'adm') {
                    window.location.href = "/views/admin/dashboard.php";
                } else {
                    window.location.href = "/index.php";
                }
            }
        })
        .catch((err) => console.log(err));
}