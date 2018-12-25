// Global variable declarations
var usernameOk = true;
var running = false;

function validateName() {
    var elem = document.querySelector("#name-control");
    if (elem.value.length == 0) {
        document.querySelector("#name-message").innerText = "* Cannot be left blank";
        document.querySelector("#name-message").style.visibility = "visible";
        return false;
    } else if (elem.value.length < 4) {
        document.querySelector("#name-message").innerText = "* Minimum 4 character long";
        document.querySelector("#name-message").style.visibility = "visible";
        return false;
    } else {
        document.querySelector("#name-message").style.visibility = "hidden";
        return true;
    }
}

function validatePassword() {
    var elem = document.querySelector('#password-control');
    if (elem.value.length < 8) {
        document.querySelector('#password-message').style.visibility = 'visible';
        return false;
    } else {
        document.querySelector('#password-message').style.visibility = 'hidden';
        return true;
    }
}

function validateConfirmation() {
    var elem = document.querySelector('#confirm-control');
    if (elem.value !== document.querySelector('#password-control').value) {
        document.querySelector('#confirm-password').style.visibility = 'visible';
        return false;
    } else {
        document.querySelector('#confirm-password').style.visibility = 'hidden';
        return true;
    }
}

function validateMobile() {
    var elem = document.querySelector('#mobile-number-control');
    if (elem.value.length == 0) {
        document.querySelector('#mobile-message').innerText = "* Cannot be left be blank";
        document.querySelector('#mobile-message').style.visibility = 'visible';
        return false;
    } else if (elem.value.length != 10) {
        document.querySelector('#mobile-message').style.visibility = 'visible';
        return false;
    } else {
        document.querySelector('#mobile-message').style.visibility = 'hidden';
        return true;
    }
}

function regularEmail(mail)
{
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail))
    {
        return (true)
    }
    return (false)
}


function validateEmail() {
    var elem = document.querySelector('#email-control');
    if (regularEmail(elem.value) == false) {
        document.querySelector("#email-message").innerText = "* Enter a valid email";
        document.querySelector("#email-message").style.visibility = 'visible';
        return false;
    } else {
        document.querySelector("#email-message").style.visibility = 'hidden';
        return true;
    }
}


function validateUsername() {
    var elem = document.querySelector('#username-control');
    if (elem.value === "") {
        document.querySelector('#username-message').innerText = "* Enter a valid username";
        document.querySelector('#username-message').style.visibility = 'visible';
        usernameOk = false;
        return false;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            text = this.responseText;
            console.log(text);
            if (text == "ok") {
                document.querySelector('#username-message').innerText = "";
                document.querySelector('#username-message').style.visibility = 'hidden';
                usernameOk = true;
                return true;
            } else {
                document.querySelector('#username-message').innerText = "* Username already taken"
                document.querySelector('#username-message').style.visibility = 'visible';
                usernameOk = false;
                return false;
            }
        }
    };
    xhttp.open("GET", "./signup_handler.php?subscription_check=true&username=" + elem.value, true);
    xhttp.send();
}


function validateEmailAjax() {
    var elem = document.querySelector('#email-control');
    if (elem.value === "") {
        document.querySelector('#email-message').innerText = "* Enter a valid email";
        document.querySelector('#email-message').style.visibility = 'visible';
        usernameOk = false;
        return false;
    }else if(validateEmail()) {
        console.log(validateEmail())
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                text = this.responseText;
                console.log(text);
                if (text == "ok") {
                    document.querySelector('#email-message').innerText = "";
                    document.querySelector('#email-message').style.visibility = 'hidden';
                    usernameOk = true;
                    return true;
                } else {
                    document.querySelector('#email-message').innerText = "* Username already taken"
                    document.querySelector('#email-message').style.visibility = 'visible';
                    usernameOk = false;
                    return false;
                }
            }
        };
        xhttp.open("GET", "./signup_handler.php?subscription_check=true&email=" + elem.value, true);
        xhttp.send();
    }
}

/*
function sendSignIn() {
    var username = document.querySelector('#sign-in-username')
    var password = document.querySelector('#sign-in-password')
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            text = this.responseText;
            console.log(text);
            if (text === "ok") {
                document.querySelector('#sign-in-message').style.visibility = 'hidden';
                return true;
            } else {
                document.querySelector('#sign-in-message').style.visibility = 'visible';
                document.querySelector("#sign-in-password").value = "";
                return false;
            }
        }
    };
    xhttp.open("GET", "http://localhost/start/signup_handler.php?sign_in=true&name=" + username + "&password=" + password, true);
    xhttp.send();

}
*/

function sendForgetPassword() {
    var email = document.querySelector('#forget-email');
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            text = this.responseText;
            console.log(text);
            if (text.includes("ok")) {
                document.querySelector('#forget-password-message').style.visibility = 'hidden';
                document.querySelector('#forget-email-detail').innerText = email.value;
                document.querySelector('#forget-password-confirmation').style.visibility = 'visible';
                return true;
            } else {
                document.querySelector('#forget-password-message').style.visibility = 'visible';
                document.querySelector('#forget-password-confirmation').style.visibility = 'hidden';
                return false;
            }
        }
    };
    xhttp.open("GET", "./forgot_passord_handler.php?forgot_password=true&email=" + email.value, true);
    xhttp.send();

}

function showSignIn(elem) {
    elem.classList.add('active');
    document.querySelector('#sign-up-tab').classList.remove('active');
    document.querySelector('#sign-in-left').style.display = 'block';
    document.querySelector('#sign-in-right').style.display = 'block';
    document.querySelector('#sign-in-form').style.left = '0vw';
    document.querySelector('#sign-up-form').style.left = '100vw';
    setTimeout(function () {
        document.querySelector('#sign-up-left').style.display = 'none';
        document.querySelector('#sign-up-right').style.display = 'none';
        running = false;
    }, 1000);

}

function showSignUp(elem) {
    elem.classList.add('active');
    document.querySelector('#sign-in-tab').classList.remove('active');
    document.querySelector('#sign-up-form').style.left = '0vw';
    document.querySelector('#sign-in-form').style.left = '-100vw';
    document.querySelector('#sign-up-left').style.display = 'block';
    document.querySelector('#sign-up-right').style.display = 'block';
    setTimeout(function () {
        document.querySelector('#sign-in-left').style.display = 'none';
        document.querySelector('#sign-in-right').style.display = 'none';
        running = false;
    }, 1000);
}

function showForgetPassword(elem) {
    document.querySelector('#forget-password-form-control').style.opacity = '1';
    document.querySelector('#sign-in-form-controls').style.top = '100%';
    document.querySelector('#forget-password-container').style.display = 'block';
    document.querySelector('#forget-password-form-control').style.top = '14.5%';
    setTimeout(function () {
        document.querySelector('#sign-in-form-container').style.display = 'none';
        running = false;
    }, 1000);
}

function showSignForm(elem) {
    document.querySelector('#sign-in-form-controls').style.top = '14.5%';
    document.querySelector('#forget-password-form-control').style.top = '-100%';
    document.querySelector('#sign-in-form-container').style.display = 'block';
    setTimeout(function () {
        document.querySelector('#forget-password-container').style.display = 'none';
        running = false;
    }, 1000);
}

function submitSignUp() {
    if (validateName() && validateEmail() && validateMobile() && validatePassword() && usernameOk && validateConfirmation()) {
        document.querySelector("#email-confirmation").style.visibility = "visible";
        document.querySelector("#loading").style.display = "block";
        document.querySelector('#sign-up-tab').classList.remove('active');
        var name = document.querySelector("#name-control").value;
        var username = document.querySelector("#username-control").value;
        var email = document.querySelector("#email-control").value;
        var mobile = document.querySelector("#mobile-number-control").value;
        var password = document.querySelector("#password-control").value;
        var college = document.querySelector("#college-control").value;

        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                text = this.responseText;
                console.log(text);
                document.querySelector("#loading").style.display = "none";
                document.querySelector("#mail-confirmation-message").innerText = text;
            }
        };
        xhttp.open("POST", "./signup_handler.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("sign_up=true&name=" + name + "&username=" + username + "&email=" + email + "&password=" + password + "&mobile=" + mobile + "&college=" + college);
    } else {
        /*alert("validateemail" + validateEmail());
alert("validatemobilel" + validateMobile());
alert("validatepassword" + validatePassword());
alert("validateconfirm" + validateConfirmation());
alert("validateUsername" + usernameOk);*/
        alert("Please enter the details correctly");
    }
}
