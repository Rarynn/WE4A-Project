<?php include("./Parties/head.php") ?>
<?php include("./Parties/Classes.php") ?>
<?php

$cook = new Cookie();
// Vérification si l'utilisateur est authentifié
if ($cook->IssetCookie()) {

    // Connexion à la base de données
    $conn = new SQLconn();
    ?>
    <?php include("./Parties/header.php"); ?>

    <body>
        <?php

        if (isset($_GET['post_id']) && !empty($_GET['post_id'])) {
            $post_id = $_GET['post_id'];

            $post_data = $conn->getPostData($post_id);
            //si le post existe
            if ($post_data !== false) {
                $post_title = $post_data['post_title'];
                $post_text = $post_data['post_text'];
                $post_img = $post_data['post_img'];
                $post_time = $post_data['created_time'];
                //Nom de l'utilisateur actuel :
                $actual_user = $conn->getUserData($cook->getUsername())['user_id'];
                //Nom de l'utilisateur qui a posté :
                $user_id = $conn->getUserIdFromPostId($post_id);
                $user_pseudo = $conn->getUserPseudo($user_id);
                // echo "<h1>Post de $user_pseudo</h1>";
                //Nombre de likes :
                $num_likes = $conn->getNumLikes($post_id);
                ?>
                <div class="center">

                    <div class="post">
                        <div class="left">
                            <h1>
                                <?php echo "$post_title" ?>
                            </h1>
                            <div class="img-container">
                                <img src="<?php echo "$post_img" ?>" alt="<?php echo "$post_title" ?>">
                            </div>
                        </div>
                        <div class="com">
                            <div id="comment-container">
                                <?php
                                //variables pour le chargement des commentaires
                                $path = true; //si les commentaires existent
                                $post_id = $_GET['post_id'];
                                //Chargement de deux commentaires 
                                //Avant Ajax
                                $comNumber = 0;
                                include("./Parties/loadcom.php");

                                if ($path) {
                                    //si on a des commentaires, on charge le script qui permet de charger les commentaires au scroll
                                    ?>
                                    <script>
                                        loadCommentsOnScroll(<?php echo $post_id; ?>);
                                    </script>
                                    <?php
                                }
                                ?>
                            </div>
                            <form method="post" action="redirect.php">
                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                                <input type="hidden" name="commenter"
                                    value="<?php echo $actual_user ?>"><!--ici on récupère l'id de l'utilisateur qui commente puis on l'envoie -->
                                <input type="hidden" name="path" value="<?php echo basename(__FILE__); ?>">

                                <label for="comment_text">Commentaire :</label><br>
                                <textarea name="comment_text" id="comment_text" rows="3" cols="40"></textarea><br>
                                <input type="submit" value="Sauvegarder" name="com">
                            </form>
                        </div>
                        <div class="desc">
                            <div class="like-and-user">
                                <form method='post' action="./redirect.php">
                                    <input type='hidden' name='post_id' value='<?php echo $post_id ?>'>
                                    <input type='hidden' name='user_id' value='<?php echo $actual_user ?>'>
                                    <input type="hidden" name="path" value="<?php echo basename(__FILE__); ?>">
                                    <button type='submit' name="like">Like :
                                        <?php echo $num_likes ?>
                                    </button>
                                </form>
                                <form action="./Profil.php?user_pseudo=<?php echo $user_pseudo ?> " method="post">
                                    <input type="submit" value="VOIR LE PROFIL de <?= $user_pseudo ?>">
                                </form>
                            </div>
                            <div class="modif">
                                <?php if ($user_id == $actual_user) { ?>
                                    <form method="post" action="./action.php">
                                        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                                        <button type="submit" name="mod">Modifier</button>
                                    </form>
                                <?php }
                                if (isAdmin($actual_user) || $user_id == $actual_user) { ?>
                                    <form method="post" action="./action.php">
                                        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                                        <button type="submit" name="del">Effacer</button>
                                    </form>
                                    <?php
                                } ?>
                            </div>
                            <div class="usertext">
                                <div class="text">
                                    <?php echo "$post_text" ?>
                                </div>
                                <p class="date">
                                    <?php echo "$post_time" ?>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>

                <?php
            } else {
                echo "Aucun post trouvé";
            }
            include("./Parties/footer.php");
        } else {
            header("Location: ./index.php");
            exit();
        }
}
?>


</body>

</html>