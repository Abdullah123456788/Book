<?php
include "config.php";
include "connection.php";
include "Book.php"; 
session_start();
$bookObj = new Book($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <title>Add Book</title>
    <link rel="icon" type="image" href="images/logo.jpg">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center" style="margin: auto">
            <div class="col-md-8">
                <h1 class="text-center" style="text-decoration:underline;">Add Book</h1>
                <form method="POST" action="action.php">
                    <div class="form-group">
                        <label for="book-title">Book Title:</label>
                        <input type="text" name="title" id="book-title" class="form-control" required placeholder="Enter Book Title">
                    </div>
                    <div class="form-group">
                        <label for="country">Select Country</label>
                        <select id="country" name="country" class="form-control" required>
                            <option value="">Select a Country</option>
                            <?php
                            $countries = $bookObj->getAllCountries();
                            if ($countries && $countries->num_rows > 0) {
                                while ($country = $countries->fetch_assoc()) { ?>
                                    <option value="<?php echo $country['c_id']; ?>"><?php echo $country['c_name']; ?></option>
                                <?php }
                            } else {
                                echo '<option value="">No countries available</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="city">Select City</label>
                        <select id="city" name="city" class="form-control" required>
                            <option value="">Select a City</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="book_type">Select Book Type</label>
                        <br>
                    <input type="radio" id="storybook" name="book_type" value="storybook">
                    <text for="storybook">Story Book</text>
                    <br>
                    <input type="radio" id="textbook" name="book_type" value="textbook">
                    <text for="textbook">Text Book</text>
                    <br>
                    <input type="radio" id="historybook" name="book_type" value="historybook">
                    <text for="historybook">History Book</text>
                     <br>
                    <input type="radio" id="novel" name="book_type" value="novel">
                    <text for="novel">novel</text>
                        </div>
                        <div class="form-group">
                        <label for="book_category">Select Book Category</label><br>
                        <input type="checkbox" id="kids" name="book_category[]" value="kids"> Kids Book<br>
                        <input type="checkbox" id="adults" name="book_category[]" value="adults"> Adults Book<br>
                    </div>
                    <div class="form-group">
                          <label for="book_description">Book Description</label>
                          <textarea class="form-control" name="book_description" id="book_description" rows="3" placeholder="Enter Book Description"></textarea>
                    </div>

                    <button type="submit" name="btn-add" class="btn btn-primary btn-block">Add Book</button>
                </form>
            </div>
        </div>
    </div>
    </body>
</html>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#country').change(function() {
                var countryId = $(this).val();
                $('#city').empty().append('<option value="">Select a City</option>');
                if (countryId) {
                    $.ajax({
                        url: 'action.php',
                        type: 'GET',
                        data: { country_id: countryId },
                        dataType: 'json',
                        success: function(response) {
                            if (response.message) {
                                $('#city').append('<option value="0">' + response.message + '</option>');
                            } else {
                                $.each(response, function(index, city) {
                                    $('#city').append('<option value="' + city.city_id + '">' + city.city_name + '</option>');
                                });
                            }
                        },
                        error: function() {
                            alert('Error retrieving cities.');
                        }
                    });
                }
            });
        });
    </script>

