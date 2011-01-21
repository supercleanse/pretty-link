<?php
require_once 'prli-config.php';
require_once(PRLI_MODELS_PATH . '/models.inc.php');

$groups = $prli_group->getAll('',' ORDER BY name');
$values = setup_new_vars($groups);

require_once 'classes/views/prli-links/new.php';
?>
