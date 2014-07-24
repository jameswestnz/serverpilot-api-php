# ServerPilot API PHP Wrapper

```php
include_once 'ServerPilot-API-PHP-Wrapper/Client.php';
$client = new \ServerPilotAPI\Client('CLIENT_ID', 'API_KEY');
$server_list = $client->Servers()->listAll();
```