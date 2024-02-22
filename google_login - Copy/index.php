<?php
include 'header.php';

// Include Google API PHP client library
require_once "vendor/autoload.php";
require_once 'config.php';
// Create a Google Client

$client = new Google_Client();
$client->setClientId($clientId);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope('email');
$client->addScope('profile');
$authUrl = $client->createAuthUrl();

if (!isset($_GET['code'])) {
  // The code parameter is not present, so this is the index page

  echo '<a href="' . filter_var($authUrl, FILTER_SANITIZE_URL) . '">
    <div class="login-button-google clickable">
      <img src="images/google-logo.png">Sign In With Google
    </div>
  </a>';
} else {
  // The code parameter is present, so this is the login part

  // Handle the OAuth 2.0 server response
  $client->authenticate($_GET['code']);
  $token = $client->getAccessToken();
  $client->setAccessToken($token);

  // Get user info from Google using Google_Service_Oauth2
  $oauth2 = new Google_Service_Oauth2($client);
  $userInfo = $oauth2->userinfo->get();

  // Access user data
  $first_name = $userInfo->givenName;
  $last_name = $userInfo->familyName;
  $email = $userInfo->email;
  $gender = $userInfo->gender;
  $image_url = $userInfo->picture;

  // Store user data in the database
  $conn = mysqli_connect("localhost", "root", "", "student");

  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Sanitize the user data
  $first_name = mysqli_real_escape_string($conn, $first_name);
  $last_name = mysqli_real_escape_string($conn, $last_name);
  $email = mysqli_real_escape_string($conn, $email);
  $gender = mysqli_real_escape_string($conn, $gender);
  $image_url = mysqli_real_escape_string($conn, $image_url);

  // Download and store the user's image
  $image = file_get_contents($image_url);
  $image_filename = "images/user_image.jpg"; // You can generate a unique filename for each user
  file_put_contents($image_filename, $image);

  // Insert the user data into the database
  $sql = "INSERT INTO USERS_GOOGLE_LOGIN (first_name, last_name, email, gender, image) 
      VALUES ('$first_name', '$last_name', '$email', '$gender', '$image_filename')";

  if (mysqli_query($conn, $sql)) {
    // Redirect to a welcome page or user dashboard
    header("Location: welcome.php");
  } else {

    echo "Error: " . mysqli_error($conn);
  }

  mysqli_close($conn);
}

require_once 'footer.php';
