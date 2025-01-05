<?php
$required = "moderator";
require_once "protected.php";
require_once 'db.php';

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$itemsPerPage = 8;
$offset = ($page - 1) * $itemsPerPage;

//select of all data for tours
$sql = "
    SELECT 
        t.tour_id, 
        t.tour_datetime, 
        t.capacity, 
        IFNULL(SUM(r.number_of_people), 0) AS used_slots, 
        t.state, 
        t.updated_at, 
        t.created_at, 
        t.payment_status, 
        t.payment_method,
        t.description, 
        t.number_of_guides
    FROM 
        tours t
    LEFT JOIN 
        reservations r ON t.tour_id = r.tour_id
    WHERE 
        t.tour_datetime >= CURDATE() -- Fetch only tours today and later
    GROUP BY 
        t.tour_id
    ORDER BY 
        t.tour_datetime ASC -- Order by the closest upcoming event
    LIMIT ?, ?;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $offset, $itemsPerPage);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$stmt->close();

$totalCountQuery = "SELECT COUNT(*) as total FROM tours WHERE tour_datetime >= CURDATE();";
$totalCountResult = $conn->query($totalCountQuery);
$totalCountRow = $totalCountResult->fetch_assoc();
$totalCount = $totalCountRow['total'];

$response = [
    'data' => $data,
    'total' => $totalCount,
    'currentPage' => $page,
    'itemsPerPage' => $itemsPerPage,
];

header('Content-Type: application/json');
echo json_encode($response);
?>
