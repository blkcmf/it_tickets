<?php
require 'checkSession.php';
if ($_SESSION['role'] != 'EMP') {
    header('location:authForm.php?auth=role');
}

require 'config.php';
require 'dbconnection.php';
require 'trace.php';
require 'myfunctions.php';

$title = $_POST['title'];
$category = $_POST['category'];
$description = $_POST['description'];
$employee = $_SESSION['user'];
$createdAt = date('Y-m-d H:i:s');
$screenshot = null;

// Handle optional screenshot upload
if (isset($_FILES['screenshot']) && $_FILES['screenshot']['error'] == 0) {
    if ($_FILES['screenshot']['type'] != 'image/jpeg' &&
        $_FILES['screenshot']['type'] != 'image/jpg' &&
        $_FILES['screenshot']['type'] != 'image/png') {
        header('location:submitTicketForm.php?error=typephoto');
        exit;
    }
    if ($_FILES['screenshot']['size'] > $maxsizefile) {
        header('location:submitTicketForm.php?error=sizefile');
        exit;
    }

    $source = $_FILES['screenshot']['tmp_name'];
    $screenshot = "screenshots/" . uniqid() . ".jpg";
    move_uploaded_file($source, $screenshot);
}

// 1. Insert ticket (starts on hold)
$screenshotValue = $screenshot ? "'$screenshot'" : "NULL";
$query = "INSERT INTO tickets (employee_email, category, title, description, screenshot, status, created_at)
          VALUES ('$employee', '$category', '$title', '$description', $screenshotValue, 'on_hold', '$createdAt')";
mysqli_query($connection, $query);
trace("Ticket submitted: $title");

// 2. Get the new ticket id
$query = "SELECT max(id) as lastId FROM tickets";
$result = mysqli_query($connection, $query);
$data = mysqli_fetch_array($result);
$ticketId = $data['lastId'];

// 3. Try to assign to a free IT staff
$assignedTo = tryAssignNewTicket($connection, $ticketId);
if ($assignedTo != null) {
    trace("Ticket #$ticketId assigned to $assignedTo");
} else {
    trace("Ticket #$ticketId on hold (no free IT staff)");
}

mysqli_close($connection);
header('location:myTickets.php');
?>
