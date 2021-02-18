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
                                WHERE disputes.".$_GET['type']." LIKE '".$_GET['q']."%' AND dis_status = 1");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        echo json_encode($rows);
    }
}
if(isset($_SESSION['id']) && isset($_GET['required']) && $_GET['required'] == "get-profit"){
    include 'connect.php';
    if(isset($_GET["d-start"]) && isset($_GET["d-end"])){
        $start = $_GET['d-start'];
        $end = $_GET['d-end'];

        $stmt = $connect->prepare("SELECT payments.*,disputes.title FROM payments LEFT JOIN disputes
        ON 
        payments.dispute_id = disputes.dispute_id 
        WHERE (payments.date BETWEEN '".$start." 0:00:00' AND '".$end." 0:00:00') ORDER BY payments.date DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $sum = 0;
        foreach($rows as $key => $value){
            if($rows[$key]["type"] == 0){
                $sum -= $rows[$key]["amount"];
            }else{
                $sum += $rows[$key]["amount"];
            }
        }
        $rows[0]["profit"] = ($sum > 0 ) ? "+$sum" : $sum;
        echo json_encode($rows);
    }
}
if(isset($_SESSION['id']) && isset($_GET['required']) && $_GET['required'] == "notifications"){
    include 'connect.php';
    if(isset($_GET['d-start']) && isset($_GET['d-end'])){
        // echo "YES";
        $start = $_GET['d-start'];
        $end = $_GET['d-end'];
        $type = $_GET['type'];
        
        if($type == 2){
            $type = "notes.dispute_id IS NOT NULL";
        }elseif($type == 3){
            $type = "notes.dispute_id IS NULL";
        }


        $stmt = $connect->prepare("SELECT notes.*,disputes.title,disputes.dispute_id FROM notes
        LEFT JOIN disputes 
        ON
        disputes.dispute_id = notes.dispute_id
        WHERE $type AND (notes.date BETWEEN '".$start." 0:00:00' AND '".$end." 0:00:00') ORDER BY notes.date DESC ");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        echo json_encode($rows);
        // echo $stmt->queryString;
    }
}

?>