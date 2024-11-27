<?php

class RepoOrders implements RepoCrud{
    private $conexion;
    private $repoUsers;
    private $repoDirec;


    public function __construct($conexion) {
        $this->conexion = $conexion;
        $this->repoUsers = new RepoUser($conexion);
        $this->repoDirec = new RepoDirec($conexion);
    }
    public function create($order) {
        // Verificar que el usuario existe
        $user = $this->repoUsers->getById($order->getUserID());
        if (!$user) {
            echo "Usuario no encontrado";
        }
    
        // Insertar el pedido en la base de datos
        $stmt = $this->conexion->prepare("INSERT INTO Orders (datetime, state, totalPrice, UsersidUsers) VALUES (:datetime, :state, :totalPrice, :userID)");
            
        $stmt->execute(["datetime" => $order->getDatetime(),"state" => $order->getState(),"totalPrice" => $order->getTotalPrice(),"userID" => $order->getUserID()]);
    
        $orderId = $this->conexion->lastInsertId();
        $order->setIdOrder($orderId);
        
         return $order;
    }

    public function update($id, $order) {
        // Verificar que el pedido existe
        $existingOrder = $this->getById($id);
        if (!$existingOrder) {
            error_log("Pedido no encontrado: " . $id);
            return false;
        }
    
        // Verificar que el usuario existe
        $user = $this->repoUsers->getById($order->getUserID());
        if (!$user) {
            error_log("Usuario no encontrado: " . $order->getUserID());
            return false;
        }    
            // Actualizar el pedido en la base de datos
            $stmt = $this->conexion->prepare("UPDATE Orders SET datetime = :datetime, state = :state, 
                    totalPrice = :totalPrice, UsersidUsers = :userID 
                WHERE idOrder = :id
            ");
            
            $result = $stmt->execute([
                "id" => $id,
                "datetime" => $order->getDatetime(),
                "state" => $order->getState(),
                "totalPrice" => $order->getTotalPrice(),
                "userID" => $order->getUserID()
            ]);
    
            if (!$result) {
                throw new Exception("Error al actualizar el pedido principal");
            }
    
            // Primero, eliminar las relaciones en orderline_has_kebab
            $stmt = $this->conexion->prepare("DELETE FROM orderline_has_kebab WHERE OrderLine_orderLineID IN (SELECT orderLineID FROM OrderLine WHERE orderID = :orderId)");
            $stmt->execute(["orderId" => $id]);
    
            // Ahora sí, eliminar las líneas de pedido existentes
            $stmt = $this->conexion->prepare("DELETE FROM OrderLine WHERE orderID = :orderId");
            $stmt->execute(["orderId" => $id]);
    
            // Insertar las nuevas líneas de pedido
            foreach ($order->getOrderLines() as $line) {
                $stmt = $this->conexion->prepare("INSERT INTO OrderLine (orderID, json, quantity, price) 
                    VALUES (:orderId, :json, :quantity, :price)");
                $stmt->execute([
                    "orderId" => $id,
                    "json" => json_encode($line->getKebabs()),
                    "quantity" => $line->getQuantity(),
                    "price" => $line->getPrice()
                ]);
    
                $lineId = $this->conexion->lastInsertId();
    
                // Actualizar la tabla de relación orderline_has_kebab
                foreach ($line->getKebabs() as $kebab) {
                    if ($kebab->getIdKebab() !== null) { // Solo para kebabs no personalizados
                        $stmt = $this->conexion->prepare("INSERT INTO orderline_has_kebab (OrderLine_orderLineID, Kebab_idKebab) 
                            VALUES (:lineId, :kebabId)");
                        $stmt->execute([
                            "lineId" => $lineId,
                            "kebabId" => $kebab->getIdKebab()
                        ]);
                    }
                }
            }
    
            // Si todo ha ido bien, confirmar la transacción
            return $order;
    }

    public function delete($id) {
        // Verificar que el pedido existe
        $existingOrder = $this->getById($id);
        if (!$existingOrder) {
            echo "Pedido no encontrado";
            return false;
        }
    
        // Preparar y ejecutar la consulta para eliminar el pedido
        $stmt = $this->conexion->prepare("DELETE FROM Orders WHERE idOrder = :id");
        $result = $stmt->execute(["id" => $id]);
    
        if ($result) {
            echo "Pedido eliminado correctamente";
            return true;
        } else {
            echo "Error al eliminar el pedido";
            return false;
        }
    }

    public function getById($id) {
        $stmt = $this->conexion->prepare("SELECT * FROM Orders WHERE idOrder = :id");
        $stmt->execute(["id" => $id]);
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $order = new Order($row['idOrder'], $row['datetime'], $row['State'], $row['totalPrice'], $row['UsersidUsers']);
            
            // Obtener las líneas de pedido asociadas
            $stmtLines = $this->conexion->prepare("SELECT * FROM OrderLine WHERE orderID = :orderId");
            $stmtLines->execute(["orderId" => $id]);
            
            while ($lineRow = $stmtLines->fetch(PDO::FETCH_ASSOC)) {
                $orderLine = new OrderLine(
                    $lineRow['orderLineID'],
                    $lineRow['json'],
                    $lineRow['quantity'],
                    $lineRow['price'],
                    $lineRow['orderID']
                );
                
                // Procesar kebabs estándar
                $stmtKebab = $this->conexion->prepare("SELECT k.* FROM orderline_has_kebab olk JOIN Kebab k ON olk.Kebab_idKebab = k.idKebab WHERE olk.OrderLine_orderlineID = :orderLineID");
                $stmtKebab->execute(["orderLineID" => $lineRow['orderLineID']]);
                
                while ($kebabRow = $stmtKebab->fetch(PDO::FETCH_ASSOC)) {
                    $kebab = new Kebab($kebabRow['idKebab'], $kebabRow['name'], $kebabRow['basePrice'], $kebabRow['photo']);
                    $orderLine->addKebab($kebab);
                }
                
                // Procesar kebabs personalizados del JSON
                $jsonData = json_decode($lineRow['json'], true);
                if (is_array($jsonData)) {
                    foreach ($jsonData as $kebabData) {
                        if (isset($kebabData['name']) && $kebabData['name'] === 'Kebab Personalizado') {
                            $customKebab = new Kebab(
                                null, // ID null para kebabs personalizados
                                $kebabData['name'],
                                0, // El precio base se calculará sumando los precios de los ingredientes
                                '' // No hay foto para kebabs personalizados
                            );

                            // Añadir ingredientes al kebab personalizado
                            if (isset($kebabData['ingredients']) && is_array($kebabData['ingredients'])) {
                                $totalPrice = 0;
                                foreach ($kebabData['ingredients'] as $ingredientData) {
                                    $ingredient = new Ingredient(
                                        $ingredientData['name'],
                                        $ingredientData['price']
                                    );
                                    $customKebab->addIngredient($ingredient);
                                    $totalPrice += $ingredientData['price'];
                                }
                                $customKebab->setBasePrice($totalPrice);
                            }

                            $orderLine->addKebab($customKebab);
                        }
                    }
                }

                $order->addOrderLine($orderLine);
            }
            
            return $order;
        } else {
            return null;
        }
    }
    
    public function getAll() {
        $stmt = $this->conexion->prepare("SELECT * FROM Orders");
        $stmt->execute();
        
        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $order = new Order($row['idOrder'], $row['datetime'], $row['State'], $row['totalPrice'], $row['UsersidUsers']);
            
            // Obtener las líneas de pedido asociadas
            $stmtLines = $this->conexion->prepare("SELECT * FROM OrderLine WHERE orderID = :orderId");
            $stmtLines->execute(["orderId" => $row['idOrder']]);
            
            while ($lineRow = $stmtLines->fetch(PDO::FETCH_ASSOC)) {
                $orderLine = new OrderLine(
                    $lineRow['orderLineID'],
                    $lineRow['json'],
                    $lineRow['quantity'],
                    $lineRow['price'],
                    $lineRow['orderID']
                );
                
                // Procesar kebabs estándar
                $stmtKebab = $this->conexion->prepare("SELECT k.* FROM orderline_has_kebab olk JOIN Kebab k ON olk.Kebab_idKebab = k.idKebab WHERE olk.OrderLine_orderlineID = :orderLineID");
                $stmtKebab->execute(["orderLineID" => $lineRow['orderLineID']]);
                
                while ($kebabRow = $stmtKebab->fetch(PDO::FETCH_ASSOC)) {
                    $kebab = new Kebab($kebabRow['idKebab'], $kebabRow['name'], $kebabRow['basePrice'], $kebabRow['photo']);
                    $orderLine->addKebab($kebab);
                }
                
                // Procesar kebabs personalizados del JSON
                                // Procesar kebabs personalizados del JSON
                // Procesar kebabs personalizados del JSON
                $jsonData = json_decode($lineRow['json'], true);
                if (is_array($jsonData)) {
                    foreach ($jsonData as $kebabData) {
                        if (isset($kebabData['name']) && $kebabData['name'] === 'Kebab Personalizado') {
                            $customKebab = new Kebab(
                                null, // ID null para kebabs personalizados
                                $kebabData['name'],
                                0, // El precio base se calculará sumando los precios de los ingredientes
                                '' // No hay foto para kebabs personalizados
                            );

                            // Añadir ingredientes al kebab personalizado
                            if (isset($kebabData['ingredients']) && is_array($kebabData['ingredients'])) {
                                $totalPrice = 0;
                                foreach ($kebabData['ingredients'] as $ingredientData) {
                                    $ingredient = new Ingredient(
                                        $ingredientData['name'],
                                        $ingredientData['price']
                                    );
                                    $customKebab->addIngredient($ingredient);
                                    $totalPrice += $ingredientData['price'];
                                }
                                $customKebab->setBasePrice($totalPrice);
                            }

                            $orderLine->addKebab($customKebab);
                        }
                    }
                }

                $order->addOrderLine($orderLine);
            }
            
            $orders[] = $order;
        }
        
        return $orders;
    }

    public function getAllByUserId($userID) {
        // Implementación del método getAllByUserId
        $stmt = $this->conexion->prepare("SELECT * FROM Orders WHERE userID = :userID");
        $stmt->execute(["userID" => $userID]);
        
        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $order = new Order($row['idOrder'],$row['datetime'],$row['state'],$row['totalPrice'],$row['userID'],$row['addressJson'],$row['orderLinesJson']);
            $orders[] = $order;
        }
        
        return $orders;
    }

    public function find($criterio) {
        $sql = "SELECT DISTINCT o.* FROM Orders o LEFT JOIN OrderLine ol ON o.idOrder = ol.orderID LEFT JOIN OrderLineKebab olk ON ol.orderLineID = olk.orderLineId LEFT JOIN Kebab k ON olk.kebabId = k.kebabId LEFT JOIN ingredientsKebab ik ON k.kebabId = ik.kebab_id LEFT JOIN ingredients i ON ik.ingredient_id = i.idIngredients WHERE 1=1";
        $params = [];
    
        if (isset($criterio['fecha_inicio']) && isset($criterio['fecha_fin'])) {
            $sql .= " AND o.datetime BETWEEN :fecha_inicio AND :fecha_fin";
            $params['fecha_inicio'] = $criterio['fecha_inicio'];
            $params['fecha_fin'] = $criterio['fecha_fin'];
        }
    
        if (isset($criterio['estado'])) {
            $sql .= " AND o.state = :estado";
            $params['estado'] = $criterio['estado'];
        }
    
        if (isset($criterio['usuario_id'])) {
            $sql .= " AND o.userID = :usuario_id";
            $params['usuario_id'] = $criterio['usuario_id'];
        }
    
        if (isset($criterio['ingrediente'])) {
            $sql .= " AND i.name LIKE :ingrediente";
            $params['ingrediente'] = '%' . $criterio['ingrediente'] . '%';
        }
    
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute($params);
    
        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $order = new Order($row['idOrder'],$row['datetime'],$row['state'],$row['totalPrice'],$row['userID'],$row['addressJson'],$row['orderLinesJson']);
            $orders[] = $order;
        }
    
        return $orders;
    }

    public function count() {
        // Implementación del método count
        $stmt = $this->conexion->prepare("SELECT COUNT(*) as total FROM Orders");
        $stmt->execute();
        
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila['total'];
        // Devuelve el número total de pedidos
    }
}
