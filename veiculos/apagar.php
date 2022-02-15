<?php

session_start();
ob_start();
include_once '../config/conexao.php';

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
var_dump($id);

if (empty($id)) {
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Veiculo não encontrado!</p>";
    header("Location: index.php");
    exit();
}

$query_veiculos = "SELECT vei_id FROM tb_veiculos WHERE vei_id = $id LIMIT 1";
$result_veiculo = $conn->prepare($query_veiculos);
$result_veiculo->execute();

if (($result_veiculo) AND ($result_veiculo->rowCount() != 0)) {
    $query_del_veiculo = "DELETE FROM tb_veiculos WHERE vei_id = $id";
    $apagar_veiculo = $conn->prepare($query_del_veiculo);

    if ($apagar_veiculo->execute()) {
        $_SESSION['msg'] = "<p style='color: green;'>Veiculo apagado com sucesso!</p>";
        header("Location: index.php");
    } else {
        $_SESSION['msg'] = "<p style='color: #f00;'>Veiculo não apagado com sucesso!</p>";
        header("Location: index.php");
    }
} else {
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Veiculo não encontrado!</p>";
    header("Location: index.php");
}
