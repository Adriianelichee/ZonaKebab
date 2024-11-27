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
            echo "Pedido no encontrado";
            return false;
        }
    
        // Verificar que el usuario existe
        $user = $this->repoUsers->getById($order->getUserID());
        if (!$user) {
            echo "Usuario no encontrado";
            return false;
        }
    
        // Verificar que la dirección pertenece al usuario
        $addressData = json_decode($order->getAddressJson(), true);
        $userAddresses = $this->repoDirec->getAddressesByUserId($order->getUserID());
        $validAddress = false;
        foreach ($userAddresses as $address) {
            if ($address->getId() == $addressData['id']) {
                $validAddress = true;
                break;
            }
        }
        if (!$validAddress) {
            echo "La dirección no pertenece al usuario";
            return false;
        }
    
        // Actualizar el pedido en la base de datos
        $stmt = $this->conexion->prepare("UPDATE Orders SET datetime = :datetime, state = :state, 
                totalPrice = :totalPrice, 
                userID = :userID, 
                addressJson = :addressJson, 
                orderLinesJson = :orderLinesJson 
            WHERE idOrder = :id
        ");
        
        $result = $stmt->execute([
            "id" => $id,
            "datetime" => $order->getDatetime(),
            "state" => $order->getState(),
            "totalPrice" => $order->getTotalPrice(),
            "userID" => $order->getUserID(),
            "addressJson" => $order->getAddressJson(),
            "orderLinesJson" => $order->getOrderLinesJson()
        ]);
    
        if ($result) {
            return $order;
        } else {
            echo "Error al actualizar el pedido";
            return false;
        }
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
            $order = new Order($row['idOrder'],$row['datetime'],$row['state'],$row['totalPrice'],$row['userID'],$row['addressJson'],$row['orderLinesJson']);
            
            return $order;
        } else {
            return null;
        }
    }

    public function getAll() {
        // Implementación del método getAll
        $stmt = $this->conexion->prepare("SELECT * FROM Orders");
        $stmt->execute();
        
        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $order = new Order($row['idOrder'],$row['datetime'],$row['state'],$row['totalPrice'],$row['userID'],$row['addressJson'],$row['orderLinesJson']);
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
