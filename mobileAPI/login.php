<?php 
    require_once('db.php');
    echo "asd";
    if($_SERVER['REQUEST_METHOD']=='POST'){
        if(empty($_POST['email'])){
            echo "Invalid data";
        }else{
            
            $email = $_POST['email'];

            $query = mysql_query("SELECT * FROM users WHERE email='$email'");

            $stm = $db->prepare($query);
            $stm->execute();
            $row = $stm->fetchAll();

            echo json_encode($row);

            if($row['email'] == $email){
                echo "welcome";
            }else{
                echo "invalid email or password";
            }
        }
    }
?>

<?php 
