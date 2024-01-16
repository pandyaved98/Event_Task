<?php
require_once('connect.php');

// Set default values if parameters are not provided
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$perPage = isset($_GET['perPage']) ? intval($_GET['perPage']) : 10;
$location = isset($_GET['location']) ? $_GET['location'] : null;
$category = isset($_GET['category']) ? $_GET['category'] : null;

// Calculate the offset based on the page and items per page
$offset = ($page - 1) * $perPage;

// Start building the SQL query
$sql = "SELECT * FROM events";
$totalEventsSql = "SELECT count(*) as total FROM events";

// Add location and category filters if provided
if ($location !== null || $category !== null) {
    $sql .= " WHERE";
    $totalEventsSql .= " WHERE";

    if ($location !== null) {
        $sql .= " location = '$location'";
        $totalEventsSql .= " location = '$location'";
    }

    if ($location !== null && $category !== null) {
        $sql .= " OR";
        $totalEventsSql .= " OR";

    }

    if ($category !== null) {
        $sql .= " category = '$category'";
        $totalEventsSql .= " category = '$category'";
    }
}

// Add LIMIT clause for pagination
$sql .= " LIMIT $offset, $perPage";

// // Fetch total number of events based on filters
// $totalEventsSql = "SELECT COUNT(*) as total FROM events";

$totalEventsResult = $conn->query($totalEventsSql);
$totalEvents = $totalEventsResult->fetch_assoc()['total'];

// Fetch events based on the constructed query
$result = $conn->query($sql);

$events = array();
while ($row = $result->fetch_assoc()) {
    // Build the full URL for the image
    $bannerImage = $row['banner_image'];
    $imageUrl = 'http://localhost/allevent_task/backend/images/' . $bannerImage;

    // Add the URL to the event row
    $row['banner_image_url'] = $imageUrl;

    // Add the event row to the array
    $events[] = $row;
}

// Construct the meta information
$meta = [
    'totalEvents' => $totalEvents,
    'perPage' => $perPage,
    'currentPage' => $page,
    'totalPages' => ceil($totalEvents / $perPage),
];

// Combine events and meta in the response
$response = [
    'events' => $events,
    'meta' => $meta,
];

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);

// Close the database connection
$conn->close();
?>