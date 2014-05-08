<?php

use lib\util\HTML;

$form = new HTML\TForm;

$form->AdicionarCampo(new HTML\TInput('Nome'));

$form->Renderizar();
?>