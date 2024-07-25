<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'conexao.php'; // Inclui o arquivo de conexão com o banco de dados

    // Recebe os dados do formulário
    $login = $_POST['login'];
    $senha = $_POST['senha'];

    // Prepara a consulta SQL
    $sql = "SELECT id, senha FROM usuarios WHERE login = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hash_senha);
        $stmt->fetch();

        // Verifica a senha
        if (password_verify($senha, $hash_senha)) {
            // Cria uma sessão e redireciona o usuário para a página de atividades
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $id;
            header("Location: atividades.php");
            exit;
        } else {
            echo "Senha ou Usuário incorretos!";
        }
    } else {
        echo "Usuário não encontrado!";
    }

    // Fecha a declaração e a conexão
    $stmt->close();
    $conexao->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- cdn Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
    <main class="form-signin w-100 m-auto">
        <section class="py-5 text-center container">
        <div class="row py-lg-5">
        <div class="col-lg-6 col-md-8 mx-auto">
        <h2 class="fw-light">Faça o login para entrar</h2><br>
            <form method="post" action="login.php"> 
                <div class="row g-3 align-items-center">
                 <div class="col-auto">
                     <label for="login">Usuário:</label>
                     </div>
             <div class="col-auto">
                    <input type="text" id="login" name="login" required class="form-control">
                            </div>
                        </div><br>
            <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="senha">Senha:&nbsp;&nbsp;</label>
                            </div>
                 <div class="col-auto">
                                <input type="password" id="senha" name="senha" required class="form-control">
                 </div>
                  <div class="col-12">
                                <input type="submit" value="Entrar" class="btn btn-success">
                            </div>
                        </div><br>
                    </form>
                    <a href="cadastro.php">Ainda não tem uma conta? Cadastre-se aqui.</a>
                </div>
                    </div>
                </section>
           </main>
</body>
</html>

