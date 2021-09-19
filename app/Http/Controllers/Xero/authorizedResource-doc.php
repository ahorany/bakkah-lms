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
  if (isset($_GET['action'])) {
    if ($_GET["action"] == 1) {
        // Get Organisation details
        $apiResponse = $apiInstance->getOrganisations($xeroTenantId);
        $message = 'Organisation Name: ' . $apiResponse->getOrganisations()[0]->getName();
    } else if ($_GET["action"] == 2) {
        // Create Contact
        try {
            $person = new XeroAPI\XeroPHP\Models\Accounting\ContactPerson;
            $person->setFirstName("John")
                ->setLastName("Smith")
                ->setEmailAddress("john.smith@24locks.com")
                ->setIncludeInEmails(true);

            $arr_persons = [];
            array_push($arr_persons, $person);

            $contact = new XeroAPI\XeroPHP\Models\Accounting\Contact;
            $contact->setName('FooBar')
                ->setFirstName("Foo")
                ->setLastName("Bar")
                ->setEmailAddress("ben.bowden@24locks.com")
                ->setContactPersons($arr_persons);

            $arr_contacts = [];
            array_push($arr_contacts, $contact);
            $contacts = new XeroAPI\XeroPHP\Models\Accounting\Contacts;
            $contacts->setContacts($arr_contacts);

            $apiResponse = $apiInstance->createContacts($xeroTenantId,$contacts);
            $message = 'New Contact Name: ' . $apiResponse->getContacts()[0]->getName();
        } catch (\XeroAPI\XeroPHP\ApiException $e) {
            $error = AccountingObjectSerializer::deserialize(
                $e->getResponseBody(),
                '\XeroAPI\XeroPHP\Models\Accounting\Error',
                []
            );
            $message = "ApiException - " . $error->getElements()[0]["validation_errors"][0]["message"];
        }

    } else if ($_GET["action"] == 3) {
        $if_modified_since = new \DateTime("2019-01-02T19:20:30+01:00"); // \DateTime | Only records created or modified since this timestamp will be returned
        $if_modified_since = null;
        $where = 'Type=="ACCREC"'; // string
        $where = null;
        $order = null; // string
        $ids = null; // string[] | Filter by a comma-separated list of Invoice Ids.
        $invoice_numbers = null; // string[] |  Filter by a comma-separated list of Invoice Numbers.
        $contact_ids = null; // string[] | Filter by a comma-separated list of ContactIDs.
        $statuses = array("DRAFT", "SUBMITTED");;
        $page = 1; // int | e.g. page=1 – Up to 100 invoices will be returned in a single API call with line items
        $include_archived = null; // bool | e.g. includeArchived=true - Contacts with a status of ARCHIVED will be included
        $created_by_my_app = null; // bool | When set to true you'll only retrieve Invoices created by your app
        $unitdp = null; // int | e.g. unitdp=4 – You can opt in to use four decimal places for unit amounts

        try {
            $apiResponse = $apiInstance->getInvoices($xeroTenantId, $if_modified_since, $where, $order, $ids, $invoice_numbers, $contact_ids, $statuses, $page, $include_archived, $created_by_my_app, $unitdp);
            if (  count($apiResponse->getInvoices()) > 0 ) {
                $message = 'Total invoices found: ' . count($apiResponse->getInvoices());
            } else {
                $message = "No invoices found matching filter criteria";
            }
        } catch (Exception $e) {
            echo 'Exception when calling AccountingApi->getInvoices: ', $e->getMessage(), PHP_EOL;
        }
    } else if ($_GET["action"] == 4) {
        // Create Multiple Contacts
        try {
            $contact = new XeroAPI\XeroPHP\Models\Accounting\Contact;
            $contact->setName('George Jetson')
                ->setFirstName("George")
                ->setLastName("Jetson")
                ->setEmailAddress("george.jetson@aol.com");

            // Add the same contact twice - the first one will succeed, but the
            // second contact will throw a validation error which we'll catch.
            $arr_contacts = [];
            array_push($arr_contacts, $contact);
            array_push($arr_contacts, $contact);
            $contacts = new XeroAPI\XeroPHP\Models\Accounting\Contacts;
            $contacts->setContacts($arr_contacts);

            $apiResponse = $apiInstance->createContacts($xeroTenantId,$contacts,false);
            $message = 'First contacts created: ' . $apiResponse->getContacts()[0]->getName();

            if ($apiResponse->getContacts()[1]->getHasValidationErrors()) {
                $message = $message . '<br> Second contact validation error : ' . $apiResponse->getContacts()[1]->getValidationErrors()[0]["message"];
            }

        } catch (\XeroAPI\XeroPHP\ApiException $e) {
            $error = AccountingObjectSerializer::deserialize(
                $e->getResponseBody(),
                '\XeroAPI\XeroPHP\Models\Accounting\Error',
                []
            );
            $message = "ApiException - " . $error->getElements()[0]["validation_errors"][0]["message"];
        }
    } else if ($_GET["action"] == 5) {

        $if_modified_since = new \DateTime("2019-01-02T19:20:30+01:00"); // \DateTime | Only records created or modified since this timestamp will be returned
        $where = null;
        $order = null; // string
        $ids = null; // string[] | Filter by a comma-separated list of Invoice Ids.
        $page = 1; // int | e.g. page=1 – Up to 100 invoices will be returned in a single API call with line items
        $include_archived = null; // bool | e.g. includeArchived=true - Contacts with a status of ARCHIVED will be included

        try {
            $apiResponse = $apiInstance->getContacts($xeroTenantId, $if_modified_since, $where, $order, $ids, $page, $include_archived);
            if (  count($apiResponse->getContacts()) > 0 ) {
                $message = 'Total contacts found: ' . count($apiResponse->getContacts());
            } else {
                $message = "No contacts found matching filter criteria";
            }
        } catch (Exception $e) {
            echo 'Exception when calling AccountingApi->getContacts: ', $e->getMessage(), PHP_EOL;
        }
    } else if ($_GET["action"] == 6) {

        $jwt = new XeroAPI\XeroPHP\JWTClaims();
        $jwt->setTokenId((string)$storage->getIdToken() );
        // Set access token in order to get authentication event id
        $jwt->setTokenAccess( (string)$storage->getAccessToken() );
        $jwt->decode();

        echo("sub:" . $jwt->getSub() . "<br>");
        echo("sid:" . $jwt->getSid() . "<br>");
        echo("iss:" . $jwt->getIss() . "<br>");
        echo("exp:" . $jwt->getExp() . "<br>");
        echo("given name:" . $jwt->getGivenName() . "<br>");
        echo("family name:" . $jwt->getFamilyName() . "<br>");
        echo("email:" . $jwt->getEmail() . "<br>");
        echo("user id:" . $jwt->getXeroUserId() . "<br>");
        echo("username:" . $jwt->getPreferredUsername() . "<br>");
        echo("session id:" . $jwt->getGlobalSessionId() . "<br>");
        echo("authentication_event_id:" . $jwt->getAuthenticationEventId() . "<br>");

    } else if ($_GET["action"] == 7) {
        // Create Contact
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
                ->setDate('2020-10-13')
                ->setDueDate('2020-10-13')
                ->setReference('R.(2020-10-13)-HyperPay-SID.: (612)')
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

    }
  }
?>
<html>
    <body>
        <ul>
            <li><a href="authorizedResource.php?action=1">Get Organisation Name</a></li>
            <li><a href="authorizedResource.php?action=2">Create one Contact</a></li>
            <li><a href="authorizedResource.php?action=3">Get Invoice with Filters</a></li>
            <li><a href="authorizedResource.php?action=4">Create multiple contacts and summarizeErrors</a></li>
            <li><a href="authorizedResource.php?action=5">Get Contact with Filters</a></li>
            <li><a href="authorizedResource.php?action=6">Get JWT Claims</a></li>
        </ul>
        <div>
        <?php
            echo($message );
        ?>
        </div>
    </body>
</html>
