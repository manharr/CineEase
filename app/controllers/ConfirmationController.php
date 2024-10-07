<?php

class ConfirmationController
{
    // Display the confirmation page
    public function index()
    {
        // Simply load the view without fetching any data
        require_once '../app/views/movie/confirmation.php';
    }
}
