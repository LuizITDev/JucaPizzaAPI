<?php
/**
 * =========================================================================
 * O QUE É UMA "MODEL" / MODELO? (bem simples)
 * =========================================================================
 * Este ficheiro é a "receita" da Pizza no código. Não é o ecrã do site; é a parte
 * que sabe COMO ler e escrever pizzas na tabela `pizzas` do MySQL.
 *
 * Cada função abaixo faz UMA coisa óbvia:
 *   getall   → traz todas as linhas da tabela (lista do cardápio).
 *   get      → traz UMA linha, pela chave idPizza.
 *   create   → insere uma linha nova.
 *   update   → altera uma linha que já existe.
 *   delete   → apaga uma linha.
 *
 * Porque prepare() e bind? Porque assim o valor do id/nome vai "encaixado" no sítio
 * certo do comando SQL sem o utilizador mal-intencionado poder injetar comando falso
 * (é a defesa básica contra SQL injection — não precisas de decorar, só saber que é boa prática).
 */
 
class Pizza
{
    // Ligação ao MySQL (vem de fora, do Database.php).
    private $conn;
 
    // Nome da tabela onde as pizzas estão guardadas.
    private $tabela = "pizzas";
 
    // Estes campos são públicos para o resto do código preencher antes de chamar create/update/get.
    public $idPizza;
    public $nome;
    public $ingredientes;
    public $valor;
 
    /**
     * Quando crias `new Pizza($db)`, guardamos essa ligação para todas as queries usarem a mesma.
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }
 
    /**
     * Lista TODAS as pizzas. Não precisa de id porque não estamos a escolher uma só.
     * Devolve um $stmt (resultado do PDO) para o getall.php ir buscando linha a linha num while.
     */
    public function getall()
    {
        $query = "SELECT idPizza, nome, ingredientes, valor FROM " . $this->tabela;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
 
    /**
     * Busca UMA pizza pelo id que está em $this->idPizza ANTES de chamares esta função.
     * Se encontrar, copia os campos para o objeto e devolve a linha em array.
     * Se não encontrar, devolve false — o get.php usa isso para mandar "não encontrada".
     */
    public function get()
    {
        $query = "SELECT idPizza, nome, ingredientes, valor
            FROM " . $this->tabela . "
            WHERE idPizza = ?
            LIMIT 1";
 
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->idPizza);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
        if ($row) {
            $this->idPizza = $row['idPizza'];
            $this->nome = $row['nome'];
            $this->ingredientes = $row['ingredientes'];
            $this->valor = $row['valor'];
        }
 
        return $row;
    }
 
    /**
     * Insere uma pizza nova. O id normalmente é gerado pelo MySQL (AUTO_INCREMENT).
     * Depois de inserir, guardamos o novo id em $this->idPizza para o API responder "criei a número X".
     */
    public function create()
    {
        $query = "INSERT INTO " . $this->tabela . " (nome, ingredientes, valor) VALUES (:nome, :ingredientes, :valor)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':nome', $this->nome);
        $stmt->bindValue(':ingredientes', $this->ingredientes);
        $stmt->bindValue(':valor', $this->valor);
        if (!$stmt->execute()) {
            return false;
        }
        $this->idPizza = $this->conn->lastInsertId();
        return true;
    }
 
    /**
     * Altera a pizza cujo id está em $this->idPizza. Os outros campos vêm já preenchidos no objeto.
     * Devolve true se mudou pelo menos uma linha; false se o id não existia ou nada mudou.
     */
    public function update()
    {
        $query = "UPDATE " . $this->tabela . "
            SET nome = :nome, ingredientes = :ingredientes, valor = :valor
            WHERE idPizza = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':nome', $this->nome);
        $stmt->bindValue(':ingredientes', $this->ingredientes);
        $stmt->bindValue(':valor', $this->valor);
        $stmt->bindValue(':id', $this->idPizza);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
 
    /**
     * Apaga UMA linha com aquele id. LIMIT 1 é uma rede de segurança extra.
     * Devolve true se apagou, false se não havia ninguém com esse id.
     */
    public function delete()
    {
        $query = "DELETE FROM " . $this->tabela . " WHERE idPizza = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->idPizza);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}