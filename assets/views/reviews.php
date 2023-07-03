<section class="conteiner reviews">

    
    <div class="row reviewes">
            <?php
            for ($i = 0; $i <= 3; $i++):
                require __DIR__ . "/review.php";
            endfor;
            ?>
    </div>
    
    <div class="row reviews-nav">
        <nav class="col-12 paginator">
                <a class='paginator_item' title="Primeira página" href="?file=review&page=1"><<</a>
                <span class="paginator_item paginator_active">1</span>
                <a class='paginator_item' title="Página 2" href="?file=review&page=2">2</a>
                <a class='paginator_item' title="Página 3" href="?file=review&page=3">3</a>
                <a class='paginator_item' title="Página 4" href="?file=review&page=4">4</a>
                <a class='paginator_item' title="Última página" href="?file=review&page=10">>></a>
        </nav>
    </div>
        
    
</section>