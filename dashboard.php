<?php
session_start();
require_once ('db.php');
$checkEmailQuery = $conn->prepare("select * from tbl_registration where id = ?");
$checkEmailQuery->bind_param("i", $_SESSION['userId']);
$checkEmailQuery->execute();
$result = $checkEmailQuery->get_result();
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="login-registration.js"></script>
<script src="script.js"></script>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet"
    href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script>
            $(function () {
                $("#logout-div").button().on("click", function () {
                    ajaxLogout();
                });
            });
        </script>
<style>
.image-source {
    border-radius: 50%;
}
</style>
</head>

<body>
    <h2>Welcome To Homepage</h2>

    <div class="dashboard">

        <div style="height: 10px"></div>
        <div>

            <img src="<?php echo $row['avatar_path']; ?>"
                class="image-source"> <b>
                        <?php
                        echo " <br/> " . " <br/> " . " " . " " . $row['first_name'] . "  " . $row['last_name'];
                        ?>
                    </b>
                    <?php
                    echo " " . " " . " <br/> " . $row['email_id'];
                    ?>

                </div>
        <div style="height: 10px"></div>
        <div>
            <input type="button" class="btn-logout" value="Logout"
                id="logout-div">
        </div>
    </div>
</body>
</html>