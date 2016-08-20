<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 18.08.16 at 12:50
 */
namespace samsonframework\containerxml\tests\classes;

class Road
{
    /** @var CarService */
    protected $carService;

    /**
     * Road constructor.
     *
     * @param CarService $carService
     */
    public function __construct(CarService $carService)
    {
        $this->carService = $carService;
    }
}
