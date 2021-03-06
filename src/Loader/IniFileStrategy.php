<?php

namespace LilleBitte\Container\Loader;

use LilleBitte\Container\ContainerBuilder;
use LilleBitte\Container\Reference;
use LilleBitte\Container\Exception\ContainerException;

use function count;
use function explode;
use function parse_ini_file;
use function ltrim;
use function rtrim;
use function strlen;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class IniFileStrategy extends AbstractLoaderStrategy
{
    public function __construct(ContainerBuilder $container, string $dir)
    {
        $this->setContainer($container);
        $this->setConfigDir($dir);
    }

    /**
     * {@inheritdoc}
     */
    public function deserialize(string $file)
    {
        $config = parse_ini_file(
            sprintf(
                "%s/%s",
                $this->resolveAbsolutePathname(),
                $file
            ),
            true,
            \INI_SCANNER_TYPED
        );

        $container = $this->getContainer();

        foreach ($config as $key => $conf) {
            $autowired = !isset($conf['autowire'])
                ? false
                : $conf['autowire'];

            if ($autowired) {
                $container->autowire($key, $conf['class']);
                continue;
            }

            $params = !isset($conf['parameters'])
                ? []
                : $this->parseArray($conf['parameters']);

            foreach ($params as $k => $q) {
                if (is_string($q) && false !== strpos($q, '@ref')) {
                    $spl = explode(':', $q);

                    if (!isset($spl[1]) && count($spl) !== 2) {
                        throw new ContainerException(
                            "Reference value must be '@ref:<reference_id>' in INI config."
                        );
                    }

                    $params[$k] = new Reference($spl[1]);
                }
            }

            $container->register($key, $conf['class'])
                ->setArguments($params);
        }
    }

    /**
     * Deserializing '[...]' to actual array.
     *
     * @param string $buf String to parse.
     * @return array
     */
    private function parseArray(string $buf)
    {
        $buf = explode(
            ', ',
            ltrim(rtrim($buf, ']'), '[')
        );

        foreach ($buf as $key => $value) {
            if (($value[0] === "'" && $value[strlen($value) - 1] === "'") ||
                ($value[0] === '"' && $value[strlen($value) - 1] === '"')) {
                $buf[$key] = ltrim(rtrim($value, "'\""), "'\"");
                continue;
            }

            if (ctype_digit($value)) {
                $buf[$key] = intval($value);
                continue;
            }
        }

        return $buf;
    }
}
