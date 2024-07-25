<?php
session_start();
// Verifica se há um usuário logado antes de permitir o acesso.
// Caso não tenha, então volta para o login.
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

include 'conexao.php';


// Processa a criação de uma nova atividade
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_activity'])) {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $data_inicio = $_POST['data_inicio'];
    $hora_inicio = $_POST['hora_inicio'];
    $data_termino = $_POST['data_termino'];
    $hora_termino = $_POST['hora_termino'];
    $status = $_POST['status'];
    $usuario_id = $_SESSION['id'];

    $sql = "INSERT INTO atividades (nome, descricao, data_inicio, hora_inicio, data_termino, hora_termino, status, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sssssssi", $nome, $descricao, $data_inicio, $hora_inicio, $data_termino, $hora_termino, $status, $usuario_id);

    if ($stmt->execute()) {
        echo "Atividade adicionada com sucesso!";
       
    } else {
        echo "Erro ao adicionar atividade: " . $stmt->error;
    }

    $stmt->close();
}

// Processa a atualização do status de uma atividade
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_activity'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $usuario_id = $_SESSION['id'];

    $sql = "UPDATE atividades SET status = ? WHERE id = ? AND usuario_id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sii", $status, $id, $usuario_id);

    if ($stmt->execute()) {
        echo "Atividade atualizada com sucesso!";
    } else {
        echo "Erro ao atualizar atividade: " . $stmt->error;
    }

    $stmt->close();
}

// Processa a exclusão de uma atividade
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_activity'])) {
    $id = $_POST['id'];
    $usuario_id = $_SESSION['id'];

    $sql = "DELETE FROM atividades WHERE id = ? AND usuario_id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ii", $id, $usuario_id);

    if ($stmt->execute()) {
        echo "Atividade excluída com sucesso!";
    } else {
        echo "Erro ao excluir atividade: " . $stmt->error;
    }

    $stmt->close();
}

// Busca as atividades do usuário
$usuario_id = $_SESSION['id'];
$sql = "SELECT * FROM atividades WHERE usuario_id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atividades</title>
    <!-- FullCalendar -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.min.css' rel='stylesheet' />
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
<main class="form-signin w-100 m-auto">
        <section class="py-5 text-center container">
            <div class="row py-lg-5">
                <div class="col-lg-6 col-md-8 mx-auto">
                <h4 class="fw-light">id do usuário: <?php echo $usuario_id ?> </h4><br>
                <h2 class="fw-light">Adicionar Atividades</h2><br>

    <!-- Formulário para adicionar nova atividade -->
    <form method="post" action="">
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                 <label >Nome:</label>
                                    </div>
        <div class="col-auto">                  
            <input type="text" name="nome" required><br></div>
        </div><br>
        <div class="row g-3 align-items-center">
        <div class="col-auto">
            <label> Descrição: </label>
            </div>
            <div class="col-auto">
        <input type="text" name="descricao" required><br></div>
        </div><br>

        <div class="row g-3 align-items-center">
        <div class="col-auto">
       <label> Data de início: </label>
            </div>
            <div class="col-auto">
        
        <input type="date" name="data_inicio" required><br></div>
        </div><br>


        <div class="row g-3 align-items-center">
        <div class="col-auto">
       <label> Hora de início: </label>
            </div>
            <div class="col-auto">
        
        <input type="time" name="hora_inicio" required><br></div>
        </div><br>

        <div class="row g-3 align-items-center">
        <div class="col-auto">
       <label> Data de término: </label>
            </div>
            <div class="col-auto">
        
        <input type="date" name="data_termino" required><br></div>
        </div><br>

        <div class="row g-3 align-items-center">
        <div class="col-auto">
       <label> Hora de término: </label>
            </div>
            <div class="col-auto">
        
        <input type="time" name="hora_termino" required><br></div>
        </div><br>

        <div class="row g-3 align-items-center">
        <div class="col-auto">
       <label> Status: </label>
            </div>
            <div class="col-auto">
        <select name="status">
            <option value="pendente">Pendente</option>
            <option value="concluída">Concluída</option>
            <option value="cancelada">Cancelada</option>
        </select><br></div>
        <div class="col-auto">
        <input type="submit" name="add_activity" class="btn btn-success" value="Adicionar Atividade"></div>
        </div>
       
        
    </form>
    
    </div>
    </div>
    </section>
    <!-- Exibir em uma tabela as atividades cadastradas -->
    <section class="py-5 text-center container">
            <div class="row py-lg-5">
                <div class="col-lg-6 col-md-8 mx-auto">
                <h2 class="fw-light">Atividades Cadastradas</h2><br>
                        </div>
            </div>
        </section>
    <table class="table table-hover" >
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Data de Início</th>
            <th>Hora de Início</th>
            <th>Data de Término</th>
            <th>Hora de Término</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
        <!-- Método para buscar no banco de dados o conteúdo de cada linha se houver -->
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['nome']); ?></td>
            <td><?php echo htmlspecialchars($row['descricao']); ?></td>
            <td><?php echo htmlspecialchars($row['data_inicio']); ?></td>
            <td><?php echo htmlspecialchars($row['hora_inicio']); ?></td>
            <td><?php echo htmlspecialchars($row['data_termino']); ?></td>
            <td><?php echo htmlspecialchars($row['hora_termino']); ?></td>
            <td class="table-warning"><?php echo htmlspecialchars($row['status']); ?></td>
            <td>
                <!-- Formulário para atualizar o status -->
                <form method="post" action="" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                    <select name="status">
                        <option value="pendente" <?php if ($row['status'] == 'pendente') echo 'selected'; ?>>Pendente</option>
                        <option value="concluída" <?php if ($row['status'] == 'concluída') echo 'selected'; ?>>Concluída</option>
                        <option value="cancelada" <?php if ($row['status'] == 'cancelada') echo 'selected'; ?>>Cancelada</option>
                    </select>
                    <input type="submit" name="update_activity" class="btn btn-primary btn-sm" value="Atualizar Status">
                </form>

                <!-- Formulário para excluir a atividade -->
                <form method="post" action="" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                    <input type="submit" name="delete_activity" class="btn btn-outline-danger" value="Excluir Atividade" onclick="return confirm('Tem certeza que deseja excluir?');">
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <!-- Exibição do calendário -->
    <section class="py-5 text-center container">
            <div class="row py-lg-5">
                <div class="col-lg-6 col-md-8 mx-auto">
                <h2 class="fw-light">Calendário</h2><br>
                        </div>
            </div>
        </section>
    <div id='calendar'></div> <!-- Chama o calendário a página HTML -->

    <!-- FullCalendar -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: 'carregar_eventos.php', // retorna os eventos no formato JSON
                editable: false,
                
                
            });
            calendar.render(); // função que chama para renderizar o calendário
        });
    </script>
    <section class="py-5 text-center container">
            <div class="row py-lg-5">
                <div class="col-lg-6 col-md-8 mx-auto">
                    
                <a href="logout.php" class="btn btn-danger btn-lg">Sair</a> <!-- Direciona para o logout.php que encerra a sessão -->
                        </div>
            </div>
        </section>
    
    </main>
</body>
</html>

<?php
// Fecha a conexão
$conexao->close();
?>