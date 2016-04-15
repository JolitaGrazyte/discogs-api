<?php

namespace Jolita\DiscogsApiWrapper;

use GuzzleHttp\Client;

class DiscogsOrders extends DiscogsApi
{
    public function __construct($token, $userAgent)
    {
        parent::__construct($token, $userAgent);
    }

    public function orderWithId(string $id)
    {
        return $this->get("marketplace/orders/{$id}", '', [], true);
    }

    public function orderMessages(string $orderId)
    {
        return $this->get("marketplace/orders/{$orderId}/messages", '', [], true);
    }

    public function getMyOrders(int $page = null, int $per_page = null, string $status = null, string $sort = null, string $sort_order = null)
    {
        $query = [
            'page' => $page ?: 1,
            'per_page' => $per_page ?: 50,
            'status' => $status ?: 'All',
            'sort' => $sort ?: 'id',
            'sort_order' => $sort_order ?: 'desc',
        ];

        return $this->get('marketplace/orders', '', $query, true);
    }

    public function changeOrderStatus(string $orderId, string $status)
    {
       return $this->postChanges($orderId, 'status', $status);
    }
    public function addShipping($orderId, string $shipping)
    {
       return $this->postChanges($orderId, 'shipping', $shipping);
    }

    protected function postChanges(string $orderId, string $key, string $value)
    {
        $resource = "marketplace/orders/";

        return (new Client())
            ->post($this->url($this->path($resource, $orderId)),
                ['query' => [
                        $key => $value,
                        'token' => $this->token()
                    ]
                ]
            );
    }
}
