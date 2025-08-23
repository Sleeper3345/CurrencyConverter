<?php

namespace common\components;

use common\components\clients\FreeCurrencyClient;

class FreeCurrencyComponent
{
    public string $url;
    public string $apiKey;

    /**
     * @return FreeCurrencyClient
     */
    public function getClient(): FreeCurrencyClient
    {
        return new FreeCurrencyClient($this->url, $this->apiKey);
    }
}
