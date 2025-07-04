<?php
/**
*    File        : backend/models/students.php
*
*    Project     : CRUD PHP
*
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.5 ( prototype )
*/

function getAllStudents($conn) 
{
    $sql = "SELECT * FROM students";
    return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}

function getPaginatedStudents($conn, $limit, $offset) 
{
    $stmt = $conn->prepare("SELECT * FROM students LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getTotalStudents($conn) 
{
    $sql = "SELECT COUNT(*) AS total FROM students";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'];
}

function getStudentById($conn, $id) 
{
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function createStudent($conn, $fullname, $email, $age) 
{
    $sql = "INSERT INTO students (fullname, email, age) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $fullname, $email, $age);
    $stmt->execute();
    return 
    [
        'inserted' => $stmt->affected_rows,        
        'id' => $conn->insert_id
    ];
}

function updateStudent($conn, $id, $fullname, $email, $age) 
{
    $sql = "UPDATE students SET fullname = ?, email = ?, age = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $fullname, $email, $age, $id);
    $stmt->execute();
    return ['updated' => $stmt->affected_rows];
}

function deleteStudent($conn, $id) 
{
    $sql = "DELETE FROM students WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return ['deleted' => $stmt->affected_rows];
}
?>
