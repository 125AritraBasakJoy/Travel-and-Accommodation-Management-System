<?php

class Hotel extends Controller {
    public function index() {
        // Safely get the 'url' from $_GET
        if (isset($_GET["url"])) {
            // Split the URL by "/"
            $urlParts = explode("/", $_GET["url"]);
            
            // Check if the second segment exists
            if (isset($urlParts[1]) && $urlParts[1] != null) {
                $this->view( $_GET["url"]);  // Use the second segment as the view
            } else {
                $this->view('hotel');  // Default to the 'Hotel' view
            }
        } else {
            // If the 'url' is not set in the query, default to 'Hotel'
            $this->view('hotel');
        }
    }
}

$Hotel = new Hotel;
$Hotel->index();