<?php
session_start();
// Verifica se há um usuário logado antes de permitir o acesso.
// Caso não tenha, então volta para o login.
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

include 'conexao.php';  // Inclui o arquivo de conexão com o banco de dados

$usuario_id = $_SESSION['id'];

$sql = "SELECT id, nome AS title, CONCAT(data_inicio, 'T', hora_inicio) AS start, CONCAT(data_termino, 'T', hora_termino) AS end, status FROM atividades WHERE usuario_id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$events = array();
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

header('Content-Type: application/json');
echo json_encode($events);

$stmt->close();
$conexao->close();
?>
