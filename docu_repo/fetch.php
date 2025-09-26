<?php
include 'db.php';

$sort = $_GET['sort'] ?? 'id';
$search = $_GET['search'] ?? '';

$allowed = ['id', 'doc_id', 'doc_type'];
if (!in_array($sort, $allowed)) {
    $sort = 'id';
}

$sql = "SELECT * FROM documents WHERE 
        doc_id LIKE ? OR 
        doc_type LIKE ? OR 
        file_name LIKE ? 
        ORDER BY $sort ASC";

$stmt = $conn->prepare($sql);
$searchTerm = "%" . $search . "%";
$stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$docs = [];
while ($row = $result->fetch_assoc()) {
    $docs[] = $row;
}

header('Content-Type: application/json');
echo json_encode($docs);
?>
