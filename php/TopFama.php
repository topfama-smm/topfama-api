<?php
/**
 * Top Fama SMM panel API client (PHP 7.4+, cURL).
 * Docs: https://topfama.com/smm-panel-api
 */
class TopFama
{
    private $apiKey;
    private $apiUrl;

    public function __construct(string $apiKey, string $apiUrl = 'https://topfama.com/api/v2')
    {
        $this->apiKey = $apiKey;
        $this->apiUrl = $apiUrl;
    }

    private function call(string $action, array $params = [])
    {
        $data = array_merge(['key' => $this->apiKey, 'action' => $action], array_filter($params, function ($v) {
            return $v !== null;
        }));
        $ch = curl_init($this->apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_USERAGENT => 'topfama-api-php/1.0',
        ]);
        $raw = curl_exec($ch);
        if ($raw === false) {
            throw new RuntimeException(curl_error($ch));
        }
        curl_close($ch);
        $result = json_decode($raw, true);
        if (is_array($result) && isset($result['error'])) {
            throw new RuntimeException($result['error']);
        }
        return $result;
    }

    /** List all services: id, name, category, rate per 1000, min, max, refill, cancel. */
    public function services() { return $this->call('services'); }

    /** Create an order. $extra: runs, interval, comments, username, answer_number. */
    public function addOrder(int $service, string $link, ?int $quantity = null, array $extra = [])
    {
        return $this->call('add', array_merge(['service' => $service, 'link' => $link, 'quantity' => $quantity], $extra));
    }

    public function status(int $order) { return $this->call('status', ['order' => $order]); }

    /** Up to 100 order IDs. */
    public function multiStatus(array $orders) { return $this->call('status', ['orders' => implode(',', $orders)]); }

    public function refill(int $order) { return $this->call('refill', ['order' => $order]); }

    public function multiRefill(array $orders) { return $this->call('refill', ['orders' => implode(',', $orders)]); }

    public function refillStatus(int $refill) { return $this->call('refill_status', ['refill' => $refill]); }

    public function cancel(array $orders) { return $this->call('cancel', ['orders' => implode(',', $orders)]); }

    public function balance() { return $this->call('balance'); }
}
