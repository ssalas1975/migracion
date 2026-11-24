<?php
/*
 * This file is part of the Vocento Software.
 *
 * (c) Vocento S.A., <desarrollo.dts@vocento.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Vocento\Infrastructure\Client\Http\Exceptions;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ObjectNotFoundException.
 */
final class ObjectNotFoundException extends UnexpectedServiceResponseException
{
    /**
     * ObjectNotFoundException constructor.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param \Exception|null   $previous
     */
    public function __construct(RequestInterface $request, ResponseInterface $response, \Exception $previous = null)
    {
        parent::__construct('Object not found', $request, $response, $previous);
    }
}
