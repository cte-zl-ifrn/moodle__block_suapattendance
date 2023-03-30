const checkboxesColuna1 = document.querySelectorAll('.checkbox-coluna-1');

checkboxesColuna1.forEach(checkbox => {
  checkbox.addEventListener('click', () => {
    const checkboxCorrespondente = checkbox.parentNode.parentNode.querySelector('.checkbox-coluna-2');
    console.log(checkboxCorrespondente);
    if (checkbox.checked) { 
      checkboxCorrespondente.disabled = false;
    }else{
      checkboxCorrespondente.disabled = true;
    }
  });
});