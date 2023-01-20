<?php

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/blocks/suapattendance/classes/form/moodleForm.php');

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

echo $OUTPUT->render_from_template('block_suapattendance/details', $templatecontext);

$mform->display();

echo $OUTPUT->footer();