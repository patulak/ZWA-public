document.querySelector('#change_pass_form').addEventListener('submit', function (e) {
    const passwordOne = document.getElementById('password_one').value;
    const passwordTwo = document.getElementById('password_two').value;

    if (passwordOne !== passwordTwo && passwordOne != "") {
        e.preventDefault();
        let err = document.getElementById("error_span");
        err.innerText = "Hesla se neshodují";
        err.style.color = "red";
        console.log(err);
    }
});