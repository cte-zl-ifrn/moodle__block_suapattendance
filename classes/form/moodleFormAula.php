<?php

//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class moodleFormAula extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG, $DB;

        $mform = $this->_form; // Don't forget the underscore!
        
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        
        $mform->addElement('hidden','courseid');
        $mform->setType('courseid', PARAM_INT);
        
        $mform->addElement('hidden','isEdit');
        $mform->setType('isEdit', PARAM_INT);

        $mform->addElement('text', 'quantidade', 'Hora Aula:', ['size' => '3', 'type' => 'number']);
        $mform->addRule('quantidade', null, 'required', null, 'client');
        $mform->addRule('quantidade', null, 'nonzero', null, 'client');
        $mform->addRule('quantidade', null, 'numeric', null, 'client');
        $mform->setType('quantidade', PARAM_INT);

        $options = array(
            '1' => 'Etapa 1',
            '2' => 'Etapa 2',
            '3' => 'Etapa 3',
            '4' => 'Etapa 4',
            '5' => 'Etapa 5'
        );
        $select = $mform->addElement('select', 'etapa', 'Etapas:', $options);

        $mform->addElement('date_selector', 'data_inicio', 'Data Início:');

        $mform->addElement('date_selector', 'data_fim', 'Data Fim:');

        $ifGet = isset($_GET['courseid']) ? ['course'=>$_GET['courseid']] : [];

        $options = array_values($DB->get_records('course_sections', $ifGet, 'section'));

        // Se a seção não tiver nome, coloca o nome manualmente.
        foreach ($options as $option => $value) {
            if($value->section == 0 && is_null($value->name)) {
                $value->name = "Apresentação";
            } else if (is_null($value->name)) {
                $value->name = "Tópico $value->section";
            }
            $optionsInterface[$value->id] = $value->name;
        }

        $select = $mform->addElement('select', 'sectionid', 'Tópicos:', $optionsInterface);

        $mform->addElement('editor', 'conteudo', 'Conteúdo:', null, null);
        $mform->setType('conteudo', PARAM_RAW); // Set type of element.

        $this->add_action_buttons();

        //  $mform->disabledIf('submitbutton', 'hora_aula', 'lt', 1);

        $mform->addElement('html',
        '<script type="text/javascript">
            if (document.getElementsByName("isEdit")[0].value == "1") {    
                const topicos = document.getElementById("id_sectionid");
                const DBValue = topicos.value;
                
                topicos.addEventListener("change", function() {
                if (topicos.value !== DBValue) {
                    alert("Se alterar o tópico, os componentes anteriormente marcados para contabilizar frequência relacionados a esta aula serão apagados");
                }
                });
            }
        </script>'); // ver um forma de linkar um arquivo js aqui em vez de colocar o código bruto


    }
    //Custom validation should be added here
    function validation($data, $files) {

        $errors = parent::validation($data, $files);
        $errors = [];

        if ($data['quantidade'] < 2) {
            $errors['quantidade'] = 'Error';
        }
        return $errors;
    }
}