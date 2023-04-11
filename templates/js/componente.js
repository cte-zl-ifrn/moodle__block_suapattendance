const checkboxes = document.querySelectorAll('.checkbox-coluna-1');
const eventoChange = new Event('change');

checkboxes.forEach(checkbox => {
  checkbox.addEventListener('click', () => {
    const numberCorrespondente = checkbox.parentNode.parentNode.querySelector('.coluna-2');
    // console.log(numberCorrespondente);
    if (checkbox.checked) { 
      numberCorrespondente.disabled = false;
    }else{
      numberCorrespondente.disabled = true;
      numberCorrespondente.value = "";
      numberCorrespondente.dispatchEvent(eventoChange);
    }
  });
});