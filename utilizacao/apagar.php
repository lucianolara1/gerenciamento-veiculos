<?php

session_start();
ob_start();
include_once '../config/conexao.php';

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
var_dump($id);

if (empty($id)) {
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Uso não encontrado!</p>";
    header("Location: index.php");
    exit();
}

$query_usos = "SELECT uve_id FROM tb_uso_veiculo WHERE uve_id = $id LIMIT 1";
$result_uso = $conn->prepare($query_usos);
$result_uso->execute();

if (($result_uso) AND ($result_uso->rowCount() != 0)) {
    $query_del_uso = "DELETE FROM tb_uso_veiculo WHERE uve_id = $id";
    $apagar_uso = $conn->prepare($query_del_uso);

    if ($apagar_uso->execute()) {
        $_SESSION['msg'] = "<p style='color: green;'>Uso apagado com sucesso!</p>";
        header("Location: index.php");
    } else {
        $_SESSION['msg'] = "<p style='color: #f00;'>Uso não apagado com sucesso!</p>";
        header("Location: index.php");
    }
} else {
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Uso não encontrado!</p>";
    header("Location: index.php");
}
