<?php

session_start();
ob_start();
include_once '../config/conexao.php';

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
var_dump($id);

if (empty($id)) {
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: motorista não encontrado!</p>";
    header("Location: index.php");
    exit();
}

$query_motoristas = "SELECT mot_id FROM tb_motorista WHERE mot_id = $id LIMIT 1";
$result_motorista = $conn->prepare($query_motoristas);
$result_motorista->execute();

if (($result_motorista) AND ($result_motorista->rowCount() != 0)) {
    $query_del_motorista = "DELETE FROM tb_motorista WHERE mot_id = $id";
    $apagar_motorista = $conn->prepare($query_del_motorista);

    if ($apagar_motorista->execute()) {
        $_SESSION['msg'] = "<p style='color: green;'>motorista apagado com sucesso!</p>";
        header("Location: index.php");
    } else {
        $_SESSION['msg'] = "<p style='color: #f00;'>motorista não apagado com sucesso!</p>";
        header("Location: index.php");
    }
} else {
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: motorista não encontrado!</p>";
    header("Location: index.php");
}
