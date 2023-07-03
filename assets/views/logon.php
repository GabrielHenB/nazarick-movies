<!--<section class='conteiner logon'> -->
<article class="conteiner logon">
    <div class="row logon-head">
        <header class="col-4 logon-header">
            <h1>Fazer Login</h1>
            <p>Ainda não tem conta? <a title="Cadastre-se" href="?file=register">Cadastre-se</a></p>
        </header>
        <div class="col-8 logon-content">
            <form class="logon-form" action="" method="post" enctype="multipart/form-data">
                <label>
                    <div><span class="icon-email">Email:</span></div>
                    <input type="email" name="email" placeholder="Digite seu e-mail aqui!"/>
                </label>

                <label>
                    <div class="logon-unlock">
                        <span class="logon-unlock-span">Senha:</span>
                        <span><a title="Forgopass" href="?file=forgopass">Esqueceu sua senha?</a></span>
                    </div>
                    <input type="password" name="password" placeholder="Informe sua senha:"/>
                </label>

                <label class="check">
                    <input type="checkbox" name="save"/>
                    <span>Lembrar dados?</span>
                </label>

                <button class="btn transition gradient gradient-green gradient-hover logon-button">Entrar</button>
            </form>
        </div>
    </div>
</article>
<!-- </section> -->
