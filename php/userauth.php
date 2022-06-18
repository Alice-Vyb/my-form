<?php
session_start();
require_once "../config.php";

//register users
function registerUser($fullnames, $email, $password, $gender, $country)
{
    //create a connection variable using the db function in config.php
    $conn = db();
    //check if user with this email already exist in the database
    $email = $_POST['email']; 
    $exists = $conn->prepare("SELECT * FROM Students WHERE email=?");
    $exists->execute(['email']);
    $user = $exists->fetch();
    
    if ($user) {
        echo "User already existed";
    } else {
        $query = "INSERT INTO `Students` (`full_names`, `email`, `password`, `gender`, `country`) VALUES ( '$fullnames', '$email', '$password', '$gender', '$country')";

        if (mysqli_query($conn, $query)) {
            echo "User Successfully registered";
        } else {
            echo "Error";
        }
    }
    mysqli_close($conn);
}



//login users
function loginUser($email, $password)
{
    //create a connection variable using the db function in config.php
    $conn = db();
    echo "<h1 style='color: red'> LOG ME IN (IMPLEMENT ME) </h1>";
    // //open connection to the database and check if username exist in the database
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM `Students` WHERE `email` = '$email'";
    //if it does, check if the password is the same with what is given
    if (mysqli_query($conn, $sql)) {
        $sql = "SELECT * FROM Students WHERE email='" . $email . "' AND password ='" . $password . "'";
        $result = mysqli_query($conn, $sql);
        $rows = mysqli_num_rows($result);

        //if true then set user session for the user and redirect to the dasbboard

        if ($rows > 0) {
              $fetchRows = mysqli_fetch_array($result);
              $_SESSION['username'] = $fetchRows['full_names'];
           header('Location: ../dashboard.php');
        } else {
            header('Location: ../forms/login.html');
        }
        
        
    }
}



function resetPassword($email, $password)
{
    //create a connection variable using the db function in config.php
    $conn = db();
    // echo "<h1 style='color: red'>RESET YOUR PASSWORD (IMPLEMENT ME)</h1>";
    //open connection to the database and check if username exist in the database      
    $email = $_POST['email'];
    $password = $_POST['password'];
    $exists = $conn->prepare("SELECT * Students WHERE email= ?");
    $exists->execute([$email]);
    $emailexists = $exists->fetch();

    if (!$emailexists) {
        echo "<h1 style='color: red'>User does not exist</h1>";
    } else {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $conn = db();
        $sql =  "SELECT * FROM `Students` WHERE `email` = '$email'";
        $newpassword = "UPDATE `Students` SET `password` = '$password' WHERE `email` = '$email'";

        if (mysqli_query($conn, $newpassword)) {
            Header("Location: ../forms/login.html");
        }
        else{
            mysqli_error($conn);
        }
        mysqli_close($conn);
    }
}


function getusers()
{
    $conn = db();
    $sql = "SELECT * FROM Students";
    $result = mysqli_query($conn, $sql);
    echo "<html>
    <head></head>
    <body>
    <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
    <table border='1' style='width: 700px; background-color: magenta; border-style: none'; >
    <tr style='height: 40px'><th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th></tr>";
    if (mysqli_num_rows($result) > 0) {
        while ($data = mysqli_fetch_assoc($result)) {
            //show data
            echo "<tr style='height: 30px'>" .
                "<td style='width: 50px; background: blue'>" . $data['id'] . "</td>
                <td style='width: 150px'>" . $data['full_names'] .
                "</td> <td style='width: 150px'>" . $data['email'] .
                "</td> <td style='width: 150px'>" . $data['gender'] .
                "</td> <td style='width: 150px'>" . $data['country'] .
                "</td>
                <form action='action.php' method='post'>
                <input type='hidden' name='id'" .
                "value=" . $data['id'] . ">" .
                "<td style='width: 150px'> <button type='submit', name='delete'> DELETE </button>" .
                "</tr>";
        }
        echo "</table></table></center></body></html>";
    }
    //return users from the database
    //loop through the users and display them on a table
}

function deleteaccount($id)
{
    $conn = db();
    //delete user with the given id from the database
    $sql = "DELETE from `Students` WHERE `id`= '$id'";
    if ($conn) {
        if (mysqli_query($conn, $sql)) {
            echo "Students successfully deleted";
        } else {
            echo "Error";
        }
        mysqli_close($conn);
    }
}