<?php
$servername = "localhost";
$database = "gveiculos";
$username = "root";
$password = "";

//Criando a conexão
$conn = mysqli_connect($servername, $username, $password, $database);

//Checando conexão
if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}
//echo "conexão feita";
?>


