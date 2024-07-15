<?php

// return true or false depends on the user is logged in or not
function is_login()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['id'])) {
        return true;
    }
    return false;
}

// return the user id if the user is logged in
function get_user_id()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['id'])) {
        return $_SESSION['id'];
    }
    return false;
}

function is_company()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['isCompany'])) {
        return true;
    }
    return false;
}
