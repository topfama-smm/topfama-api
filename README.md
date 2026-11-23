# Top Fama SMM Panel API

Lightweight API clients for the [Top Fama SMM panel](https://topfama.com/smm-panel) in **PHP**, **Python** and **Node.js**, with no dependencies. Use them to connect your reseller website, bot or child panel: list services, create orders, check status, request refills and see your balance.

- API URL: `https://topfama.com/api/v2` (POST, JSON response)
- Get your API key in your account after [signing up](https://topfama.com/en/cadastro) (free, no monthly fee)
- Full docs: [SMM panel API guide](https://topfama.com/smm-panel-api)

Leer en español: [API de panel SMM](https://topfama.com/api-panel-smm) · Ler em português: [API de painel SMM](https://topfama.com/blog/api-de-painel-smm)

## Quick start

### Python

```python
from topfama import TopFama

api = TopFama("YOUR_API_KEY")
print(api.balance())                       # {"balance": "100.84", "currency": "USD"}
services = api.services()                  # list of services with rate per 1,000
order = api.add_order(1, "https://instagram.com/profile", 1000)
print(api.status(order["order"]))          # charge, start_count, status, remains
```

### PHP

```php
require 'php/TopFama.php';

$api = new TopFama('YOUR_API_KEY');
$order = $api->addOrder(1, 'https://instagram.com/profile', 1000);
print_r($api->status($order['order']));
```

### Node.js

```js
const { TopFama } = require('./node/topfama');

const api = new TopFama(process.env.TOPFAMA_API_KEY);
const order = await api.addOrder(1, 'https://instagram.com/profile', 1000);
console.log(await api.status(order.order));
```

## Methods

| Method | API action | Notes |
| --- | --- | --- |
| `services()` | `services` | ID, name, category, rate per 1,000, min, max, refill, cancel |
| `add_order(service, link, quantity, ...)` | `add` | Extra fields: `runs`, `interval` (drip-feed), `comments`, `username`, `answer_number` |
| `status(order)` | `status` | One order |
| `multi_status(orders)` | `status` | Up to 100 IDs |
| `refill(order)` / `multi_refill(orders)` | `refill` | Refill services only (R30, R365) |
| `refill_status(refill)` | `refill_status` | |
| `cancel(orders)` | `cancel` | Up to 100 IDs |
| `balance()` | `balance` | |

Order statuses: Pending, In progress, Completed, Partial, Processing, Canceled.

## Good practices

- Keep your API key on the server. Never put it in front-end code or a public repository.
- Read `services()` once and cache it: rates and limits change, but not every minute.
- Respect each service's `min` and `max`, and send only public links. The panel never needs a password.

## About Top Fama

Top Fama is an SMM panel from Brazil for resellers: 178 services across 11 networks (Instagram, TikTok, YouTube, Kwai, Facebook, Telegram and more), 38 of them with a Brazilian audience, wholesale prices per 1,000 and crypto payment (USDT TRC20, Binance Pay, Cryptomus). Support in English, Spanish and Portuguese.

- [SMM panel](https://topfama.com/smm-panel) · [SMM reseller panel](https://topfama.com/smm-reseller-panel) · [Panel SMM en español](https://topfama.com/panel-smm) · [Painel SMM em português](https://topfama.com/)

## License

MIT
