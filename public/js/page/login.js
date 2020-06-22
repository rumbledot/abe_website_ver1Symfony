$(document).ready (() => {

    const inputUsername = document.getElementById('inputUsername')
    const signinButton  = document.getElementById('signinButton')
    const form          = document.getElementById('form-signin')
    const signup        = document.getElementById('signup-link')
    
    inputUsername.addEventListener('change', () => {
        
        $.ajax({
            url     : Routing.generate('app_check_user', { username : inputUsername.value }),
            method  : 'POST'
        }).done((res) => {
            res = JSON.parse(res)
            if (res === false) {
                signup.style.display = "inline";
                signup.href=Routing.generate('app_signup', { username : inputUsername.value })
                signinButton.disabled = true;
            } else {
                signup.style.display = "none";
                signinButton.disabled = false;
            }
        })
    })

    signinButton.addEventListener('click', () => {
        if (signinButton.innerHTML == 'Welcome back!') {
            form.submit()
        } else {
            window.location.href = Routing.generate('app_signup')
        }
    })
})