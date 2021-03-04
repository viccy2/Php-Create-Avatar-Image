<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once ('db.php');
require_once ('create-avatar.php');

// function for form validation
function validateData($data)
{
    $resultData = htmlspecialchars(stripslashes(trim($data)));
    return $resultData;
}

if (isset($_POST['action']) && $_POST['action'] == 'registration') {
    $first_name = validateData($_POST['firstName']);
    $last_name = validateData($_POST['lastName']);
    $email_id = validateData($_POST['emailId']);
    $contact_number = validateData($_POST['contactNumber']);
    $password = validateData($_POST['password']);
    $confirm_password = validateData($_POST['confirmPassword']);
    
    $nameFirstChar = $first_name[0];
    $target_path = createAvatarImage($nameFirstChar);
    
    $error_message = '';
    $checkEmailQuery = $conn->prepare("select * from tbl_registration where email_id = ?");
    $checkEmailQuery->bind_param("s", $email_id);
    $checkEmailQuery->execute();
    
    $result = $checkEmailQuery->get_result();
    if ($result->num_rows > 0) {
        
        $error_message = "Email ID already exists !";
        echo $error_message;    
    }    // Insert data into MySQL db
    else {
        $insertQuery = $conn->prepare("insert into tbl_registration(first_name,last_name,email_id,contact_number,password,avatar_path) values(?,?,?,?,?,?)");
        $password = md5($password);
        $insertQuery->bind_param("ssssss", $first_name, $last_name, $email_id, $contact_number, $password, $target_path);
        
        if ($insertQuery->execute()) {
            $success_message = "Thankyou for registering with us.You can login.";
            echo $success_message;
            exit();
        } else {
            $error_message = "Problem in Inserting New Record!";
        }
        $insertQuery->close();
        $conn->close();
        
        echo $error_message;
    }
}

// login procees
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    $emailId = validateData($_POST['emailId']);
    $password = validateData($_POST['password']);
    $password = md5($password);
    $error_message = '';
    
    $selectQuery = $conn->prepare("select * from tbl_registration where email_id = ? and password = ?");
    $selectQuery->bind_param("ss", $emailId, $password);
    $selectQuery->execute();
    
    $result = $selectQuery->get_result();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $_SESSION['userId'] = $row['id'];
            $success_message = "success";
            echo $success_message;
        } // endwhile
    } // endif
else {
        $error_message = "Invalid email Id or password !";
    } // endElse
    $conn->close();
    
    echo $error_message;
}

// logout
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    unset($_SESSION['email_id']);
    session_destroy();
    echo "success";
}

?>
