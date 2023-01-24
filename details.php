<?php

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/blocks/suapattendance/classes/form/moodleForm.php');

global $DB;

$PAGE->set_url(new moodle_url('/blocks/presence/manage.php'));

$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Detalhes Presença');

echo $OUTPUT->header();

$templatecontext = [
    'aluno' => 'Thiago Dutra da Silva Gomes',
    'presenca' => [
        ["peso" => 1   , "presenca" => True, "descricao" => "Vídeo"        ],
        ["peso" => 0.5 , "presenca" => True, "descricao" => "PDF besta"    ],
        ["peso" => 0.5 , "presenca" => True, "descricao" => "Avaliaçao 1"  ],
        ["peso" => 0.5 , "presenca" => True, "descricao" => "Vídeo"        ],
        ["peso" => 0.5 , "presenca" => True, "descricao" => "PDF besta"    ],
        ["peso" => 1   , "presenca" => True, "descricao" => "Avaliaçao 2"  ],
    ],
];

$mform = new atto_editor();



if ($mform->is_cancelled()) {
    //volta para a página inicial por exemplo
    redirect($CFG->wwwroot . 'local/message/manage.php', 'Você cancelou a ação');
} else if ($fromform = $mform->get_data()) {
  //Inserir data no banco
  $recordinsert = new stdClass();
  $recordinsert->hora_aula = $fromform->hora_aula;
  $recordinsert->etapas = $fromform->etapas;
  $recordinsert->data_inicio = $fromform->data_inicio;
  $recordinsert->data_fim = $fromform->data_fim;
  $recordinsert->description_editor = $fromform->description_editor;

  $DB->insert_record('table', $recordinsert);

  redirect($CFG->wwwroot . 'local/message/manage.php', 'Você inseriu com sucesso');

} 

echo $OUTPUT->render_from_template('block_suapattendance/details', $templatecontext);

$mform->display();

echo $OUTPUT->footer();