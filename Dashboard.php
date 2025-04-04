<?php
session_start();

require './Connection/connect.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; 
} else {
    die("User not logged in!");
}

unset($_SESSION['titleError']);
unset($_SESSION['contentError']);
unset($_SESSION['title']);
unset($_SESSION['content']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="https://img.icons8.com/color/48/nginx.png">
    <link rel="stylesheet" href="./style/style-home.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Note It : Dashboard</title>
</head>
<body>
    <div class="sidebar">
        <h1>Note<span>It!</span></h1>
        <br>
        <a href=""><i class='bx bx-note'></i>All Notes</a>
        <a href=""><i class='bx bx-heart'></i>Favorites</a>
        <a href=""><i class='bx bx-box'></i>Archives</a>
        <a href="index.php" style="margin-left: -8px;"><i class='bx bx-log-out-circle'></i>Logout</a>
        
        <div class="profile">         
        <div class="profile-image"><i class='bx bxs-circle'></i></div>  
        <div class="profile-name">
        <?php
            if (isset($_SESSION['u_uname'])) {
                echo "<h4>Hi " . htmlspecialchars($_SESSION['u_uname']) . "!</h4>";
                echo "<p style='font-size: 14px;'>Welcome Back.</p>";
            }
        ?>
        </div>
    </div>
    </div>

    <div class="wrapper-admin">
        <section id="notes">
            <div class="notes">
                <h1>All Notes</h1>
                <div class="searchbar">
                    <input type="text" id="searchInput" placeholder="Search">
                    <p class="add-notes">
                        <i class='bx bxs-message-square-add' id="openModalBtn"></i> Add Notes
                    </p>
                </div>                
            </div>

            <div id="addNoteModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeModalBtn">&times;</span>
        <h2>Add a New Note</h2>
        <form action="./conmod/savenotes.php" method="POST" id="noteForm">
            <label for="noteTitle">Title:</label>
            <input type="text" id="noteTitle" name="title" placeholder="Note Title" value="<?php echo isset($_SESSION['title']) ? htmlspecialchars($_SESSION['title']) : ''; ?>">
            <?php 
            if (isset($_SESSION['titleError']) && !empty($_SESSION['titleError'])) {
                echo "<p style='color:red; margin-top: -10px; font-size:14px;'>".$_SESSION['titleError']."</p>";
            }
            ?>

            <label for="noteContent">Content:</label>
            <textarea id="noteContent" name="content" placeholder="Note Content"><?php echo isset($_SESSION['content']) ? htmlspecialchars($_SESSION['content']) : ''; ?></textarea>
            <?php 
            if (isset($_SESSION['contentError']) && !empty($_SESSION['contentError'])) {
                echo "<p style='color:red; margin-top: -20px; font-size:14px;'>".$_SESSION['contentError']."</p>";
            }
            ?>

            <div class="modal-buttons">
                <button type="submit" class="save-btn">Save Note</button>
                <button type="button" class="cancel-btn" id="cancelBtn">Cancel</button>
            </div>
        </form>
    </div>
</div>

            <div class="details-container">
            <?php
                $stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();
                $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($notes as $note) {
                    echo "<div class='box' data-title='" . htmlspecialchars($note['title'], ENT_QUOTES) . "' data-content='" . htmlspecialchars($note['content'], ENT_QUOTES) . "'>";
                    echo "<div class='title'><h3>" . htmlspecialchars($note['title'], ENT_QUOTES) . "</h3><i class='bx bx-dots-horizontal-rounded'></i></div>";
                    echo "<p>" . htmlspecialchars($note['content'], ENT_QUOTES) . "</p>";
                    echo "<div class='date'><i class='bx bxs-circle'></i><p>" . htmlspecialchars($note['created_at'], ENT_QUOTES) . "</p></div>";
                    echo "</div>";
                }
                ?>
                
            </div>
        </section>
    </div>

    <script src="./Javascript/script.js"></script>

</body>
</html>
