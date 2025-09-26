<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $docId = $_POST['docId'];
    $docType = $_POST['docType'];

    if (isset($_FILES['docFile']) && $_FILES['docFile']['error'] == 0) {
        $fileName = basename($_FILES['docFile']['name']);
        $targetDir = "uploads/";
        $targetFile = $targetDir . uniqid() . "_" . $fileName;

        // Create uploads folder if not exists
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['docFile']['tmp_name'], $targetFile)) {
            $stmt = $conn->prepare("INSERT INTO documents (doc_id, doc_type, file_name) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $docId, $docType, $targetFile);
            $stmt->execute();
            $stmt->close();
            echo "success";
        } else {
            echo "File upload failed!";
        }
    } else {
        echo "No file uploaded!";
    }
}
?>
