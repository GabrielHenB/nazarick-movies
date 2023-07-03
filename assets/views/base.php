<!-- SITE INICIALMENTE DESENVOLVIDO PARA MATERIA DESENVOLVIMENTO DE INT WEB DA PUC MINAS - Aluno: GABRIEL H B-->
<!DOCTYPE html>
<html lang="pt-br">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <meta name="author" content="Gabriel H B">
        <meta name="description" content="Portal de Filmes Nazarick Movies - Notícias, avaliações e discussões sobre filmes de animações">

        <title>Nazarick Movies - Portal</title>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <link rel="stylesheet" href="./assets/css/estilo.css">

    </head>
    <body>
        <header class="container cabecalho">
            <div class="row cabecalho-area">
                <div class="col-2 logo">
                    <img src="./assets/images/bentriel-logo.png" alt="Nazarick Movies">
                </div>
                <div class="col-8 menu">
                    <input type="checkbox" id="menuToggle">
                    <label for="menuToggle" class="menuMobile">
                        <img src="./assets/images/menu-icon1.png">
                        <img src="./assets/images/menu-icon2.png">
                    </label>
                    <ul class="menuLista">
                        <li>
                          <a href="#" id="botaoLancamentos">lançamentos</a>
                        </li>
                        <li>
                          <a href="#cartazesEmDestaque">em destaque</a>
                        </li>
                        <li>
                          <a href="#caixa-avaliacoes">avaliações</a>
                        </li>
                        <li>
                          <a href="#jumpToEntrevistas">entrevistas & makingOff</a>
                        </li>
                        <li>
                            <a href="#jumpToNovidades">novidades</a>
                        </li>
                        <li>
                            <a href="#">sua lista</a>
                        </li>
                    </ul>
                </div>

                <div class="col-2 pesquisar">
                    <form id="form-pesquisar">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary" type="button" id="botao-pesquisar">Ir</button>
                            </div>
                            <input type="text" class="form-control" id="caixa-pesquisar" placeholder="Pesquisar" aria-label="Example text with button addon" aria-describedby="button-addon1">
                        </div>
                    </form>
                </div>

            </div>

        </header>

		<!-- TEMPLATES AQUI -->

		<main class="container main">
		
        <?php 
		$file = filter_input(INPUT_GET, "file", FILTER_SANITIZE_SPECIAL_CHARS);
		if (empty($file)) {
			require __DIR__ . "/assets/views/home.php";
		} elseif ($file && file_exists(__DIR__ . "/assets/views/{$file}.php")) {
			require __DIR__ . "/assets/views/{$file}.php";
		} else {
			require __DIR__ . "/assets/views/404.php";
		}
		?>

		</main>

		<!-- FOOTER AQUI -->

        <footer class="container rodape">
            <div class="row">
                <div class="col-10 texto-footer">
                    <p>Copyright 2020-2023 - Nazarick Movies - Grande Tumba de Nazarick</p>
					<a title="Termos de uso" href="?file=terms">Termos de uso</a>
                </div>
                <div class="col-2 logo-footer">
                    <img src="./assets/images/nazarick-movies.jpg" alt="logo-footer">
                </div>
            </div>
            <div class="row">
                <div class="col-10 texto-footer">
                    Informações Adicionais:
                    <p>Site desenvolvido para a  Disciplina de Desenvolvimento de Interfaces Web - ICEI PUC Minas</p>
                    <p>Este site utiliza conteúdos adquiridos através da API do <a href="https://www.themoviedb.org">themovidedb.com</a> </p>
                </div>
                <div class="col-2 logo-footer">
                    <img src="./assets/images/puc-minas-logo.png" alt="logo-footer">
                </div>
            </div>
        </footer>
        
        
        <!--SCRIPTS DO BOOTSTRAP-->
        <!-- O QUE VEM NO BOOTSTRAP(mas buga o jquery ajax): <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script> -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
        <!--MEUS SCRIPTS-->
        <script src="./assets/scripts/escripte.js"></script>
    </body>



</html>