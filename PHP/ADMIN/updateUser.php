<?php
// Prevent any unwanted output and set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
ob_start();

// Set proper JSON header
header('Content-Type: application/json');

include '../connect.php';
include '../LOGIN/session.php';

// Debug log function
function debugLog($message, $data = null) {
    $logFile = __DIR__ . '/update_debug.log';
    $logMessage = date('Y-m-d H:i:s') . " - " . $message;
    if ($data !== null) {
        $logMessage .= " - " . print_r($data, true);
    }
    file_put_contents($logFile, $logMessage . "\n", FILE_APPEND);
}

if ($_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

try {
    // Get POST data
    $username = $_POST['username'] ?? '';
    $newUsername = $_POST['new_username'] ?? '';
    $email = $_POST['email'] ?? '';
    $namaWarung = $_POST['Nama_Warung'] ?? '';

    debugLog("Received request", $_POST);
    if (isset($_FILES['Gambar_Warung'])) {
        debugLog("File upload details", $_FILES['Gambar_Warung']);
    }

    $conn->begin_transaction();

    // Get current user data
    $stmt = $conn->prepare("SELECT u.*, k.ID_Warung 
                           FROM user u 
                           LEFT JOIN kantin k ON u.id_pengguna = k.ID_Pengguna 
                           WHERE u.username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('User not found');
    }

    $currentUser = $result->fetch_assoc();
    
    // Update user table
    $sql = "UPDATE user SET username = ?, email = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $newUsername, $email, $username);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to update user: ' . $stmt->error);
    }

    // If user is a seller and has kantin data
    if ($currentUser['role'] === 'seller' && $currentUser['ID_Warung']) {
        $updateFields = ["Nama_Warung = ?"]; 
        $params = [$namaWarung];
        $types = "s";

        // Handle image upload if provided
        if (isset($_FILES['Gambar_Warung']) && $_FILES['Gambar_Warung']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../ASSETS/KANTIN/';
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    throw new Exception('Failed to create upload directory');
                }
            }
            
            // Generate unique filename
            $fileExtension = strtolower(pathinfo($_FILES['Gambar_Warung']['name'], PATHINFO_EXTENSION));
            $newFileName = uniqid('warung_', true) . '.' . $fileExtension;
            $uploadFile = $uploadDir . $newFileName;

            // Validate file type
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($fileExtension, $allowedTypes)) {
                throw new Exception('Invalid file type. Allowed types: ' . implode(', ', $allowedTypes));
            }

            // Move uploaded file
            if (!move_uploaded_file($_FILES['Gambar_Warung']['tmp_name'], $uploadFile)) {
                debugLog("Failed to move uploaded file", [
                    'tmp_name' => $_FILES['Gambar_Warung']['tmp_name'],
                    'destination' => $uploadFile,
                    'upload_error' => error_get_last()
                ]);
                throw new Exception('Failed to upload image');
            }

            $updateFields[] = "Gambar_Warung = ?";
            $params[] = $newFileName;
            $types .= "s";
        }

        $sql = "UPDATE kantin SET " . implode(", ", $updateFields) . " WHERE ID_Warung = ?";
        $params[] = $currentUser['ID_Warung'];
        $types .= "i";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to update kantin: ' . $stmt->error);
        }
    }

    $conn->commit();
    
    // Clear any buffered output
    ob_clean();
    
    echo json_encode([
        'success' => true,
        'message' => 'Update successful'
    ]);

} catch (Exception $e) {
    $conn->rollback();
    debugLog("Error occurred", $e->getMessage());
    
    // Clear any buffered output
    ob_clean();
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
?>