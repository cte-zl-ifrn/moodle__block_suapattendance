const camposNumericos = document.querySelectorAll('.campo-numerico');
const submit = document.getElementById('send');
// const alert = document.getElementById('alert');

camposNumericos.forEach(campo => {
  campo.addEventListener('change', () => {
    const camposAtivados = document.querySelectorAll('.campo-numerico:not([disabled])');
    let soma = 0;
    camposAtivados.forEach(campoAtivado => {
      soma += parseInt(campoAtivado.value);
    });
    if (soma !== 100) {
        submit.disabled = true;
        // alert('A soma dos valores deve ser igual a 100');
        // alert.innerHTML = "A soma do percentual presenças tem que resultar em 100%";
    } else {
        submit.disabled = false;
    }
  });
});