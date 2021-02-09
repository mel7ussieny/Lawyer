<?php
session_start(); 
if(isset($_SESSION['id']) && isset($_GET['required']) && $_GET['required'] == "search-clients" && !empty($_GET['q'])){
    include 'connect.php';
    if(isset($_GET['search']) && $_GET['search'] == "on"){
        $stmt = $connect->prepare("SELECT clients.client_id,clients.address,clients.contract,clients.name FROM clients WHERE name LIKE '".$_GET['q']."%' ");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        echo json_encode($rows);
    }
}
if(isset($_SESSION['id']) && isset($_GET['required']) && $_GET['required'] == "search-disputes" && !empty($_GET['q'])){
    include 'connect.php';
    if(isset($_GET['search']) && $_GET['search'] == "on"){
        $stmt = $connect->prepare("SELECT disputes.dispute_id,disputes.title,disputes.ref_number,disputes.court,clients.name,disputes.en_name,disputes.en_lawyer,disputes.date FROM disputes
                                INNER JOIN clients ON 
                                clients.client_id = disputes.dclient_id
                                WHERE disputes.".$_GET['type']." LIKE '".$_GET['q']."%' ");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        echo json_encode($rows);
        // echo $stmt->queryString;
    }
}

?>