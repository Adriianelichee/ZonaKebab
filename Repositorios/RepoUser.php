<?php

class RepoUser implements RepoCRUD {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // METODOS CRUD
    public function getLastInsertId() {
        return $this->conexion->lastInsertId();
    }

    public function create($user) {
        $stmt = $this->conexion->prepare("INSERT INTO users (username, password, rol, email, phone) VALUES (:username, :password, :rol, :email, :phone)");
        $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        $stmt->execute(["username" => $user->getUsername(), "password" => $hashedPassword, "rol" => $user->getRol(), "email" => $user->getEmail(), "phone" => $user->getPhone()]);

        $userId = $this->conexion->lastInsertId();

        $addresses = $user->getAddresses();
        if (!empty($addresses)) {
            $stmtAddress = $this->conexion->prepare("INSERT INTO addresses (roadName, roadNumber) VALUES (:roadName, :roadNumber)");
            $stmtUserAddress = $this->conexion->prepare("INSERT INTO user_address (user_id, address_id) VALUES (:user_id, :address_id)");
            
            foreach ($addresses as $address) {
                $stmtAddress->execute(["roadName" => $address->getRoadName(), "roadNumber" => $address->getRoadNumber()]);
                $addressId = $this->conexion->lastInsertId();
    
                // Insertar en la tabla intermedia user_address
                $stmtUserAddress->execute(["user_id" => $userId, "address_id" => $addressId]);
            }
        }
        return $userId;
    }

    public function getUserByEmail($email) {
        $stmt = $this->conexion->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(["email" => $email]);
        
        if ($stmt->rowCount() > 0) {
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);
            $user = new User(
                $fila['idUser'], 
                $fila['username'], 
                $fila['password'], 
                $fila['rol'], 
                $fila['email'], 
                $fila['phone']
            );
            
            $user->setMonedero($fila['wallet'] ?? 0);
    
            // Obtener direcciones asociadas al usuario
            $stmtUserAddress = $this->conexion->prepare("
                SELECT a.* 
                FROM addresses a 
                INNER JOIN user_addresses ua ON a.idAddress = ua.idAddress
                WHERE ua.idUser = :user_id
            ");
            $stmtUserAddress->execute(["user_id" => $fila['idUser']]);
            
            while ($filaAddress = $stmtUserAddress->fetch(PDO::FETCH_ASSOC)) {
                $address = new Address(
                    $filaAddress['idAddress'], 
                    $filaAddress['roadName'], 
                    $filaAddress['roadNumber']
                );
                $user->addAddress($address);
            }
            
            return $user;
        }
        
        return null; // Retorna null si no se encuentra el usuario
    }

    public function updateWallet($userId, $amount) {
        try {
    
            // Verificar si el usuario existe y obtener el saldo actual
            $checkUserStmt = $this->conexion->prepare("SELECT idUser, monedero FROM users WHERE idUser = :id FOR UPDATE");
            $checkUserStmt->execute([':id' => $userId]);
            $userData = $checkUserStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$userData) {
                return [
                    'success' => false,
                    'message' => "Usuario no encontrado con ID: $userId"
                ];
            }
    
            $currentWallet = $userData['monedero'];
            $newWallet = $currentWallet + $amount;
    
            // Verificar si el nuevo saldo sería negativo
            if ($newWallet < 0) {
                return [
                    'success' => false,
                    'message' => "El saldo resultante sería negativo."
                ];
            }
    
            // Actualizar el monedero
            $sql = "UPDATE users SET monedero = :newWallet WHERE idUser = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([':newWallet' => $newWallet, ':id' => $userId]);
    
            if ($stmt->rowCount() > 0) {
                return [
                    'success' => true,
                    'newWallet' => $newWallet,
                    'message' => "Monedero actualizado con éxito"
                ];
            } else {
                return [
                    'success' => false,
                    'message' => "No se pudo actualizar el monedero. El saldo no ha cambiado."
                ];
            }
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            return [
                'success' => false,
                'message' => "Error al actualizar el monedero: " . $e->getMessage()
            ];
        }
    }


    public function update($id, $user) {    
        // Iniciar con los campos que siempre se actualizarán
        $sql = "UPDATE users SET username = :username, rol = :rol, email = :email, phone = :phone, monedero = :wallet";
        $params = [
            "id" => $id,
            "username" => $user->getUsername(),
            "rol" => $user->getRol(),
            "email" => $user->getEmail(),
            "phone" => $user->getPhone(),
            "wallet" => $user->getMonedero()
        ];
    
        // Si se proporciona una nueva contraseña, incluirla en la actualización
        if ($user->getPassword() !== null && $user->getPassword() !== '') {
            $sql .= ", password = :password";
            $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
            $params["password"] = $hashedPassword;
        }
    
        // Añadir la cláusula WHERE al final de la consulta
        $sql .= " WHERE idUser = :id";
    
        // Ejecutar la consulta
        $stmt = $this->conexion->prepare($sql);
        $result = $stmt->execute($params);
    
    
        // El resto del método permanece igual
        // Eliminar las asociaciones existentes de direcciones
        $stmtDeleteUserAddress = $this->conexion->prepare("DELETE FROM user_addresses WHERE idUser = :user_id");
        $stmtDeleteUserAddress->execute(["user_id" => $id]);
    
        // Insertar las nuevas direcciones asociadas
        $addresses = $user->getAddresses();
        if (!empty($addresses)) {
            $stmtAddress = $this->conexion->prepare("INSERT INTO addresses (roadName, roadNumber) VALUES (:roadName, :roadNumber)");
            $stmtUserAddress = $this->conexion->prepare("INSERT INTO user_addresses (idUser, idAddress) VALUES (:user_id, :address_id)");
            
            foreach ($addresses as $address) {
                // Verificar si la dirección ya existe
                $stmtCheckAddress = $this->conexion->prepare("SELECT idAddress FROM addresses WHERE roadName = :roadName AND roadNumber = :roadNumber");
                $stmtCheckAddress->execute(["roadName" => $address->getRoadName(), "roadNumber" => $address->getRoadNumber()]);
                $existingAddress = $stmtCheckAddress->fetch(PDO::FETCH_ASSOC);
    
                if ($existingAddress) {
                    $addressId = $existingAddress['idAddress'];
                } else {
                    $stmtAddress->execute(["roadName" => $address->getRoadName(), "roadNumber" => $address->getRoadNumber()]);
                    $addressId = $this->conexion->lastInsertId();
                }
    
                // Insertar en la tabla intermedia user_address
                $stmtUserAddress->execute(["user_id" => $id, "address_id" => $addressId]);
            }
        }
    
        return true;
    }

    public function delete($id) {
        // Eliminar asociaciones existentes de direcciones
        $stmtDeleteUserAddress = $this->conexion->prepare("DELETE FROM user_address WHERE user_id = :user_id");
        $stmtDeleteUserAddress->execute(["user_id" => $id]);
        
        // Eliminar el usuario
        $stmtDeleteUser = $this->conexion->prepare("DELETE FROM users WHERE idUser = :id");
        $stmtDeleteUser->execute(["id" => $id]);
        
        return true;
    }

    public function getById($id) {
        $stmt = $this->conexion->prepare("SELECT * FROM users WHERE idUser = :id");
        $stmt->execute(["id" => $id]);
        
        if ($stmt->rowCount() > 0) {
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);
            $user = new User(
                $fila['idUser'],
                $fila['username'],
                $fila['password'],
                $fila['rol'],
                $fila['email'],
                $fila['phone']
            );
            
            // Asegurar que el monedero se establezca correctamente
            $user->setMonedero($fila['monedero'] ?? 0);
    
            // Obtener direcciones asociadas al usuario
            $stmtUserAddress = $this->conexion->prepare("
                SELECT a.* 
                FROM addresses a 
                INNER JOIN user_addresses ua ON a.idAddress = ua.idAddress 
                WHERE ua.idUser = :user_id
            ");
            $stmtUserAddress->execute(["user_id" => $id]);
            
            while ($filaAddress = $stmtUserAddress->fetch(PDO::FETCH_ASSOC)) {
                $address = new Address(
                    $filaAddress['idAddress'],
                    $filaAddress['roadName'],
                    $filaAddress['roadNumber']
                );
                $user->addAddress($address);
            }
            
            return $user;
        }
        
        return null; // Retorna null si no se encuentra el usuario
    }

    public function getAll() {
        // Obtener todos los usuarios
        $stmt = $this->conexion->prepare("SELECT * FROM users");
        $stmt->execute();
        
        $users = [];
        
        if ($stmt->rowCount() > 0) {
            while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $user = new User($fila['idUser'], $fila['username'], $fila['password'], $fila['rol'], $fila['email'], $fila['phone']);
                
                // Obtener direcciones asociadas al usuario
                $stmtUserAddress = $this->conexion->prepare("
                    SELECT a.* 
                    FROM addresses a 
                    INNER JOIN user_addresses ua ON a.idAddress = ua.idAddress 
                    WHERE ua.idUser = :user_id
                ");
                $stmtUserAddress->execute(["user_id" => $fila['idUser']]);
                
                while ($filaAddress = $stmtUserAddress->fetch(PDO::FETCH_ASSOC)) {
                    $address = new Address(
                        $filaAddress['idAddress'], 
                        $filaAddress['roadName'], 
                        $filaAddress['roadNumber']
                    );
                    $user->addAddress($address);
                }
                
                $users[] = $user;
            }
        }
        
        return $users;
    }

    public function find($criterio) {
        // Implementación del método find
        $stmt = $this->conexion->prepare("SELECT * FROM users WHERE username LIKE :criterio OR email LIKE :criterio");
        $stmt->execute(["criterio" => '%' . $criterio . '%']);
        
        $users = [];
        
        if ($stmt->rowCount() > 0) {
            while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $user = new User($fila['idUser'], $fila['username'], $fila['password'], $fila['rol'], $fila['email'], $fila['phone']);
                
                // Obtener direcciones asociadas al usuario
                $stmtUserAddress = $this->conexion->prepare("SELECT a.* FROM addresses a INNER JOIN user_address ua ON a.idAddress = ua.address_id WHERE ua.user_id = :user_id");
                $stmtUserAddress->execute(["user_id" => $fila['idUser']]);
                
                while ($filaAddress = $stmtUserAddress->fetch(PDO::FETCH_ASSOC)) {
                    $address = new Address($filaAddress['idAddress'], $filaAddress['roadName'], $filaAddress['roadNumber']);
                    $user->addAddress($address);                    
                }
                
                $users[] = $user;
            }
        }
        return $users;
    }

    public function count() {
        // Implementación del método count
        $stmt = $this->conexion->prepare("SELECT COUNT(*) AS total FROM users");
        $stmt->execute();
        
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila['total'];
    }

}