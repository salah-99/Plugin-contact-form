<?php
/*
Plugin Name: Contact Form
Description: Simple WordPress Contact Form
Version: 0.1
Author: Salah
*/

//Creation de la connection avec la base de donné de wordpress
require_once(ABSPATH . 'wp-config.php');
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysqli_select_db($con, DB_NAME);



//Fonction de la creation d'une nouvelle table pour le stockage des informations de la formulaire
function newTable()
{

    global $con;

    $sql = "CREATE TABLE contact(id int NOT NULL PRIMARY KEY AUTO_INCREMENT, firstname varchar(255) NOT NULL, lastname varchar(255) NOT NULL, email varchar(255) NOT NULL, subj varchar(255) NOT NULL, msg varchar(255) NOT NULL)";
    $res = mysqli_query($con, $sql);
    return $res;
}

//Creation du Table si la connection est établie
if ($con == true){

    newTable();
}


//Fonction pour laisser ou supprimer des champs du formulaire
function form($atts){
    $prenom= "";
    $nom= "";
    $mail= "";
    $sujet= "";
    $msg= "";

    extract(shortcode_atts(
        array(
            'firstname' => 'true',
            'lastname' => 'true',
            'email' => 'true',
            'subject' => 'true',
            'message' => 'true'
            
    ), $atts));

    if($firstname== "true"){
        $prenom = '<label style="font-family: Poppins Medium; color: rgb(31, 95, 133);">Prénom:</label><input type="text" name="fname" required>';
    }

    if($lastname== "true"){
        $nom = '<label style="font-family: Poppins Medium; color: rgb(31, 95, 133);">Nom:</label><input type="text" name="lname" required>';
    }

    if($email== "true"){
        $mail = '<label style="font-family: Poppins Medium; color: rgb(31, 95, 133);">Email:</label><input type="email" name="email" required>';
    }
    if($subject== "true"){
        $sujet = '<label style="font-family: Poppins Medium; color: rgb(31, 95, 133);">Sujet:</label><input type="text" name="subject" required>';
    }

    if($message== "true"){
        $msg = '<label style="font-family: Poppins Medium; color: rgb(31, 95, 133);">Message:</label><textarea name="msg"></textarea>';
    }



    echo '<form method="POST"  >' .$prenom.$nom.$mail.$sujet.$msg. '<input style="margin-top : 20px; background-color: rgb(35, 219, 201); color: white;" value="Envoyer" type="submit" name="send"></form>';
}



//Shortcode du plugin
add_shortcode('Form', 'form');



// Fonction d'envoi des informations au base de donnée
    function sendToDB($fname,$lname,$email,$subject,$msg)
    {
        global $con;

    $sql = "INSERT INTO contact(firstname,lastname,email,subj, msg) VALUES ('$fname','$lname','$email','$subject','$msg')";
    $res = mysqli_query($con , $sql);
    
    return $res;
    }



//L'envoi des informations au base de donnée 
    if(isset($_POST['send'])){

        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $msg = $_POST['msg'];
        

        sendToDB($fname,$lname,$email,$subject,$msg);
    
    }




    add_action("admin_menu", "addMenu");
    function addMenu()
    {
        add_menu_page("Contact Form", "Contact Form", 4, "Contact Form", "adminMenu");
    }

function adminMenu()
{
    echo <<< EOD
    <div style="font-size : 20px; display : flex; flex-direction : column;">
    <center><h1 style="color:black; font-family : monospace;">
      Formulaire de contact 
    </h1></center>
  
    <h3>
    Ce plugin génère un formulaire de contact avec 5 champs:
    </h3>
  
    <ul>
      <li>Prénom</li>
      <li>Nom</li>
      <li>email</li>
      <li>Sujet</li>
      <li>message</li>
    </ul>
  
    <h3>
    Utilisez le shortcode (code court) [Form] dans votre page pour générer le formulaire de contact
    </h3>
  
    <h3>
     Si vous souhaitez supprimer un champ, ajoutez simplement nameofthefield = "false" au shortcode
    </h3>
  
  
  
  </div>

EOD;
}

?>