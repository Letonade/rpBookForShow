<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';
    private const INTEGRATED_APP_NAME = 'front_twig';

    public function getProjectDir(): string
    {
        return \dirname(__DIR__);
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('../config/{packages}/*.yaml');
        $container->import('../config/{packages}/'.$this->environment.'/*.yaml');

        if (is_file(\dirname(__DIR__).'/config/services.yaml')) {
            $container->import('../config/services.yaml');
            $container->import('../config/{services}_'.$this->environment.'.yaml');
        } else {
            $container->import('../config/{services}.php');
        }
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $confDir = $this->getProjectDir().'/config';

        // $routes->import($confDir.'/{routes}/'.$this->environment.'/*'.self::CONFIG_EXTS, '/', 'glob');
        // $routes->import($confDir.'/{routes}/*'.self::CONFIG_EXTS, '/', 'glob');
        // $routes->import($confDir.'/{routes}'.self::CONFIG_EXTS, '/', 'glob');

        $routes->import($confDir.'/{routes}/'.$this->environment.'/*'.self::CONFIG_EXTS);
        $routes->import($confDir.'/{routes}/*'.self::CONFIG_EXTS);
        $routes->import($confDir.'/{routes}/'.self::INTEGRATED_APP_NAME.'/*'.self::CONFIG_EXTS);

        // if (is_file(\dirname(__DIR__).'/config/routes.yaml')) {
        //     $routes->import('../config/routes.yaml');
        // } else {
        //     $routes->import('../config/{routes}.php');
        // }
    }

    // protected function configureContainer(ContainerConfigurator $container): void
    // {
    //     $container->import('../config/{packages}/*.yaml');
    //     $container->import('../config/{packages}/'.$this->environment.'/*.yaml');
    //
    //     if (is_file(\dirname(__DIR__).'/config/services.yaml')) {
    //         $container->import('../config/services.yaml');
    //         $container->import('../config/{services}_'.$this->environment.'.yaml');
    //     } else {
    //         $container->import('../config/{services}.php');
    //     }
    // }
    //
    // protected function configureRoutes(RoutingConfigurator $routes): void
    // {
    //     $routes->import('../config/{routes}/'.$this->environment.'/*.yaml');
    //     $routes->import('../config/{routes}/*.yaml');
    //
    //     if (is_file(\dirname(__DIR__).'/config/routes.yaml')) {
    //         $routes->import('../config/routes.yaml');
    //     } else {
    //         $routes->import('../config/{routes}.php');
    //     }
    // }
}
