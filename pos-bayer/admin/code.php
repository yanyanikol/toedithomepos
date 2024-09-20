<?php

include('../config/function.php');

// Error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['saveAdmin'])) {

    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);
    $is_ban = isset($_POST['is_ban']) ? 1 : 0; // Correct checkbox name

    if ($name != '' && $email != '' && $password != '') {

        // Check if email already exists
        $emailCheck = mysqli_query($conn, "SELECT * FROM admins WHERE email='$email'");
        if ($emailCheck) {
            if (mysqli_num_rows($emailCheck) > 0) {
                redirect('admins-create.php', 'Email already used by another user.');
            } 
        } else {
            die('Error checking email: ' . mysqli_error($conn)); // Error handling
        }

        $bcrypt_password = password_hash($password, PASSWORD_BCRYPT); // Corrected constant

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $bcrypt_password, // Use hashed password
            'phone' => $phone,
            'is_ban' => $is_ban
        ];

        $result = insert('admins', $data); // Fixed typo: reslut -> result
        if ($result) {
            redirect('admins.php', 'Admin Created Successfully!');
        } else {
            redirect('admins-create.php', 'Something Went Wrong!');
        }

    } else {
        redirect('admins-create.php', 'Please fill required fields.');
    }

}

if(isset($_POST['saveAdmin']))
{
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);
    $is_ban = isset($_POST['is_ban']) ? 1 : 0; // Correct checkbox name

    if ($name != '' && $email != '' && $password != '') {

        // Check if email already exists
        $emailCheck = mysqli_query($conn, "SELECT * FROM admins WHERE email='$email'");
        if ($emailCheck) {
            if (mysqli_num_rows($emailCheck) > 0) {
                redirect('admins-create.php', 'Email already used by another user.');
            } 
        } else {
            die('Error checking email: ' . mysqli_error($conn)); // Error handling
        }

        $bcrypt_password = password_hash($password, PASSWORD_BCRYPT); // Corrected constant

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $bcrypt_password, // Use hashed password
            'phone' => $phone,
            'is_ban' => $is_ban
        ];

        $result = insert('admins', $data); // Fixed typo: reslut -> result
        if ($result) {
            redirect('admins.php', 'Admin Created Successfully!');
        } else {
            redirect('admins-create.php', 'Something Went Wrong!');
        }

    } else {
        redirect('admins-create.php', 'Please fill required fields.');
    }

}

if(isset($_POST['updateAdmin']))
{
    $adminId = validate($_POST['adminId']);

    $adminData = getByID('admins', $adminId);
    if($adminData['status'] != 200){
        redirect('admins-create.php?id=' .$adminId, 'Please fill required fields.');
    }
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);
    $is_ban = isset($_POST['is_ban']) ? 1 : 0; // Correct checkbox name

    if($password != ''){
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    }else{
        $hashedPassword = $adminData['data']['password'];
    }

    if ($name != '' && $email != '')
    {
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword, // Use hashed password
            'phone' => $phone,
            'is_ban' => $is_ban
        ];

        $result = update('admins',$adminId, $data); // Fixed typo: reslut -> result
        if ($result) {
            redirect('admins-edit.php?id='.$adminId, 'Admin Updated Successfully!');
        }else {
            redirect('admins-create.php?id='.$adminId, 'Something Went Wrong!');
        }
    }
    else 
    {
        redirect('admins-create.php', 'Please fill required fields.');
    }
}
?>
