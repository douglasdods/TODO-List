<?php

include './DB/Connection.php';

function maxOrder()
{
    $conn = connection();
    $max_order = $conn->query('SELECT MAX(sort_order) FROM tasks', PDO::FETCH_NUM);
    $max_order = $max_order->fetch();
    return $max_order[0] + 1;
}

function updateOrderTask($id, $sort_order)
{
    $conn = connection();
    $sqlUpdate = 'UPDATE tasks SET sort_order = :sort_order WHERE uuid = :id';
    $stmt = $conn->prepare($sqlUpdate);
    $stmt->bindValue(':sort_order', $sort_order);
    $stmt->bindValue(':id', $id);
    if ($stmt->execute()){
        return true;
    }else{
        return false;
    }

}

function getTask($id)
{
    $conn = connection();
    $sql = "SELECT * FROM tasks WHERE uuid = :uuid";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':uuid', $id);
    $stmt->execute();
    $result = $stmt->fetchAll();
    if (empty($result)){
        return false;
    }else{
        return $result;
    }
}

function listTasks()
{
    $conn = connection();
    $sql = "SELECT uuid, content, sort_order FROM tasks WHERE 1 ORDER BY sort_order ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    header('Content-Type: application/json');
    if (empty($result)){
        $response=array(
            'status' => 0,
            'status_message' => 'Wow. You have nothing else to do. Enjoy the rest of your day!'
        );
    }else{
        $response=array(
            'status' => 1,
            'data' => $result
        );
        echo json_encode($response);
    }
}

function singleTask($id)
{
    $task = getTask($id);
    header('Content-Type: application/json');
    if (empty($task)){
        $response=array(
            'status' => 0,
            'status_message' =>'Task not found!.'
        );
        echo json_encode($response);
    } else {
        $response=array(
            'status' => 1,
            'data' => $task
        );
        echo json_encode($response);
    }
}

function createTask($type, $content)
{
    $conn = connection();
    header('Content-Type: application/json');
    $max_order = maxOrder();

    $sql = 'INSERT INTO tasks (type, content, sort_order, done, date_created) VALUES (?, ?, ?, ?, ?)';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(1, $type);
    $stmt->bindValue(2, $content);
    $stmt->bindValue(3,$max_order);
    $stmt->bindValue(4, 'false');
    $stmt->bindValue(5, date('Y-m-d'));

    if ($stmt->execute()){
        $response=array(
            'status' => 1,
            'status_message' =>'Task Added Successfully.'
        );
    }else{
        $response=array(
            'status' => 0,
            'status_message' =>'Task Addition Failed.'
        );
    }
    echo json_encode($response);

}

function deleteTask($id)
{

    $conn = connection();
    $sql = 'DELETE FROM tasks WHERE uuid = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    if ($stmt->execute()) {
        $response=array(
            'status' => 1,
            'status_message' =>'Task Deleted Successfully.'
        );
    } else {
        $response=array(
            'status' => 0,
            'status_message' =>'Task Deleted Failed.'
        );
    }
    echo json_encode($response);
}

function updateTask($id, $type, $content, $sort_order, $done)
{

    $conn = connection();

    $sql = 'UPDATE tasks SET type = :type , content = :content,done = :done WHERE uuid = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':type', $type);
    $stmt->bindValue(':content', $content);
    $stmt->bindValue(':done', $done);
    $stmt->bindValue(':id', $id);

    if ($stmt->execute()) {
        if(reorderTasks($id, $sort_order)) {
            $response=array(
                'status' => 1,
                'status_message' =>'Task Updated Successfully.'
            );
        } else {
            $response=array(
                'status' => 0,
                'status_message' =>'Task Order Update Failed.'
            );
        }
    } else {
        $response=array(
            'status' => 0,
            'status_message' =>'Task Updated Failed.'
        );
    }
    echo json_encode($response);
}

function reorderTasks($id, $sort_order)
{
    $stop = 0;
    $conn = connection();
    if (updateOrderTask($id,$sort_order)) {
        do {
            $sql = 'SELECT uuid FROM tasks WHERE sort_order = :sort_order AND uuid != :id';
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':sort_order', $sort_order);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $return = $stmt->fetchAll();
            if (empty($return)) {
                $stop = 1;
                return true;
            } else {
                $id = $return[0]['uuid'];
                $sort_order++;
                updateOrderTask($id,$sort_order);
            }

        } while ($stop != 1);
    } else {
        return false;
    }


}
