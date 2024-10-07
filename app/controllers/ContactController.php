<?php
require_once '../config/db.php'; // Include database connection
require_once '../app/models/Contact.php'; // Include Contact model

class ContactController {
    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $message = $_POST['message'];

            // Validate input data
            if (Contact::submitMessage($name, $email, $message)) {
                $message = 'Your message has been successfully submitted!';
                $status = 'success';
            } else {
                $message = 'There was an error submitting your message. Please try again.';
                $status = 'error';
            }

            // Set query parameters for success or error message
            $query = http_build_query(['message' => $message, 'status' => $status]);

            // Redirect back to the same page with query parameters
            header('Location: index.php?' . $query);
            exit();
        }
    }
}
