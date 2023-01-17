<?php

namespace Bankopen\OpenMicroserviceSdk\Auditing;

use Bankopen\OpenMicroserviceSdk\Utility\RemoteRequest;

/**
 * Class AuditService
 *
 * @author Ankit Tiwari <ankit.tiwari@bankopen.co>
 */
class AuditService
{
    /**
     * https://github.com/Architectural-Documents/audit-log/blob/main/000-audit-log-service.md#data-definition-m---mandatory-o---optional
     * @var array
     */
    private array $paramKeys = [
        'product', 'event_source', 'event_type', 'event_action', 'event_data', 'event_created_at'
    ];
    /**
     * @var array
     */
    private array $headerKeys = ['Authorization'];

    /**
     * @param $url
     * @param $parameters
     * @param $headers
     * @return mixed
     * @throws \Exception
     */
    public function createAudit($url, $parameters, $headers, $connectionTimeout = 10, $timeout = 10): mixed
    {
        $this->validateKeys($this->paramKeys, $parameters);
        $this->validateKeys($this->headerKeys, $headers);

        $remote = (new RemoteRequest($url, json_encode($parameters), $headers));
        $remote->setHeaders($headers);
        $remote->setTimeout($timeout);
        $remote->setConnectTimeout($connectionTimeout);

        return $remote->post();
    }

    /**
     * @param $which
     * @param $keys
     * @throws \Exception
     */
    private function validateKeys($which, $keys)
    {
        $keysProvided = array_keys($keys);

        foreach ($which as $key) {
            if ( !in_array($key, $keysProvided)) {
                throw new \Exception($key . ' is missing');
            }
        }
    }
}
