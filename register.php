<?php
include 'connect.php';

// Sign-Up Section
if (isset($_POST['signUp'])) {
    // Get form data
    $firstName = $_POST['fName'];
    $lastName = $_POST['lName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = md5($password); // Hash password using MD5 (not recommended for production, use password_hash() for real apps)

    // Check if email already exists
    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        echo "Email Address Already Exists!";
    } else {
        // Insert the new user into the database
        $insertQuery = "INSERT INTO users(firstName, lastName, email, password) 
                        VALUES ('$firstName', '$lastName', '$email', '$hashedPassword')";

        if ($conn->query($insertQuery) === TRUE) {
            // Redirect to index.php after successful registration
            header("Location: index.php");
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

// Sign-In Section
if (isset($_POST['signIn'])) {
    // Correct the variable name $_POST instead of $POST
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = md5($password); // Use MD5, but password_hash() is recommended for production

    // Correct SQL Query: Added `FROM users` in the SELECT statement
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$hashedPassword'";

    // Execute the query
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        session_start();
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $row['email']; // Store email in session
        header("Location: homepage.php"); // Redirect to homepage
        exit();
    } else {
        echo "Not Found, Incorrect Email or Password";
    }
}

?>
