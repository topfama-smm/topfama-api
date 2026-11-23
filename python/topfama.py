"""Top Fama SMM panel API client (Python 3, standard library only).

Docs: https://topfama.com/smm-panel-api
"""
import json
import urllib.parse
import urllib.request

API_URL = "https://topfama.com/api/v2"


class TopFamaError(Exception):
    pass


class TopFama:
    def __init__(self, api_key, api_url=API_URL, timeout=30):
        self.api_key = api_key
        self.api_url = api_url
        self.timeout = timeout

    def _call(self, action, **params):
        data = {"key": self.api_key, "action": action}
        data.update({k: v for k, v in params.items() if v is not None})
        body = urllib.parse.urlencode(data).encode()
        req = urllib.request.Request(self.api_url, data=body, headers={"User-Agent": "topfama-api-python/1.0"})
        with urllib.request.urlopen(req, timeout=self.timeout) as resp:
            result = json.loads(resp.read().decode())
        if isinstance(result, dict) and "error" in result:
            raise TopFamaError(result["error"])
        return result

    def services(self):
        """List all services: id, name, category, rate per 1000, min, max, refill, cancel."""
        return self._call("services")

    def add_order(self, service, link, quantity=None, **extra):
        """Create an order. extra: runs, interval, comments, username, answer_number."""
        return self._call("add", service=service, link=link, quantity=quantity, **extra)

    def status(self, order):
        return self._call("status", order=order)

    def multi_status(self, orders):
        """Up to 100 order IDs."""
        return self._call("status", orders=",".join(str(o) for o in orders))

    def refill(self, order):
        return self._call("refill", order=order)

    def multi_refill(self, orders):
        return self._call("refill", orders=",".join(str(o) for o in orders))

    def refill_status(self, refill):
        return self._call("refill_status", refill=refill)

    def cancel(self, orders):
        return self._call("cancel", orders=",".join(str(o) for o in orders))

    def balance(self):
        return self._call("balance")


if __name__ == "__main__":
    import os

    api = TopFama(os.environ["TOPFAMA_API_KEY"])
    print(api.balance())
    for s in api.services()[:5]:
        print(s["service"], s["name"], s["rate"])
