<?php

class RepoDirec implements RepoCrud {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function create($address) {
        // Preparar la consulta SQL
        $stmt = $this->conexion->prepare("INSERT INTO Addresses (roadName, roadNumber) VALUES (:roadName, :roadNumber)");
    
        // Ejecutar la consulta
        $result = $stmt->execute([
            "roadName" => $address->getRoadName(),
            "roadNumber" => $address->getRoadNumber()
        ]);
    
        if ($result) {
            // Obtener el id del nuevo registro
            $id = $this->conexion->lastInsertId();
        
            // Asignar el id al objeto Address
            $address->setId($id);
        
            return $address;
        } else {
            // Si la inserción falla, devolver false
            return false;
        }
    }

    public function createAddressUser($address, $userId) {
        // Preparar la consulta SQL
        $stmtAddress = $this->conexion->prepare("INSERT INTO Addresses (roadName, roadNumber) VALUES (:roadName, :roadNumber)");
    
        // Ejecutar la consulta
        $resultAddress = $stmtAddress->execute([
            "roadName" => $address->getRoadName(),
            "roadNumber" => $address->getRoadNumber()
        ]);
    
        if ($resultAddress) {
            // Obtener el id del nuevo registro de dirección
            $addressId = $this->conexion->lastInsertId();
        
            // Asignar el id al objeto Address
            $address->setId($addressId);
        
            // Preparar la consulta SQL para insertar en user_address
            $stmtUserAddress = $this->conexion->prepare("INSERT INTO user_addresses (idUser, idAddress) VALUES (:userId, :addressId)");
        
            // Ejecutar la consulta
            $resultUserAddress = $stmtUserAddress->execute([
                "userId" => $userId,
                "addressId" => $addressId
            ]);
        
            if ($resultUserAddress) {
                // Si ambas inserciones son exitosas, confirmar la transacción
                return $address;
            } else {
                // Si la inserción en user_address falla, revertir la transacción
                return false;
            }
        } else {
            // Si la inserción en Addresses falla, revertir la transacción
            return false;
        }
    }

    public function update($id, $addr) {
        // Asegurarse de que el id del objeto Address está actualizado
        $addr->setId($id);
    
        // Preparar la consulta SQL
        $stmt = $this->conexion->prepare("UPDATE Addresses SET roadName = :roadName, roadNumber = :roadNumber WHERE id = :id");
    
        // Ejecutar la consulta
        $result = $stmt->execute([
            "roadName" => $addr->getRoadName(),
            "roadNumber" => $addr->getRoadNumber(),
            "id" => $id
        ]);
    
        // Devolver true si la actualización fue exitosa, false en caso contrario
        return $result;
    }

    public function delete($id) {
        // Implementación del método delete
        $stmt = $this->conexion->prepare("DELETE FROM Addresses WHERE id = :id");
        $stmt->execute(["id" => $id]);
        return true;
    }

    public function getById($id) {
        // Implementación del método getById
        $stmt = $this->conexion->prepare("SELECT * FROM Addresses WHERE id = :id");
        $stmt->execute(["id" => $id]);
        
        if ($stmt->rowCount() > 0) {
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);
            return new Address($fila['id'], $fila['roadName'], $fila['roadNumber']);
        }
        return null;
    }

    public function getAddressesByUserId($userId) {
        // Preparar la consulta SQL
        $stmt = $this->conexion->prepare("
            SELECT a.* 
            FROM Addresses a
            INNER JOIN user_addresses ua ON a.idAddress = ua.idAddress
            WHERE ua.idUser = :userId
        ");
        
        // Ejecutar la consulta
        $stmt->execute(["userId" => $userId]);
        
        $direcciones = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $direcciones[] = new Address(
                $fila['idAddress'],
                $fila['roadName'],
                $fila['roadNumber']
            );
        }
        return $direcciones;
    }

    public function getAll() {
        // Implementación del método getAll
        $stmt = $this->conexion->prepare("SELECT * FROM Addresses");
        $stmt->execute();
        
        $direcciones = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $direcciones[] = new Address($fila['idAddress'], $fila['roadName'], $fila['roadNumber']);
        }
        return $direcciones;
    }

    public function find($criterio) {
        // Implementación del método find
        // Puedes implementar este método según tus necesidades específicas
    }

    public function count() {
        // Implementación del método count
        $stmt = $this->conexion->prepare("SELECT COUNT(*) AS total FROM Address");
        $stmt->execute();
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila['total'];
    }
}