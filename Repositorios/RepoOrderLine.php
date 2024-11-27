<?php

class RepoOrderLine implements RepoCrud{
    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }
    public function create($OrderLine) {
        if (!$OrderLine->getOrderID()) {
            throw new Exception("El OrderLine debe tener un OrderID válido antes de ser creado.");
        }
    
        try {
            // Iniciar transacción
    
            $stmt = $this->conexion->prepare("INSERT INTO OrderLine (json, quantity, price, orderID) VALUES (:json, :quantity, :price, :orderID)");

        $stmt->execute([
            "json" => $OrderLine->getStringJson(),
            "quantity" => $OrderLine->getQuantity(),
            "price" => floatval($OrderLine->getPrice()),
            "orderID" => $OrderLine->getOrderID()
        ]);

            $id = $this->conexion->lastInsertId();
            $OrderLine->setOrderLineID($id);
    
            // Insertar los kebabs asociados a esta línea de pedido
            $kebabs = $OrderLine->getKebabs();
            $stmtKebab = $this->conexion->prepare("INSERT INTO Orderline_has_kebab (Orderline_orderLineID, Kebab_idKebab) VALUES (:orderLineId, :kebabId)");
    
            foreach ($kebabs as $kebab) {
                if ($kebab->getIdKebab() !== null) {
                    $stmtKebab->execute([
                        "orderLineId" => $id,
                        "kebabId" => $kebab->getIdKebab()
                    ]);
                }
                // Si el kebab no tiene ID, simplemente lo omitimos en la tabla Orderline_has_kebab
            }
    
            // Commit de la transacción
    
            return $OrderLine;
        } catch (Exception $e) {
            // Rollback en caso de error
            $this->conexion->rollBack();
            throw new Exception("Error al crear la línea de pedido: " . $e->getMessage());
        }
    }

    public function update($id, $obj) {
        // Implementación del método update

    }

    public function delete($id) {
        // Implementación del método delete
        $stmt = $this->conexion->prepare("DELETE FROM OrderLine WHERE orderLineID = :orderLineID");
        $stmt->execute(["orderLineID" => $id]);
        $stmtKebab = $this->conexion->prepare("DELETE FROM OrderLineKebab WHERE orderLineId = :orderLineID");
        $stmtKebab->execute(["orderLineID" => $id]);
        return true;
    }

    public function getById($id) {
        $stmt = $this->conexion->prepare("SELECT * FROM OrderLine WHERE orderLineID = :orderLineID");
        $stmt->execute(["orderLineID" => $id]);
        
        if ($stmt->rowCount() > 0) {
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);
            $OrderLine = new OrderLine($fila['orderLineID'], $fila['orderID'], $fila['stringJson'], $fila['quantity'], $fila['price']);
    
            // Obtener los kebabs asociados a esta línea de pedido
            $stmtKebab = $this->conexion->prepare("SELECT k.* FROM OrderLineKebab olk JOIN Kebab k ON olk.kebabId = k.kebabId WHERE olk.orderLineId = :orderLineID");
            $stmtKebab->execute(["orderLineID" => $id]);
            
            while ($filaKebab = $stmtKebab->fetch(PDO::FETCH_ASSOC)) {
                $kebab = new Kebab($filaKebab['kebabId'],$filaKebab['name'],$filaKebab['description'],$filaKebab['price']);
                $OrderLine->addKebab($kebab);
            }
            return $OrderLine;
        }
    
        return null; // Devuelve null si no se encuentra la OrderLine
    }

    public function getAll() {
        $stmt = $this->conexion->prepare("SELECT * FROM OrderLine");
        $stmt->execute();
        
        $OrderLines = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $OrderLine = new OrderLine($fila['orderLineID'], $fila['orderID'], $fila['stringJson'], $fila['quantity'], $fila['price']);
            
            // Obtener los kebabs asociados a esta línea de pedido
            $stmtKebab = $this->conexion->prepare("SELECT k.* FROM OrderLineKebab olk JOIN Kebab k ON olk.kebabId = k.kebabId WHERE olk.orderLineId = :orderLineID");
            $stmtKebab->execute(["orderLineID" => $fila['orderLineID']]);
            
            while ($filaKebab = $stmtKebab->fetch(PDO::FETCH_ASSOC)) {
                $kebab = new Kebab($filaKebab['kebabId'],$filaKebab['name'],$filaKebab['description'],$filaKebab['price']);
                $OrderLine->addKebab($kebab);
            }
            
            $OrderLines[] = $OrderLine;
        }
        
        return $OrderLines;
    }

    public function find($criterio) {
        
    }

    public function count() {
        // Implementación del método count
        $stmt = $this->conexion->prepare("SELECT COUNT(*) as total FROM OrderLine");
        $stmt->execute();
        
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila['total'];
        // Devuelve el número total de OrderLines
    }
}
