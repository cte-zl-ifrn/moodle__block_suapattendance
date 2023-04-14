if (document.getElementById("id_isEdit").value == 1) {
    const sections = document.getElementById("id_sectionid");
    const DBValue = sections.value;

    sections.addEventListener("change", function() {
    if (sections.value !== DBValue) {
        alert("Você está alterando a aula!");
    }
    });
}

// console.log("DEU CERTO!!!!!!!!!!!")