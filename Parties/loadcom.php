<?php
if(!isset($path)){//si on utilise par le script
    include("../Parties/Classes.php");
    //connexion à la base de données
    $conn = new SQLconn();
    //récupération du numéro du dernier commentaire affiché
    $comNumber = $_GET["firstComment"];
    $post_id=$_GET["post_id"];
}

// Récupération des id des coms
$com_id_list = $conn->getComsByDate($post_id);
if(!$com_id_list){
    echo "Aucun commentaire";
    $path=false;
}else{
    // Suppression des posts déjà affichés
    for ($i = 0; $i < $comNumber; $i++) {
        array_shift($com_id_list);
    }
    if(!empty($com_id_list)){
        for ($i = 0; $i < 4; $i++) {
            if(!isset($com_id_list[$i])){
                $path=false;
                break;
            }
            // echo "taille".count($com_id_list)." i=".$i." C=".$com_id_list[$i]."<br>";
            $com_id = $com_id_list[$i];
            $com_data=$conn->getComData($com_id);
            $com_text=$com_data['comment_text'];
            $com_time=$com_data['created_time'];
            $user_id=$com_data['user_id'];
            $user_pseudo=$conn->getUserPseudo($user_id);
            echo (isset($com_text) ? "<div class=\"comments\"><div>" . "$com_text" . "</div>".(isset($com_time)? "<p class=\"date\">".(isset($user_pseudo)?"$user_pseudo le ":''). "$com_time" . "</p>" : "") . "</div>" : "");
        }
    }
       
}
//deconnexion de la base de données
// isset($path) ? '':$conn->closeDB();
?>