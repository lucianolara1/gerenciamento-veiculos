<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";
$dbname = "gveiculos";

$conn = mysqli_connect($servidor, $usuario, $senha, $dbname);

$pesquisar = $_POST['pesquisar'];
$result_motorista = "SELECT * FROM tb_motorista WHERE mot_nome LIKE '%$pesquisar' LIMIT 5";
$resultado_motorista = mysqli_query($conn, $result_motorista);

while($rows_motoristas = mysqli_fetch_array($resultado_motorista)) {
    echo "Nome motorista:".$rows_motoristas['mot_nome']."<br>";
}

?>