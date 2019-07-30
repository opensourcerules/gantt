function check(id) {
    if (document.getElementById('i' + id).value === '') {
        document.getElementById('check' + id).checked = false;
        document.getElementById('row' + id).style.fontWeight = 'normal';
    } else {
        document.getElementById('check' + id).checked = true;
        document.getElementById('row' + id).style.fontWeight = 'bold';
    }
}
