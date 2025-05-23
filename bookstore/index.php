<?php
session_start();

$servername = "db"; // Use "localhost" if not in Docker
$username = "root";
$password = "password";
$dbname = "bookstore";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add to cart
if (isset($_POST['ac'])) {
    $bookID = $conn->real_escape_string($_POST['ac']);
    $quantity = (int)$_POST['quantity'];

    $sql = "SELECT * FROM book WHERE BookID = '$bookID'";
    $result = $conn->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
        $price = $row['Price'];
        $totalPrice = $price * $quantity;
        $conn->query("INSERT INTO cart (BookID, Quantity, Price, TotalPrice) VALUES ('$bookID', $quantity, $price, $totalPrice)");
    }
}

// Empty cart
if (isset($_POST['delc'])) {
    $conn->query("DELETE FROM cart");
}

// Fetch all books
$books = $conn->query("SELECT * FROM book");

// Fetch cart items
$cartItems = $conn->query("SELECT book.BookTitle, book.Image, cart.Price, cart.Quantity, cart.TotalPrice 
                           FROM book JOIN cart ON book.BookID = cart.BookID");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bookstore</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<header>
    <blockquote>
        <a href="index.php"><img src="image/logo.png" alt="Logo"></a>
        <?php if (isset($_SESSION['id'])): ?>
            <form class="hf" action="logout.php"><input class="hi" type="submit" value="Logout"></form>
            <form class="hf" action="edituser.php"><input class="hi" type="submit" value="Edit Profile"></form>
        <?php else: ?>
            <form class="hf" action="register.php"><input class="hi" type="submit" value="Register"></form>
            <form class="hf" action="login.php"><input class="hi" type="submit" value="Login"></form>
        <?php endif; ?>
    </blockquote>
</header>

<blockquote>
    <!-- Books Display -->
    <table id="myTable" style="width:80%; float:left;">
        <tr>
            <?php while ($row = $books->fetch_assoc()): ?>
                <td>
                    <table>
                        <tr><td><img src="<?= $row["Image"] ?>" width="80%"></td></tr>
                        <tr><td style="padding: 5px;">Title: <?= $row["BookTitle"] ?></td></tr>
                        <tr><td style="padding: 5px;">ISBN: <?= $row["ISBN"] ?></td></tr>
                        <tr><td style="padding: 5px;">Author: <?= $row["Author"] ?></td></tr>
                        <tr><td style="padding: 5px;">Type: <?= $row["Type"] ?></td></tr>
                        <tr><td style="padding: 5px;">RM<?= $row["Price"] ?></td></tr>
                        <tr><td style="padding: 5px;">
                            <form method="post">
                                Quantity: <input type="number" name="quantity" value="1" style="width: 20%;" /><br>
                                <input type="hidden" name="ac" value="<?= $row['BookID'] ?>" />
                                <input class="button" type="submit" value="Add to Cart" />
                            </form>
                        </td></tr>
                    </table>
                </td>
            <?php endwhile; ?>
        </tr>
    </table>

    <!-- Cart Section -->
    <table style="width:20%; float:right;">
        <tr>
            <th style="text-align:left;">
                <i class="fa fa-shopping-cart" style="font-size:24px"></i> Cart
                <form style="float:right;" method="post">
                    <input type="hidden" name="delc"/>
                    <input class="cbtn" type="submit" value="Empty Cart">
                </form>
            </th>
        </tr>
        <?php $total = 0; ?>
        <?php while ($row = $cartItems->fetch_assoc()): ?>
            <tr>
                <td>
                    <img src="<?= $row["Image"] ?>" width="20%"><br>
                    <?= $row['BookTitle'] ?><br>RM<?= $row['Price'] ?><br>
                    Quantity: <?= $row['Quantity'] ?><br>
                    Total Price: RM<?= $row['TotalPrice'] ?>
                </td>
            </tr>
            <?php $total += $row['TotalPrice']; ?>
        <?php endwhile; ?>
        <tr>
            <td style="text-align: right; background-color: #f2f2f2;">
                Total: <b>RM<?= $total ?></b>
                <center>
                    <form action="checkout.php" method="post">
                        <input class="button" type="submit" name="checkout" value="CHECKOUT">
                    </form>
                </center>
            </td>
        </tr>
    </table>
</blockquote>
</body>
</html>
