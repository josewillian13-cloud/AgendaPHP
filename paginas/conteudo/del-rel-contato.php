<?php
    include('../../config/conexao.php');

    // 1. Verificação do parâmetro enviado na URL]
    if (isset($_GET['idDelete'])) {
        $id = $_GET['idDelete'];

         // 2. Consulta para recuperar o nome da imagem cadastrada
        $select = "SELECT foto_contatos FROM tb_contatos WHERE id_contatos=:id";
        try {
            $result = $conect->prepare($select);
            $result->bindValue(':id', $id, PDO::PARAM_INT);
            $result->execute();

            $contar = $result->rowCount();
            if ($contar > 0) {
                $show = $result->FETCH(PDO::FETCH_OBJ);
                $foto = $show->foto_contatos;

                // 3. Verifica se a imagem não é o avatar padrão antes de excluir
                if ($foto != 'avatar-padrao.png') {
                    
                    $filePath = '../../img/cont/' . $foto;
                    
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }

                // 4. Exclui o registro do banco de dados
                $delete = "DELETE FROM tb_contatos WHERE id_contatos=:id";
                try {
                $resultDelete = $conect->prepare($delete);
                $resultDelete->bindValue(':id', $id, PDO::PARAM_INT);
                $resultDelete->execute();
                header("Location: ../home.php");
                }catch (PDOException $e) {
                    echo '<strong>ERRO DE PDO= </strong>' . $e->getMessage();
                }

                
                // Redireciona para a página de relatório após a exclusão
                header("Location: ../home.php?acao=relatorio");
            } else {
                echo "Contato não encontrado.";
            }
        } catch (PDOException $e) {
            echo '<strong>ERRO DE PDO= </strong>' . $e->getMessage();

        }

        
    }
   