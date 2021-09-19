<?php
  ini_set('display_errors', 'On');
  require __DIR__ . '/vendor/autoload.php';
  require_once('storage.php');

  // Use this class to deserialize error caught
  use XeroAPI\XeroPHP\AccountingObjectSerializer;
  // Storage Classe uses sessions for storing token > extend to your DB of choice
  $storage = new StorageClass();
  $xeroTenantId = (string)$storage->getSession()['tenant_id'];

  if ($storage->getHasExpired()) {
    $provider = new \League\OAuth2\Client\Provider\GenericProvider([
      'clientId'                => env('XERO_CLIENT_ID'),
      'clientSecret'            => env('XERO_CLIENT_SECRET'),
      'redirectUri'             => route('xero.callback'),
      'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
      'urlAccessToken'          => 'https://identity.xero.com/connect/token',
      'urlResourceOwnerDetails' => 'https://api.xero.com/api.xro/2.0/Organisation'
    ]);

    $newAccessToken = $provider->getAccessToken('refresh_token', [
      'refresh_token' => $storage->getRefreshToken()
    ]);

    // Save my token, expiration and refresh token
    $storage->setToken(
        $newAccessToken->getToken(),
        $newAccessToken->getExpires(),
        $xeroTenantId,
        $newAccessToken->getRefreshToken(),
        $newAccessToken->getValues()["id_token"] );
  }

  $config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken( (string)$storage->getSession()['token'] );
  $apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
      new GuzzleHttp\Client(),
      $config
  );

// ++++++++++++++++++++ NEW +++++++++++++++++++++
    $post_type = isset($_SESSION['post_type']) ? $_SESSION['post_type'] : 'NONE';
    $session = isset($_SESSION['session']) ? $_SESSION['session'] : null;
    $training_option_id = isset($_SESSION['training_option_id']) ? $_SESSION['training_option_id'] : null;
    // dd('aa = '.$post_type);
    if($post_type == 'prepayments'){
        // dd($post_type.' = prepayments');
        $prepayment = new App\Http\Controllers\Xero\Models\Prepayment($apiInstance, $xeroTenantId);
        $prepayment->create();
    }elseif($post_type == 'invoices'){
        $invoice = new App\Http\Controllers\Xero\Models\Invoice($apiInstance, $xeroTenantId, $session, $training_option_id);
        $invoice->create();
    }else{
        dd('Something Error, return to API developer!');
    }
// ++++++++++++++++++++ NEW +++++++++++++++++++++
