// Top Fama SMM panel API client (Node 18+, no dependencies).
// Docs: https://topfama.com/smm-panel-api
'use strict';

const API_URL = 'https://topfama.com/api/v2';

class TopFama {
  constructor(apiKey, apiUrl = API_URL) {
    this.apiKey = apiKey;
    this.apiUrl = apiUrl;
  }

  async call(action, params = {}) {
    const body = new URLSearchParams({ key: this.apiKey, action });
    for (const [k, v] of Object.entries(params)) if (v !== undefined && v !== null) body.append(k, String(v));
    const res = await fetch(this.apiUrl, { method: 'POST', body, headers: { 'User-Agent': 'topfama-api-node/1.0' } });
    const result = await res.json();
    if (result && !Array.isArray(result) && result.error) throw new Error(result.error);
    return result;
  }

  /** List all services: id, name, category, rate per 1000, min, max, refill, cancel. */
  services() { return this.call('services'); }

  /** Create an order. extra: runs, interval, comments, username, answer_number. */
  addOrder(service, link, quantity, extra = {}) { return this.call('add', { service, link, quantity, ...extra }); }

  status(order) { return this.call('status', { order }); }

  /** Up to 100 order IDs. */
  multiStatus(orders) { return this.call('status', { orders: orders.join(',') }); }

  refill(order) { return this.call('refill', { order }); }

  multiRefill(orders) { return this.call('refill', { orders: orders.join(',') }); }

  refillStatus(refill) { return this.call('refill_status', { refill }); }

  cancel(orders) { return this.call('cancel', { orders: orders.join(',') }); }

  balance() { return this.call('balance'); }
}

module.exports = { TopFama };
