<?php

//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class moodleFormAula extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;

        $mform = $this->_form; // Don't forget the underscore!
        
        $mform->addElement('hidden', 'id');
        $mform->addElement('hidden','courseid');
        
        $mform->addElement('text', 'quantidade', 'Hora Aula:', ['size' => '3', 'type' => 'number']);
        $mform->addRule('quantidade', null, 'required', null, 'client');
        $mform->addRule('quantidade', null, 'nonzero', null, 'client');
        $mform->addRule('quantidade', null, 'numeric', null, 'client');
        $mform->setType('quantidade', PARAM_INT);
        // $mform->setDefault('hora_aula', get_config('tool_driprelease', 'activitiespersession'));
        // $mform->addHelpButton('activitiespersession', 'activitiespersession', 'tool_driprelease');
        

        // POST values:
        // hora_aula
        // etapas
        // data_inicio
        // data_fim
        // topicos
        // description_editor

        $options = array(
            '1' => 'Etapa 1',
            '2' => 'Etapa 2',
            '3' => 'Etapa 3',
            '4' => 'Etapa 4',
            '5' => 'Etapa 5'
        );
        $select = $mform->addElement('select', 'etapa', 'Etapas:', $options);
        $mform->addRule('etapa', null, 'required', null, 'client');

        $mform->addElement('date_selector', 'data_inicio', 'Data Início:');
        //$mform->addRule('data_inicio', null, 'required', null);

        $mform->addElement('date_selector', 'data_fim', 'Data Fim:');

        // $mform->setType('courseid', PARAM_INT);
        // $mform->setDefault('courseid', $id);
        // $mform->setDefault('hidden', $SESSION->courseid);

        // echo '<pre>';var_dump($SESSION->courseid);die();
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
        $select = $mform->addElement('select', 'sectionid', 'Tópicos:', $options);

        $mform->addElement('editor', 'conteudo', 'Conteúdo:', null, null);
        $mform->setType('conteudo', PARAM_RAW); // Set type of element.
        //  $mform->setDefault('conteudo', 'Coloque algo aqui');

        $this->add_action_buttons();

        //  $mform->disabledIf('submitbutton', 'hora_aula', 'lt', 1);


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