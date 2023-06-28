<!DOCTYPE html>
<html lang='pt-br'>
	<head>
		<meta charset='utf-8'>
		<meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
		<!--o titulo vem da variavel-->
		<title><?= $title; ?></title>
		<meta name="author" content="Gabriel">
        <meta name="description" content="Portal de Filmes Nazarick Movies - Notícias, avaliações e discussões sobre filmes de animações">

        <!--<title>Nazarick Movies - Portal</title>-->

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

        <link rel="stylesheet" href="./assets/estilo.css">
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