$(document).ready (() => {

    const form          = document.getElementById('userNew')
    const save          = document.getElementById('btn-save')
    const username      = document.getElementById('userNew_username')
    const pw1           = document.getElementById('userNew_password1')
    const pw2           = document.getElementById('userNew_password2')
    const email         = document.getElementById('userNew_email')
    const valid         = 0

    username.addEventListener('change', () => {

        var curr_user     = username.value;
        curr_user     = curr_user.toLowerCase();
        var format = /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;

        if (format.test(curr_user)) {
            alertModal('Username can\'t contains special character')
            username.value = ''
        } else if ((curr_user.length > 12) ||
                    (curr_user.length < 5)) {
            alertModal('Username should have 6 - 12 chars length')
            username.value = ''
        } else if (curr_user.toLowerCase() == 'username') {
            alertModal('Please use another')
            username.value = ''
        } else {
            $.ajax({
                url     : Routing.generate('app_check_user', { username : curr_user }),
                method  : 'POST'
            }).done((res) => {
                res = JSON.parse(res)
                if (res === true) {
                    alertModal('Username already exist, please try another.')
                    username.value = ''
                }
            })
        }
        checkForm()
    })

    pw1.addEventListener('change', () => {
        var curr_pw1      = pw1.value
        curr_pw1          = curr_pw1.toLowerCase()
        var curr_pw2      = pw2.value
        curr_pw2          = curr_pw2.toLowerCase()
        var format = /[ `@#$%^&*()_+\-=\[\]{};':"\\|,<>\/?~]/;

        if (format.test(curr_pw1)) {
            alertModal('Password can only contain special character ! and .')
            pw1.value = ''
        } else if ((curr_pw1.length > 12) ||
                    (curr_pw1.length < 5)) {
            alertModal('Password idealy between 6-12 chars long')
            pw1.value = ''
        } else if (curr_pw2.length > 0) {
            pw2.value = ''
            pw2.disabled = true;
        } else {
            pw2.disabled = false;
        }
    })

    pw2.addEventListener('change', () => {
        const curr_pw1      = pw1.value
        const curr_pw2      = pw2.value

        if (curr_pw2 != curr_pw1) {
            alertModal('Please check your password')
            pw1.value = ''
            pw2.value = ''
            pw2.disabled = true;
        }
        checkForm()
    })

    email.addEventListener('change', () => {
        const curr_email    = email.value
        if (!validateEmail(curr_email)) {
            alertModal('please check your email')
            email.value = ''
        }
        checkForm()
    })
    
    function validateEmail(email) {
        const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    function alertModal(body) {
        $('#alertBody').html(body)
        console.log("alert")
        $('#alertModal').modal('show')
    }

    function checkForm() {
        new_user    = encodeURI($(username).val())
        new_pw      = encodeURI($(pw2).val())
        new_email   = encodeURI($(email).val())
        if ((new_user.length > 5)   &&
            (new_user.length < 13)  &&
            (new_pw.length > 5)     &&
            (new_pw.length < 13)    &&
            (new_email.length > 8)) {
            save.disabled=false
        } else {
            save.disabled=true
        }
    }
})