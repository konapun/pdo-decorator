# PDO Decorator
A decorator interface for PDO and some basic decorators

```php
use PDO\Decorator\TimedQueryDecorator as TimedDecorator;

$showTime = true;
$basePDO = new PDO($dsn, $username, $passwd);
$timedPDO = new TimedDecorator($basePDO, $showTime);

// From here, use $timedPDO just as you'd use $basePDO
```
## Creating decorators
A single decorator, TimedQueryDecorator, is included which both gives execution
time for decorated queries and also serves as an example for creating your own
PDO decorators
