document.addEventListener('DOMContentLoaded', function() {
    var selectNombredeljefe = document.getElementById('Nombredeljefe');
    var inputJefe = document.getElementById('jefe');

    selectNombredeljefe.addEventListener('change', function() {
        var selectedOption = selectNombredeljefe.options[selectNombredeljefe.selectedIndex];
        var nombreApellido = selectedOption.getAttribute('data-nombre-apellido');
        inputJefe.value = nombreApellido;
    });

    // Para mostrar el nombre y apellido del profesor seleccionado inicialmente
    var selectedOption = selectNombredeljefe.options[selectNombredeljefe.selectedIndex];
    var nombreApellido = selectedOption.getAttribute('data-nombre-apellido');
    inputJefe.value = nombreApellido;
});