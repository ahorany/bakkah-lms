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

$message = "no API calls";
try {
    $contact = new XeroAPI\XeroPHP\Models\Accounting\Contact;
    $contact->setName('Ahorany')
        ->setFirstName("AbedAllah")
        ->setLastName("Elhorany")
        ->setEmailAddress("abed_348@hotmail.com.com");
    // $contact->setContactId('0a47c03d-a1af-40c5-af9e-6e7a12c1ac4e');
    // ->setContactPersons($arr_persons);

    $arr_contacts = [];
    array_push($arr_contacts, $contact);
    $contacts = new XeroAPI\XeroPHP\Models\Accounting\Contacts;
    $contacts->setContacts($arr_contacts);
    $apiResponse = $apiInstance->updateOrCreateContacts($xeroTenantId,$contacts);
    // $message = 'New Contact Name: ' . $apiResponse->getContacts()[0]->getContactId();

    /*$trackingCategories = new XeroAPI\XeroPHP\Models\Accounting\TrackingCategories;
    $trackingCategories->setTrackingCategories($arr_trackings);
    // return;
    $apiResponse1 = $apiInstance->getTrackingCategories($xeroTenantId);
    echo '<pre>';
    var_dump($apiResponse1);
    echo '</pre>';*/

    $arr_lineItemTrackings = [];
    $lineItemTracking = new XeroAPI\XeroPHP\Models\Accounting\LineItemTracking;
    $lineItemTracking->setName('Region')
        ->setOption('Eastside');
    array_push($arr_lineItemTrackings, $lineItemTracking);

    $lineItemTracking = new XeroAPI\XeroPHP\Models\Accounting\LineItemTracking;
    $lineItemTracking->setName('Partnership')
        ->setOption('first test');
    array_push($arr_lineItemTrackings, $lineItemTracking);

    $lineItem = new XeroAPI\XeroPHP\Models\Accounting\LineItem;
    $lineItem->setDescription('Consulting services as agreed (20% off standard rate)')
        ->setItemCode('BOOK')
        ->setQuantity(1.00)
        ->setAccountCode('200')
        ->setUnitAmount(1000)
        ->setDiscountRate(23.9)
        // ->setTaxType('TAX003')//We need tax id from tax settings
        ->setTracking($arr_lineItemTrackings)
    ;
    $arr_lineItem = [];
    array_push($arr_lineItem, $lineItem);

    $invoice = new XeroAPI\XeroPHP\Models\Accounting\Invoice;
    $invoice->setContact($contact)
        // ->setInvoiceNumber('222222222')
        ->setLineAmountTypes('Exclusive')
        ->setType("ACCREC")
        ->setDate('2020-10-15')
        ->setDueDate('2020-10-15')
        ->setReference('R.(2020-10-15)-HyperPay-SID.: (615)')
        ->setStatus('AUTHORISED')
        // ->setSubTotal(1000.00)
        // ->setTotal(1000.00)
        ->setAmountPaid(875.15)
        // ->setStatus('PAID')

        ->setLineItems($arr_lineItem);

    $arr_invoices = [];
    array_push($arr_invoices, $invoice);
    $invoices = new XeroAPI\XeroPHP\Models\Accounting\Invoices;
    $invoices->setInvoices($arr_invoices);

    $apiResponse = $apiInstance->createInvoices($xeroTenantId,$invoices,true, 2);

} catch (\XeroAPI\XeroPHP\ApiException $e) {
    $error = AccountingObjectSerializer::deserialize(
        $e->getResponseBody(),
        '\XeroAPI\XeroPHP\Models\Accounting\Error',
        []
    );
    var_dump($error);
    $message = "ApiException - " . $error->getElements()[0]["validation_errors"][0]["message"];
}
