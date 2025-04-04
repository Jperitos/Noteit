<?php
session_start();
require '../Connection/connect.php';

$titleError = $contentError = '';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; 
} else {
    die("User not logged in!");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    
    if (empty($title)) {
        $titleError = 'Title is required.';
    }

    if (empty($content)) {
        $contentError = 'Content is required.';
    }

    if (empty($titleError) && empty($contentError)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO notes (user_id, title, content, is_favorite, is_archived, created_at, updated_at) 
                                   VALUES (:user_id, :title, :content, :is_favorite, :is_archived, :created_at, :updated_at)");

          
            $is_favorite = 0; 
            $is_archived = 0;  
            $created_at = $updated_at = date('Y-m-d H:i:s'); 

            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':is_favorite', $is_favorite);
            $stmt->bindParam(':is_archived', $is_archived);
            $stmt->bindParam(':created_at', $created_at);
            $stmt->bindParam(':updated_at', $updated_at);

            if ($stmt->execute()) {
               
                $_SESSION['success_message'] = 'Note saved successfully!';
            } else {
                $_SESSION['error_message'] = 'Error: Could not save the note.';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Database error: ' . $e->getMessage();
        }
    } else {
        $_SESSION['titleError'] = $titleError;
        $_SESSION['contentError'] = $contentError;
        $_SESSION['title'] = $title;
        $_SESSION['content'] = $content;
    }

    header("Location: ../Dashboard.php");
    exit;
}
?>
