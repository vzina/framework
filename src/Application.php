<?php
/**
 * Application.php
 * PHP version 7
 *
 * @package open-ef
 * @author  weijian.ye
 * @contact yeweijian@eyugame.com
 * @link    https://github.com/vzina
 */
declare (strict_types=1);

namespace OpenEf\Framework;

use App\Entity\Foo;
use OpenEf\Framework\Contract\ApplicationInterface;
use OpenEf\Framework\Contract\ConfigInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Application implements ApplicationInterface
{

    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        echo 'start', PHP_EOL;

        $container = ApplicationContext::getContainer();
        $b = $container->get(Foo::class);
        var_dump($b);

        // var_dump(ApplicationContext::getContainer()->get(ConfigInterface::class));
    }
}
