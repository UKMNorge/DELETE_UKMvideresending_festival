<?php
require_once(PLUGIN_DIR_PATH . 'class/videresendingsskjema.class.php');

# $TWIG[] = '';
// $vt er fra layout.controller.php - monstring::videresendtil(true)
#var_dump($vt->info['fylke_id']);
#var_dump($vt);
$TWIG['videresendingsskjema'] = new Videresendingsskjema($vt->info['fylke_id'], get_option('pl_id'));
$TWIG['f_id'] = $vt->info['fylke_id'];
$TWIG['pl_id'] = get_option('pl_id');
#var_dump($TWIG['videresendingsskjema']);
/*echo '<pre>';
var_dump($TWIG['videresendingsskjema']->getQuestionsWithAnswers());
echo '</pre>';*/
#phpinfo();