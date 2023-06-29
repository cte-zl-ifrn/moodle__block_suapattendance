if (document.getElementsByName("isEdit")[0].value == "1") {    
    const topicos = document.getElementById("id_sectionid");
    const DBValue = topicos.value;
    
    topicos.addEventListener("change", function() {
    if (topicos.value !== DBValue) {
        alert("Se alterar o tópico, os componentes anteriormente marcados para contabilizar frequência relacionados a esta aula serão apagados");
    }
    });
}