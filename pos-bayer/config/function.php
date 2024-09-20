<?php

session_start();

require 'dbcon.php';

function validate($inputData) {
    global $conn;
    return trim(mysqli_real_escape_string($conn, $inputData));
}

function redirect($url, $status) { // Fixed typo
    $_SESSION['status'] = $status;
    header('Location: ' . $url);
    exit();
}

function alertMessage() {
    if (isset($_SESSION['status'])) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h6>' . htmlspecialchars($_SESSION['status'], ENT_QUOTES, 'UTF-8') . '</h6>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        unset($_SESSION['status']);
    }
}

function insert($tableName, $data) {
    global $conn;

    $table = validate($tableName);

    $columns = array_keys($data);
    $values = array_map(function($value) use ($conn) {
        return "'" . mysqli_real_escape_string($conn, $value) . "'";
    }, array_values($data));

    $finalColumn = implode(',', $columns);
    $finalValues = implode(',', $values);

    $query = "INSERT INTO $table ($finalColumn) VALUES ($finalValues)";
    return mysqli_query($conn, $query);
}

function update($tableName, $id, $data) {
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $updateDataString = "";

    foreach ($data as $column => $value) {
        $updateDataString .= $column . "='" . mysqli_real_escape_string($conn, $value) . "',";
    }

    $finalUpdateData = rtrim($updateDataString, ','); // Removed trailing comma

    $query = "UPDATE $table SET $finalUpdateData WHERE id='$id'";
    return mysqli_query($conn, $query);
}

function getAll($tableName, $status = NULL) {
    global $conn;

    $table = validate($tableName);
    $status = validate($status);

    if ($status) {
        $query = "SELECT * FROM $table WHERE status='$status'";
    } else {
        $query = "SELECT * FROM $table";
    }

    return mysqli_query($conn, $query);
}

function getById($tableName, $id) {
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $query = "SELECT * FROM $table WHERE id='$id' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            return [
                'status' => 200,
                'data' => $row,
                'message' => 'Record Found'
            ];
        } else {
            return [
                'status' => 404,
                'message' => 'No Data Found'
            ];
        }
    } else {
        return [
            'status' => 500,
            'message' => 'Something Went Wrong'
        ];
    }
}

function delete($tableName, $id) {
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $query = "DELETE FROM $table WHERE id='$id' LIMIT 1";
    return mysqli_query($conn, $query);
    return $result;
}

function checkParamId($type){
    if(isset($_GET[$type])){
        if($_GET[$type] != ''){
            return $_GET[$type];
        }else{
            return '<h5>No Id Found</h5>';
        }
    }else{
        return '<h5>No Id Given</h5>';
    }
}

function logoutSession(){

    unset($_SESSION['loggedIn']);
    unset($_SESSION['loggedInUser']);
}

?>