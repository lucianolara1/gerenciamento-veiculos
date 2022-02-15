<?php
session_start();
include_once '../config/conexao.php';
?>
<!DOCTYPE html>
<html>

<head lang="pt-br">
    <title>Gerenciamento de veiculos</title>
    <script src="../js/jquery.js"></script>
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <script src="../js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/bootstrap-select.min.css">
    <script src="../js/bootstrap-select.min.js"></script>
</head>

<body>

    <h1 style="text-align: center;margin: 20px;">Gerenciamento de veiculos</h1>
    <a href="cadastrar.php" class="btn btn-primary" style="position: relative;left: 42%;right: 50%;">Cadastrar novo</a>
    <a href="../" class="btn btn-light" style="position: relative;left: 43%;right: 50%;">Voltar</a>
    <hr>

    <div class="container" style="text-align: center;">

        <h1>Lista</h1>

        <table class='table table-bordered table-striped' style='top:40px;'>
            <thead>
                <tr>
                    <th>PLACA</th>
                    <th>DESCRIÇÃO</th>
                    <th>MODELO</th>
                    <th>AÇÃO</th>
                </tr>
            </thead>
            <tbody>
                <tr>

                    <?php
                    if (isset($_SESSION['msg'])) {
                        echo $_SESSION['msg'];
                        unset($_SESSION['msg']);
                    }

                    //Receber o número da página
                    $pagina_atual = filter_input(INPUT_GET, "page", FILTER_SANITIZE_NUMBER_INT);
                    $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;
                    //var_dump($pagina);

                    //Setar a quantidade de registros por página
                    $limite_resultado = 40;

                    // Calcular o inicio da visualização
                    $inicio = ($limite_resultado * $pagina) - $limite_resultado;


                    $query_veiculos = "SELECT v.vei_id, v.vei_placa, v.vei_descricao, m.mod_descricao
        FROM tb_veiculos v
        INNER JOIN tb_modelo m ON m.mod_id = v.vei_modelo ORDER BY vei_id DESC LIMIT $inicio, $limite_resultado";
                    $result_veiculos = $conn->prepare($query_veiculos);
                    $result_veiculos->execute();

                    if (($result_veiculos) and ($result_veiculos->rowCount() != 0)) {
                        while ($row_veiculo = $result_veiculos->fetch(PDO::FETCH_ASSOC)) {
                            //var_dump($row_veiculo);
                            extract($row_veiculo);
                            //echo "ID: " . $row_veiculo['id'] . "<br>";
                            echo "
                                <td>$vei_placa</td>
                                <td>$vei_descricao </td>
                                <td>$mod_descricao </td>
                                <td>
                                <a href='visualizar.php?id=$vei_id' class='btn btn-secondary btn-sm'>Ver</a>
                                    <a href='editar.php?id=$vei_id' class='btn btn-primary btn-sm'>Editar</a>
                                    <a href='apagar.php?id=$vei_id' class='btn btn-danger btn-sm' onclick='return confirma()'>Excluir</a>
                                </td>
                            </tr>
                                    ";
                        }

                        //Contar a quantidade de registros no BD
                        $query_qnt_registros = "SELECT COUNT(vei_id) AS num_result FROM tb_veiculos";
                        $result_qnt_registros = $conn->prepare($query_qnt_registros);
                        $result_qnt_registros->execute();
                        $row_qnt_registros = $result_qnt_registros->fetch(PDO::FETCH_ASSOC);

                        //Quantidade de página
                        $qnt_pagina = ceil($row_qnt_registros['num_result'] / $limite_resultado);

                        // Maximo de link
                        $maximo_link = 2;

                        echo "<a href='index.php?page=1'>Primeira</a> ";

                        for ($pagina_anterior = $pagina - $maximo_link; $pagina_anterior <= $pagina - 1; $pagina_anterior++) {
                            if ($pagina_anterior >= 1) {
                                echo "<a href='index.php?page=$pagina_anterior'>$pagina_anterior</a> ";
                            }
                        }

                        echo "$pagina ";

                        for ($proxima_pagina = $pagina + 1; $proxima_pagina <= $pagina + $maximo_link; $proxima_pagina++) {
                            if ($proxima_pagina <= $qnt_pagina) {
                                echo "<a href='index.php?page=$proxima_pagina'>$proxima_pagina</a> ";
                            }
                        }

                        echo "<a href='index.php?page=$qnt_pagina'>Última</a> ";
                    } else {
                        echo "<p style='color: #f00;'>Erro: Nenhum registro encontrado!</p>";
                    }
                    ?>

            </tbody>
        </table>


        <script>
            function confirma() {
                if (!confirm("Tem certeza que realmente deseja excluir?")) {
                    return false;
                }

                return true;
            }
        </script>



</body>

</html>