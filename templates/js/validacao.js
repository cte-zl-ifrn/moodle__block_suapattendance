const camposNumericos = document.querySelectorAll('.campo-numerico');
const submit = document.getElementById('send');

camposNumericos.forEach(campo => {
  campo.addEventListener('change', () => {
    const camposAtivados = document.querySelectorAll('.campo-numerico:not([disabled])');
    let soma = 0;
    camposAtivados.forEach(campoAtivado => {
      soma += parseInt(campoAtivado.value);
    });
    if (soma !== 100) {
        submit.disabled = true;
    } else {
        submit.disabled = false;
    }
  });
});