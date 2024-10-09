<?php
include "config.php";
include "connection.php";
session_start();
include "Book.php"; 

$bookObj = new Book($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <title>Book List</title>
    <link rel="icon" type="image" href="images/logo.jpg">
</head>
<body>
<style>
body {
  background-image: url('images/library.jpg');
}
</style>
<div class="container">
    <div class="row justify-content-center" style="margin: auto">
        <div class="col-md-10">
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-info">
                    <?php
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center" style="margin: auto">
        <div class="col-md-10">
            <h1 class="text-center" style="text-decoration:underline; color:white;">Book List</h1>
            <div>
            <form method="GET" action="add_page.php" style="display:flex; justify-content: flex-end;"> 
                <button type="submit" class="btn btn-success btn-add" style="font-size: 20px; padding:auto; width: 10%;">Add</button>                          
            </form>
            </div>
            <table class="table table-bordered table-dark">
                <thead>
                    <tr>
                        <th class="bg-primary">Book Title</th>
                        <th class="bg-primary">Actions</th>
                        <th class="bg-primary">Book Type</th>
                        <th class="bg-primary">Book Category</th>
                        <th class="bg-primary">Book Description</th>
                        <th class="bg-primary">City</th>
                        <th class="bg-primary">Country</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $bookObj->getBooks(); 
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { ?>  
                            <tr class="bg-success">
                                <td><?php echo ucfirst($row["title"]); ?></td>
                                <td>
                                    <form method="POST" action="action.php" style="display:inline;">
                                        <input type="hidden" name="book_id" value="<?php echo $row["book_id"]; ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" onclick="return confirmDelete();" class="btn btn-danger btn-delete">Delete</button>
                                    </form>
                                    <form method="GET" action="update_page.php" style="display:inline;">
                                        <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                                        <button type="submit" class="btn btn-primary btn-edit">Edit</button>
                                    </form>
                                </td>
                                <td><?php echo !empty($row["book_type"]) ? ucfirst($row["book_type"]) : "No book type selected"; ?></td>
                                <td><?php echo !empty($row["book_category"]) ? ($row["book_category"]) : "No book category selected"; ?></td>
                                <td><?php echo !empty($row["book_description"]) ? ucfirst($row["book_description"]) : "No book Description"; ?></td>
                                <td><?php echo isset($row["city_name"]) ? ($row["city_name"]) : 'No city'; ?></td>
                                <td><?php echo ($row["c_name"]); ?></td>
                            </tr> 
                        <?php } 
                    } else { 
                        echo "<tr><td colspan='4'>No results found</td></tr>"; 
                    } 
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this item?");
    }
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>
