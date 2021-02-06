<?php
    session_start();
    if(isset($_SESSION['id']) && !empty($_GET['q'])){
        include 'connect.php';
        if(isset($_GET['search']) && $_GET['search'] == "on"){
            $stmt = $connect->prepare("SELECT clients.client_id,clients.address,clients.contract,clients.name FROM clients WHERE name LIKE '".$_GET['q']."%' ");
            $stmt->execute();
            $rows = $stmt->fetchAll();
            echo json_encode($rows);
        }
    }

?>