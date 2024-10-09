<?php
class Book {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }
    public function addBook($title, $countryId, $cityId = null) {
        $sql = "INSERT INTO book (title,book_type,book_category,book_description, c_id, city_id) VALUES (?,?,?,?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssii", $title, $book_type,$book_category,$book_description, $countryId, $cityId);
        if (!$stmt->execute()) {
            die("Error adding book: " . $stmt->error);
        }
        $stmt->close();
    }
    public function getBooks() {
        $sql = "SELECT b.book_id, b.title, b.book_type, b.book_category,b.book_description, b.city_id, b.c_id, 
                COALESCE(c.city_name, 'No city') AS city_name,
                co.c_name FROM book b
                LEFT JOIN city c ON b.city_id = c.city_id
                LEFT JOIN country co ON b.c_id = co.c_id";
        return $this->conn->query($sql);
    }
    public function getBookById($book_id) {
        $sql = "SELECT * FROM book WHERE book_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $book = $result->fetch_assoc();
        $stmt->close();

        return $book;
    }
    public function deleteBook($book_id) {
        $stmt = $this->conn->prepare("DELETE FROM book WHERE book_id = ?");
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $stmt->close();
    }
    public function updateBook($book_id, $title,$book_type, $book_category,$book_description, $countryId, $cityId) {
        $stmt = $this->conn->prepare("UPDATE book SET title = ?, book_type = ?, book_category = ? ,book_description = ?, c_id = ?, city_id = ? WHERE book_id = ?");
        $stmt->bind_param("ssssiii", $title,$book_type,$book_category,$book_description, $countryId, $cityId, $book_id);
        if (!$stmt->execute()) {
            die("Error updating book: " . $stmt->error);
        }
        $stmt->close();
    }
    public function getAllCountries() {
        $query = "SELECT c_id, c_name FROM country";
        return $this->conn->query($query);
    }
    public function getCitiesByCountryId($countryId) {
        $sql = "SELECT city_id, city_name FROM city WHERE c_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $countryId);
        $stmt->execute();
        $result = $stmt->get_result();
        $cities = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $cities;
    }
}
?>
