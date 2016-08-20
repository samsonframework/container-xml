<?php
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 06.08.16 at 11:11
 */
namespace samsonframework\containerxml\tests\classes;samsonframework/container-xml

class Car
{
    /** @var DriverInterface */
    protected $driver;

    /**
     * Car constructor.
     *
     * @param DriverInterface $driver
     */
    public function __construct(DriverInterface $driver)
    {

    }
}
