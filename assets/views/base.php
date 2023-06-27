<!DOCTYPE html>
<html lang='pt-br'>
	<head>
		<meta charset='utf-8'>
		<meta name='viewport' content='width=device-width'>
		
		<title><?= $title; ?></title>
	</head>
	<body>
    	<header>
    		<h3 class="mensagem accept"> <?= $title; ?></h3>
    	</header>
    	<?php if ($v->section("nav")):  //Se existir essa secao?>
    	
            <nav class="mensagem info">
                <?= $v->section("nav") ?>
            </nav>
    	
    	<?php else: ?>
    	
    	  <p class="mensagem info">Lista de Usuários</p>
    	
    	<?php endif; ?>
    	
    	<?= $v->section("content") ?>
	</body>
</html>