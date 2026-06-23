<?php

declare(strict_types=1);

namespace custanator\ATOL\Response;

use stdClass;

/**
 * Interface ResponseInterface.
 *
 * @package custanator\ATOL\Response
 */
interface ResponseInterface
{
    /**
     * ResponseInterface constructor.
     *
     * @param stdClass $json
     */
    public function __construct(stdClass $json);
}
