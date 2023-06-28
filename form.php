<?php 

require __DIR__ . "/vendor/autoload.php";

$sessao = new Source\Core\Session();

?>

<?php
$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
$isLogin = false;
if($post){
	$data = (object)$post;

	if(!csrf_verify($post)){
		$error = message()->error("Erro de token, tente novamente!");
	}else if($isLogin){
		$userNew = (new Source\Models\User())->findByEmail($data->email);
		if($userNew)
			$session->set('login', $userNew->data());
		else
			echo message()->warning("Usuário não encontrado!");
	}else{
		$modeloUser = new \Source\Models\User();
        $modeloUser->bootstrap(
            $data->username,
            $data->email,
            $data->password
            );
        //Realizar cadastro pelo metodo save
        if(!$modeloUser->save()){
            echo $modeloUser->message();
        }else{
            echo message()->success("Sucesso !!");
			$userNew = $modeloUser;
			$session->set('login', $userNew->data());
            //unset($data)
        }
	}
}

?>

<form name="post" action="./" method="post" enctype="multipart/form-data" autocomplete="off" novalidate style="margin: 0 auto">
	<?= ($error ?? ""), csrf_input(); ?>
	<input type="text" name="username" value="<?= ($data->username ?? "") ?>" placeholder="Nome:" style="margin: 2px" /><br>
	<input type="text" name="email" value="<?= ($data->email ?? "") ?>" placeholder="E-mail:" style="margin: 2px" /><br>
	<input type="text" name="password" value="<?= ($data->password ?? "") ?>" placeholder="Senha:" style="margin: 2px" /><br>
	<button>Cadastrar Usuário</button>
</form>