<?php
/**
 * TABELA transações
 */
 
// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $idConta = (isset($_POST["idConta"]) && $_POST["idConta"] != null) ? $_POST["idConta"] : "";
    $valor = (isset($_POST["valor"]) && $_POST["valor"] != null) ? $_POST["valor"] : "";
    $dataTransacao = (isset($_POST["dataTransacao"]) && $_POST["dataTransacao"] != null) ? $_POST["dataTransacao"] : NULL;
} else if (!isset($id)) {
    // Se não se não foi setado nenhum valor para variável $id
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $idConta = NULL;
    $valor = NULL;
    $dataTransacao = NULL;
}
 
// Cria a conexão com o banco de dados
try {
    $conexao = new PDO("mysql:host=localhost;dbname=dock", "root", "");
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexao->exec("set names utf8");
} catch (PDOException $erro) {
    echo "Erro na conexão:".$erro->getMessage();
}
 
// Bloco If que Salva os dados no Banco - atua como Create e Update
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $idConta != "") {
    try {
        if ($id != "") {
            $stmt = $conexao->prepare("UPDATE transações SET idConta=?, valor=?, dataTransacao=? WHERE id = ?");
            $stmt->bindParam(4, $id);
        } else {
            $stmt = $conexao->prepare("INSERT INTO transações (idConta, valor, dataTransacao) VALUES (?, ?, ?)");
        }
        $stmt->bindParam(1, $idConta);
        $stmt->bindParam(2, $valor);
        $stmt->bindParam(3, $dataTransacao);
 
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $id = null;
                $idConta = null;
                $valor = null;
                $dataTransacao = null;
            } else {
                echo "Erro ao tentar efetivar cadastro";
            }
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
 
// Bloco if que recupera as informações no formulário, etapa utilizada pelo Update
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id != "") {
    try {
        $stmt = $conexao->prepare("SELECT * FROM transações WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            $id = $rs->id;
            $idConta = $rs->idConta;
            $valor = $rs->valor;
            $dataTransacao = $rs->dataTransacao;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
 
// Bloco if utilizado pela etapa Delete
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id != "") {
    try {
        $stmt = $conexao->prepare("DELETE FROM transações WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "Registo foi excluído com êxito";
            $id = null;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
?>
<!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Tabela de transações</title>
            <style>
            .limpar {
                color:black;
                 background-color: rgb(200, 200, 200);
                text-decoration: none; */
                font: 400 13.3333px Arial;
                padding: 2px;
                border-width: 1px;
                border-style: inset;
            }
            </style>
        </head>
        <body>
            <form action="?act=save" method="POST" name="form1" >
                <h1>Tabela de Transações</h1>
                <hr>
                <input type="hidden" name="id" <?php
                 
                // Preenche o id no campo id com um valor "value"
                if (isset($id) && $id != null || $id != "") {
                    echo "value=\"{$id}\"";
                }
                ?> />
                idConta:
               <input type="number" name="idConta" <?php
 
               // Preenche o idConta no campo idConta com um valor "value"
               if (isset($idConta) && $idConta != null || $idConta != "") {
                   echo "value=\"{$idConta}\"";
               }
               ?> />
               valor R$:
               <input type="number" name="valor" min="0" <?php
 
               // Preenche o valor no campo valor com um valor "value"
               if (isset($valor) && $valor != null || $valor != "") {
                   echo "value=\"{$valor}\"";
               }
               ?> />
               Data de Transação:
               <input type="date" name="dataTransacao" <?php
 
               // Preenche o dataTransacao no campo dataTransacao com um valor "value"
               if (isset($dataTransacao) && $dataTransacao != null || $dataTransacao != "") {
                   echo "value=\"{$dataTransacao}\"";
               }
               ?> />
               <input type="submit" value="salvar" />
               <a href="http://localhost/DockTeste/transações.php" class="limpar">Limpar</a>
               <hr>
            </form>
            <table border="1" width="100%">
                <tr>
                    <th>idConta</th>
                    <th>valor</th>
                    <th>dataTransacao</th>
                    <th>Ações</th>
                </tr>
                <?php
 
                // Bloco que realiza o papel do Read - recupera os dados e apresenta na tela
                try {
                    $stmt = $conexao->prepare("SELECT * FROM transações");
                    if ($stmt->execute()) {
                        while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                            echo "<tr>";
                            echo "<td>".$rs->idConta."</td><td>".$rs->valor."</td><td>".$rs->dataTransacao
                                       ."</td><td><center><a href=\"?act=upd&id=".$rs->id."\">[Alterar]</a>"
                                       ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                                       ."<a href=\"?act=del&id=".$rs->id."\">[Excluir]</a></center></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "Erro: Não foi possível recuperar os dados do banco de dados";
                    }
                } catch (PDOException $erro) {
                    echo "Erro: ".$erro->getMessage();
                }
                ?>
            </table>

                <br>
            <a href="http://localhost/DockTeste/"><< VOLTAR</a>
        </body>
    </html>