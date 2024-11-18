<?php
session_start();
require 'dbc.php';

// Input validation function
function validate($inputData) {
    global $conn;
    $validatedData = mysqli_real_escape_string($conn, $inputData);
    return trim($validatedData);
}

// Redirect to another page with a status message
function redirect($url, $status) {
    $_SESSION['status'] = $status;
    header('Location: ' . $url);
    exit(0);
}

// Display a message or status after any process
function alertMessage() {
    if (isset($_SESSION['status'])) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <h6>' . $_SESSION['status'] . '</h6>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
        unset($_SESSION['status']);
    }
}

// Insert a record into the database
function insert($tableName, $data) {
    global $conn;

    // Sanitize the table name
    $table = mysqli_real_escape_string($conn, validate($tableName));

    // Prepare column names and values
    $columns = array_keys($data);
    $values = array_values($data);

    // Escape each value to prevent SQL injection
    $escapedValues = array_map(function($value) use ($conn) {
        return is_numeric($value) ? $value : "'" . mysqli_real_escape_string($conn, $value) . "'";
    }, $values);

    // Join the column names and values into a string
    $finalColumn = implode(',', $columns);
    $finalValues = implode(',', $escapedValues);

    // Build the SQL query
    $query = "INSERT INTO $table ($finalColumn) VALUES ($finalValues)";

    // Execute the query and check for success
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "Error inserting data: " . mysqli_error($conn);
    }

    return $result;
}

// Update data in the database
function update($tableName, $id, $data) {
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $updateDataString = "";

    foreach ($data as $column => $value) {
        $escapedValue = mysqli_real_escape_string($conn, $value);
        $updateDataString .= "$column='$escapedValue',";
    }

    $finalUpdateData = rtrim($updateDataString, ',');

    $query = "UPDATE $table SET $finalUpdateData WHERE id='$id'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "Error updating data: " . mysqli_error($conn);
    }

    return $result;
}

// Retrieve all records or records by status
function getAll($tableName, $status = NULL) {
    global $conn;

    $table = validate($tableName);

    if ($status === 'status') {
        $query = "SELECT * FROM $table WHERE status='0'";
    } else {
        $query = "SELECT * FROM $table";
    }

    return mysqli_query($conn, $query);
}

// Retrieve a record by ID
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
            'message' => 'Error: ' . mysqli_error($conn)
        ];
    }
}

// Delete a record from the database
function delete($tableName, $id) {
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $query = "DELETE FROM $table WHERE id='$id' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "Error deleting data: " . mysqli_error($conn);
    }

    return $result;
}

// Check if a parameter is passed in the URL
function checkParamId($type) {
    if (isset($_GET[$type]) && $_GET[$type] != '') {
        return $_GET[$type];
    } else {
        return '<h5>No Id Given</h5>';
    }
}

// End user session for logout
function logoutSession() {
    unset($_SESSION['loggedIn']);
    unset($_SESSION['loggedInUser']);
}

// Return a JSON response
function jsonResponse($status, $status_type, $message) {
    
    $response = [
        'status' => $status,
        'status_type' => $status_type,
        'message' => $message
    ];

    echo json_encode($response);
    return;
}

function getCount($tableName)
{
    global $conn;

    $table = validate($tableName);

    $query = "SELECT * FROM $table";
    $query_run = mysqli_query($conn, $query);
    if($query_run){
        
        $totalCount = mysqli_num_rows($query_run);
        return $totalCount;
    }else{
        return 'Something Went Wrong';
        
    }


    
}
?>