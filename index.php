<?php 
	
	include './API/api.php';

    $request_method=$_SERVER["REQUEST_METHOD"];
    header('Content-Type: application/json');
    switch ($request_method) {
        case 'GET':
            if (!empty($_GET["task_id"])) {
                $task_id=intval($_GET["task_id"]);
                singleTask($task_id);
            } else {
                listTasks();
            }
            break;
        case 'POST':
            $type = $_POST["type"];
            $content = $_POST["content"];
            if ($type != 'shopping' && $type != 'work') {
                $response=array(
                    'status' => 0,
                    'status_message' =>'The task type you provided is not supported. You can only use shopping or work..'
                );
                echo json_encode($response);
                break;
            }
            if (empty($content)) {
                $response=array(
                    'status' => 0,
                    'status_message' =>'Bad move! Try removing the task instead of deleting its content.'
                );
                echo json_encode($response);
                break;
            }
            createTask($type, $content);
            break;
        case 'PUT':
            $post_vars = json_decode(file_get_contents("php://input"),true);
            $task_id = $_GET["task_id"];
            $type = $post_vars["type"];
            $content = $post_vars["content"];
            $sort_order = $post_vars["sort_order"];
            $done = $post_vars["done"];
            $task = getTask($task_id);
            if ($task == false) {
                $response=array(
                    'status' => 0,
                    'status_message' =>"Are you a hacker or something? The task you were trying to edit doesn't exist."
                );
                echo json_encode($response);
                break;

            }
            if ($type != 'shopping' && $type != 'work') {
                $response=array(
                    'status' => 0,
                    'status_message' =>'The task type you provided is not supported. You can only use shopping or work..'
                );
                echo json_encode($response);
                break;
            }
            if (empty($content)) {
                $response=array(
                    'status' => 0,
                    'status_message' =>'Bad move! Try removing the task instead of deleting its content.'
                );
                echo json_encode($response);
                break;
            }

            updateTask($task_id, $type, $content, $sort_order, $done);
            break;
        case 'DELETE':
            $task_id = $_GET["task_id"];

            $task = getTask($task_id);
            if ($task == false) {
                $response=array(
                    'status' => 0,
                    'status_message' =>"Good news! The task you were trying to delete didn't even exist."
                );
                echo json_encode($response);
                break;
            }
            deleteTask($task_id);
            break;
        default:
            // Invalid Request Method
            header("HTTP/1.0 405 Method Not Allowed");
            break;
    }