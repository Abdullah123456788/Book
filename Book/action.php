<?php
include "config.php";
include "connection.php";
include "Book.php"; 
session_start();
$bookObj = new Book($conn);

// Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'update' && isset($_POST['book_id'])) {
    $book_id = (int)$_POST['book_id'];
    $title = ($_POST['title']);
    $book_type = ($_POST['book_type']);
    $book_category = isset($_POST['book_category']) ? implode(",", $_POST['book_category']) : NULL;
    $book_description = ($_POST['book_description']);
    $countryId = $_POST['country'];
    $cityId = $_POST['city'];
    if (empty($cityId)) {
        $cityId = NULL; 
    }
    $bookObj->updateBook($book_id, $title, $book_type, $book_category, $book_description, $countryId, $cityId);
    $_SESSION['message'] = "Book updated successfully!";
    header("Location: home.php");
    exit();
}

// Add a new book
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn-add'])) {
    $title = $_POST['title'];
    $book_type = $_POST['book_type'];
    $book_category = isset($_POST['book_category']) ? implode(",", $_POST['book_category']) : NULL;
    $book_description = $_POST['book_description'];
    $countryId = $_POST['country'];
    $cityId = $_POST['city'];
    if (empty($cityId)) {
        $cityId = NULL; 
    }
    $stmt = $conn->prepare("INSERT INTO book (title, book_type, book_category, book_description, c_id, city_id) VALUES (?,?,?,?, ?, ?)");
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("ssssii", $title, $book_type,$book_category,$book_description, $countryId, $cityId);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Book added successfully!";
        header("Location: home.php");
        exit();
    } else {
        echo "Error adding book: " . $stmt->error;
    }
    $stmt->close();
}

// Delete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['book_id'])) {
        $book_id = (int)$_POST['book_id'];
        $stmt = $conn->prepare("SELECT title FROM book WHERE book_id = ?");
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $book = $result->fetch_assoc();
        $bookObj->deleteBook($book_id);
        $_SESSION['message'] = "Book '{$book['title']}' deleted successfully!";
        header("Location: home.php");
        exit();
    }

//city
if (isset($_GET['country_id'])) {
    $countryId = $_GET['country_id'];
    $sql = "SELECT city_id, city_name FROM city WHERE c_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $countryId);
    $stmt->execute();
    $result = $stmt->get_result();
    $cities = [];
    while ($city = $result->fetch_assoc()) {
        $cities[] = $city;
    }
    if (count($cities) > 0) {
        echo json_encode($cities);
    } else {
        echo json_encode(['message' => 'No city for this country']);
    }
    $stmt->close();
} else {
    echo json_encode([]);
}
?>
