//Valida que se ingresen dos cadenas para los apellidos y una cadena para el nombre, solo se aceptan valores del alfabeto

function validate(){
    let regLastname = /^[a-zA-Z]+ [a-zA-Z]+$/;
    let regName = /[a-zA-Z]+$/;
    let name = document.getElementById('name').value;
    let lastname = document.getElementById('lastname').value;
    if(!regLastname.test(lastname) && !regName.test(name)){
        alert("Ingrese el nombre y apellidos.");
        document.getElementById('lastname').focus();
        return false;
    }else{
        return true;
    }
}