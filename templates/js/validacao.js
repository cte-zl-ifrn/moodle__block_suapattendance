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
        document.getElementById('aviso').textContent = 'Soma do percentual de presença precisa resultar em 100'; // não funciona como deveria
    } else {
        submit.disabled = false;
        document.getElementById('aviso').textContent = ''; // também não funciona como deveria
    }
  });
});