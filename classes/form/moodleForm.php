<?php

//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class atto_editor extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;
       
        $mform = $this->_form; // Don't forget the underscore! 

        $mform->addElement('text', 'hora_aula', 'Hora Aula:', ['size' => '3', 'type' => 'number']);
        $mform->addRule('hora_aula', null, 'required', null, 'client');
        $mform->addRule('hora_aula', null, 'nonzero', null, 'client');
        $mform->addRule('hora_aula', null, 'numeric', null, 'client');
        $mform->setType('hora_aula', PARAM_INT);
       // $mform->setDefault('hora_aula', get_config('tool_driprelease', 'activitiespersession'));
       // $mform->addHelpButton('activitiespersession', 'activitiespersession', 'tool_driprelease');

       $options = array(
        '1' => 'Etapa 1',
        '2' => 'Etapa 2',
        '3' => 'Etapa 3'
        );
        $select = $mform->addElement('select', 'etapas', 'Etapas:', $options);

        $mform->addElement('date_selector', 'data_inicio', 'Data Início:');
        //$mform->addRule('data_inicio', null, 'required', null);

        $mform->addElement('date_selector', 'data_fim', 'Data Fim:');
        // array(
        //     'startyear' => 1970, 
        //     'stopyear'  => 2020,
        //     'timezone'  => 99,
        //     'optional'  => false
        // );

        $options = array(
            ''  => 'Selecione uma seção',
            '1' => 'Seção 1',
            '2' => 'Seção 2',
            '3' => 'Seção 3'
            );
        $select = $mform->addElement('select', 'etapas', 'Tópicos:', $options);
        $mform->addRule('etapas', null, 'required', null, 'client');

        $mform->addElement('editor', 'description_editor', 'Conteúdo:', null, null);
        $mform->setType('description_editor', PARAM_RAW);                 // Set type of element.
       // $mform->setDefault('description_editor', 'Please enter email');        // Default value.

       $this->add_action_buttons();

       $mform->disabledIf('submitbutton', 'hora_aula', 'lt', 1);

            
    }
    //Custom validation should be added here
    function validation($data, $files) {

        $errors = parent::validation($data, $files);
        $errors = [];

        if ($data['hora_aula'] < 2) {
            $errors['hora_aula'] = 'Error';
        }
        return $errors;
    }
}
