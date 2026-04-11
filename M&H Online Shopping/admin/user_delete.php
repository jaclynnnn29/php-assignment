<?php
require '../_base.php';
auth('admin');

// Get the ID from the URL
$id = req('id');

// Delete the user from the database
$stm = $_db->prepare("DELETE FROM user WHERE user_id = ?");
$stm->execute([$id]);

temp('info', "User $id has been deleted.");
redirect('user_list.php');