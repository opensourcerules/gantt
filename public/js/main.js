window.onload = function () {
    if ('0' === document.getElementById('admin').value) {
        document.getElementById('pass').style.display = 'none';
    }
}

function togglePasswordDisplay(value)
{
    if ('1' === value) {
        document.getElementById('pass').style.display = 'block';
    } else {
        document.getElementById('pass').style.display = 'none';
        document.getElementById('password').value = '';

    }
}
