<?php

// Hash password 
function hashPassword($pass)
{
    return md5($pass);
}

// Check password
function verifyPassword($inputPass, $storedPass)
{
    if (hashPassword($inputPass) == $storedPass) {
        return true;
    }
    if ($inputPass == $storedPass) {
        return true;
    }
    return false;
}

// If password stored plain text, upgrade it to md5
function upgradePassword($connection, $email, $inputPass)
{
    $hash = hashPassword($inputPass);
    $query = "UPDATE users SET pass='$hash' WHERE email='$email'";
    mysqli_query($connection, $query);
}

function workStatusLabel($status)
{
    switch ($status) {
        case 'not_started': return 'Not Started';
        case 'in_progress': return 'In Progress';
        case 'waiting': return 'Waiting for User';
        case 'solved': return 'Solved';
        default: return '—';
    }
}

// Find a free IT staff member 
function findFreeIT($connection)
{
    $query = "SELECT email FROM users WHERE role='IT' AND is_busy=0 ORDER BY RAND() LIMIT 1";
    $result = mysqli_query($connection, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        return $row['email'];
    }
    return null;
}

// Assign a ticket to an IT staff member and mark them is_busy=1
function assignTicket($connection, $ticketId, $itEmail)
{
    $query = "UPDATE tickets SET status='assigned', assigned_to='$itEmail', work_status='not_started' WHERE id=$ticketId";
    mysqli_query($connection, $query);

    $query = "UPDATE users SET is_busy=1 WHERE email='$itEmail'";
    mysqli_query($connection, $query);
}

// assign new tickets to free IT staff
function tryAssignNewTicket($connection, $ticketId)
{
    $itEmail = findFreeIT($connection);
    if ($itEmail != null) {
        assignTicket($connection, $ticketId, $itEmail);
        return $itEmail;
    }
    return null;
}

// start with the oldest ticket on hold
function tryAssignPendingTicket($connection, $itEmail)
{
    $query = "SELECT is_busy FROM users WHERE email='$itEmail' AND role='IT'";
    $result = mysqli_query($connection, $query);
    $user = mysqli_fetch_array($result);

    if ($user['is_busy'] == 1) {
        return;
    }

    $query = "SELECT id FROM tickets WHERE status='on_hold' ORDER BY created_at ASC LIMIT 1";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $ticket = mysqli_fetch_array($result);
        assignTicket($connection, $ticket['id'], $itEmail);
    }
}

// Free an IT staff  after closing ticket
function freeITStaff($connection, $itEmail)
{
    $query = "UPDATE users SET is_busy=0 WHERE email='$itEmail'";
    mysqli_query($connection, $query);
}

?>
