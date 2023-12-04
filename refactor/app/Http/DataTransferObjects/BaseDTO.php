<?php

namespace DTApi\Http\DataTransferObjects;

class BaseDTO
{
    /**
     * @param string $key
     * @param mixed|null $value
     * @return $this
     */
    protected function setAttribute(string $key, $value): self
    {
        $this->{$key} = $value;
        return $this;
    }
}