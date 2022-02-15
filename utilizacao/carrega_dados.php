<?php
require './mysqli.php';

if (isset($_POST["tipo"])) {
    if ($_POST["tipo"] == "tb_motoristas") {
        $sql = "
                SELECT * FROM tb_motorista
                ORDER BY mot_nome ASC
                ";
        $tb_motoristas = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($tb_motoristas)) {
            $saida[] = array(
                'mot_id' => $row["mot_id"],
                'mot_nome' => $row["mot_nome"]
            );
        }
        echo json_encode($saida);
    } else {
        $cat_id = $_POST["cat_id"];
        $sql = "
                SELECT * FROM tb_veiculos 
                WHERE vei_modelo = '" . $cat_id . "' 
                ORDER BY vei_descricao ASC
                ";
        $tb_veiculos = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_array($tb_veiculos)) {
            $saida[] = array(
                'vei_id' => $row["vei_id"],
                'vei_descricao' => $row["vei_descricao"]
            );
        }
        echo json_encode($saida);
    }
}
?>

