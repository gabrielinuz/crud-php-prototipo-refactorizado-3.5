<?php
/** 
 * @file: backend/controllers/studentsController.php
 * @important:
 * En PHP: Usamos $_GET para parámetros en la URL (propios de GET).
 * Usamos php://input cuando queremos leer el cuerpo crudo (POST, PUT, DELETE).
 * No existe $_PUT, $_DELETE ni $_BODY, por eso usamos php://input. 
 * */

require_once("./models/students.php");

// Para GET (usamos $_GET):
function handleGet($conn) 
{
    if (isset($_GET['id'])) 
    {
        $student = getStudentById($conn, $_GET['id']);
        echo json_encode($student);
    } 
    else if (isset($_GET['page']) && isset($_GET['limit'])) 
    {
        $page = (int)$_GET['page'];
        $limit = (int)$_GET['limit'];
        $offset = ($page - 1) * $limit;

        $students = getPaginatedStudents($conn, $limit, $offset);
        $total = getTotalStudents($conn);

        echo json_encode([
            'students' => $students, // ya es array
            'total' => $total        // ya es entero
        ]);
    }
    else
    {
        $students = getAllStudents($conn); // ya es array
        echo json_encode($students);
    }
}

// Para POST/PUT/DELETE (usamos php://input):
function handlePost($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);

    if (createStudent($conn, $input['fullname'], $input['email'], $input['age'])) 
    {
        echo json_encode(["message" => "Estudiante agregado correctamente"]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo agregar"]);
    }
}

function handlePut($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);
    if (updateStudent($conn, $input['id'], $input['fullname'], $input['email'], $input['age'])) 
    {
        echo json_encode(["message" => "Actualizado correctamente"]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo actualizar"]);
    }
}

function handleDelete($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);
    if (deleteStudent($conn, $input['id'])) 
    {
        echo json_encode(["message" => "Eliminado correctamente"]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo eliminar"]);
    }
}
?>
