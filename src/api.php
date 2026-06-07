<?php
header('Content-Type: application/json; charset=UTF-8');
// Versión Owner: cabecera con charset
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

switch ($method) {

    // READ - GET /api.php o GET /api.php?id=1
    case 'GET':
        if (isset($_GET['categoria'])) {
            $cat = $conn->real_escape_string($_GET['categoria']);
            $result = $conn->query("SELECT * FROM productos WHERE categoria = '$cat'");
            $productos = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($productos);
            break;
        }
        if ($id) {
            $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $producto = $result->fetch_assoc();
            if ($producto) {
                echo json_encode($producto);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Producto no encontrado']);
            }
        } else {
            $result = $conn->query("SELECT * FROM productos ORDER BY id ASC");
            $productos = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($productos);
        }
        break;

    // CREATE - POST /api.php
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['nombre'], $data['precio'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Faltan campos obligatorios: nombre y precio']);
            break;
        }
        $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, stock) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssdi",
            $data['nombre'],
            $data['descripcion'] ?? '',
            $data['precio'],
            $data['stock'] ?? 0
        );
        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(['message' => 'Producto creado', 'id' => $conn->insert_id]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al crear el producto']);
        }
        break;

    // UPDATE - PUT /api.php?id=1
    case 'PUT':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Se requiere id']);
            break;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $conn->prepare("UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=? WHERE id=?");
        $stmt->bind_param("ssdii",
            $data['nombre'],
            $data['descripcion'] ?? '',
            $data['precio'],
            $data['stock'] ?? 0,
            $id
        );
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            echo json_encode(['message' => 'Producto actualizado']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Producto no encontrado o sin cambios']);
        }
        break;

    // DELETE - DELETE /api.php?id=1
    case 'DELETE':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Se requiere id']);
            break;
        }
        $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            echo json_encode(['message' => 'Producto eliminado']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Producto no encontrado']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
}

$conn->close();
?>
