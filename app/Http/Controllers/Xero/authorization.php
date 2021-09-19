<?php
  ini_set('display_errors', 'On');
  require __DIR__ . '/vendor/autoload.php';
  require_once('storage.php');

  // localhost/xero-php/authorization.php

  // Storage Class uses sessions for storing access token (demo only)
  // you'll need to extend to your Database for a scalable solution
  $storage = new StorageClass();
  //session_start();
  $provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => env('XERO_CLIENT_ID'),
    'clientSecret'            => env('XERO_CLIENT_SECRET'),
    'redirectUri'             => route('xero.callback'),
    'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
    'urlAccessToken'          => 'https://identity.xero.com/connect/token',
    'urlResourceOwnerDetails' => 'https://api.xero.com/api.xro/2.0/Organisation'
  ]);

  // Scope defines the data your app has permission to access.
  // Learn more about scopes at https://developer.xero.com/documentation/oauth2/scopes
  $options = [
      'scope' => ['openid email profile offline_access assets projects accounting.settings accounting.transactions accounting.contacts accounting.journals.read accounting.reports.read accounting.attachments']
  ];

  // This returns the authorizeUrl with necessary parameters applied (e.g. state).
  $authorizationUrl = $provider->getAuthorizationUrl($options);

  // Save the state generated for you and store it to the session.
  // For security, on callback we compare the saved state with the one returned to ensure they match.
  $_SESSION['oauth2state'] = $provider->getState();

// ++++++++++++++++++++ NEW +++++++++++++++++++++
    $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : 'NONE';
    $session = isset($_GET['session']) ? $_GET['session'] : null;
    $training_option_id = isset($_GET['training_option_id']) ? $_GET['training_option_id'] : null;
    $_SESSION['post_type'] = $post_type;
    $_SESSION['session'] = $session;
    $_SESSION['training_option_id'] = $training_option_id;
// ++++++++++++++++++++ NEW +++++++++++++++++++++

  // Redirect the user to the authorization URL.
  header('Location: ' . $authorizationUrl);
  exit();
?>
