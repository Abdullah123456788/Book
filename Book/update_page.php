<?php
include "config.php";
include "connection.php";
session_start();
include "Book.php"; 

$bookObj = new Book($conn);

// Fetch book by ID
if (isset($_GET['book_id'])) {
    $book_id = (int)$_GET['book_id'];
    $book = $bookObj->getBookById($book_id);
    if (!$book) {
        echo "Book not found!";
        exit;
    }
} else {
    echo "book_id not set.";
    exit;
}
$countries = $bookObj->getAllCountries();
$cities = $bookObj->getCitiesByCountryId($book['c_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <title>Edit Book</title>
    <link rel="icon" type="image" href="images/logo.jpg">
    <script>
        function fetchCities(countryId) {
            const selectedCityId = document.getElementById('city').value;
            
            fetch(`action.php?country_id=${countryId}`)
                .then(response => response.json())
                .then(data => {
                    const citySelect = document.getElementById('city');
                    citySelect.innerHTML = '<option value="">Select City</option>';

                    if (data.length > 0) {
                        data.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.city_id;
                            option.textContent = city.city_name;
                            if (city.city_id == selectedCityId) {
                                option.selected = true;
                            }
                            citySelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'No city for this country';
                        citySelect.appendChild(option);
                    }
                })
                .catch(error => console.error('Error fetching cities:', error));
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center" style="margin: auto">
            <div class="col-md-8">
                <h1 class="text-center" style="text-decoration:underline;">Edit Book</h1>
                <form method="POST" action="action.php">
                    <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
                    
                    <div class="form-group">
                        <label for="title">Book Title:</label>
                        <input type="text" name="title" class="form-control" value="<?php echo ($book['title']); ?>" required>
                    </div>
                    <div class="form-group">
                      <label for="country">Country:</label>
                      <select name="country" id="country" class="form-control" onchange="fetchCities(this.value)" required>
                          <option value="">Select Country</option>
                      <?php foreach ($countries as $country): ?>
                          <option value="<?php echo $country['c_id']; ?>" <?php echo ($country['c_id'] == $book['c_id']) ? 'selected' : ''; ?>>
                             <?php echo ($country['c_name']); ?>
                          </option>
                      <?php endforeach; ?>
                        </select>
                     </div>
                    <div class="form-group">
                        <label for="city">City:</label>
                        <select name="city" id="city" class="form-control">
                            <option value="">Select City</option>
                            <?php foreach ($cities as $city): ?>
                                <option value="<?php echo $city['city_id']; ?>" <?php echo ($city['city_id'] === $book['city_id']) ? 'selected' : ''; ?>>
                                    <?php echo ($city['city_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <br>
                    <label for="book_type">Select Book Type</label>
                    <br>
                    <input type="radio" id="storybook" name="book_type" value="storybook" <?php echo ($book['book_type'] === 'storybook') ? 'checked' : ''; ?>>
                    <text for="storybook">Story Book</text>
                    <br>
                    <input type="radio" id="textbook" name="book_type" value="textbook" <?php echo ($book['book_type'] === 'textbook') ? 'checked' : ''; ?>>
                    <text for="textbook">Text Book</text>
                    <br>
                    <input type="radio" id="historybook" name="book_type" value="historybook" <?php echo ($book['book_type'] === 'historybook') ? 'checked' : ''; ?>>
                    <text for="historybook">History Book</text>
                    <br>
                    <input type="radio" id="novel" name="book_type" value="novel" <?php echo ($book['book_type'] === 'novel') ? 'checked' : ''; ?>>
                    <text for="novel">Novel</text>
                    <div>
                    <br>
                        <label for="book_category">Select Book Category</label><br>
                        <input type="checkbox" id="kids" name="book_category[]" value="kids" <?php echo (strpos($book['book_category'], 'kids') !== false) ? 'checked' : ''; ?>> Kids Book
                        <br>
                        <input type="checkbox" id="adult" name="book_category[]" value="adult" <?php echo (strpos($book['book_category'], 'adult') !== false) ? 'checked' : ''; ?>> Adult Book<br>
                        <br>
                    </div>
                    <br>
                    <div>
                          <label for="book_description">Book Description</label>
                          <textarea class="form-control" name="book_description" id="book_description" rows="3" placeholder="Enter Book Description"><?php echo isset($book['book_description']) ? ($book['book_description']) : ''; ?></textarea>
                          </div>
                     <br>
                    <button type="submit" name="action" value="update" class="btn btn-primary btn-block">Update</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
