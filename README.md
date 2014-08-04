# ServerPilot API PHP Wrapper

```
**In Development. Feel free to write some code and submit a pull request.**
```

## Create ServerPilot Client
```php
include_once 'ServerPilot-API-PHP-Wrapper/Client.php';
$ServerPilot = new \ServerPilotAPI\Client('CLIENT_ID', 'API_KEY');
```
## Resources

### Servers

#### List all servers
```php
$servers = $ServerPilot->Servers()->listAll();
```

### System Users

#### List all sysusers
```php
$sysusers = $ServerPilot->SystemUsers()->listAll($server_id=null);
```
#### Create a sysuser
```php
$app = $ServerPilot->SystemUsers()->create($serverid, $username, $password);
```

### Apps

#### List all apps
```php
$apps = $ServerPilot->Apps()->listAll($server_id=null);
```
#### Create an app
```php
$app = $ServerPilot->Apps()->create($name, $sysuser_id, $runtime='php5.4', $domains=array());
```

### Databases

#### List all databases
```php
$databases = $ServerPilot->Databases()->listAll($server_id=null, $app_id=null);
```

#### Create a database
```php
$database = $ServerPilot->Databases()->create($app_id, $name, $username, $password);
```

### Actions

#### Get action status
```php
$action = $ServerPilot->Actions()->getStatus($action_id);
```