<?php

namespace LilleBitte\Container\Loader;

use LilleBitte\Container\ContainerBuilder;
use LilleBitte\Container\Reference;
use LilleBitte\Container\Exception\ContainerException;
use Symfony\Component\Yaml\Yaml;

use function count;
use function is_string;
use function strpos;
use function explode;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class YamlFileStrategy extends AbstractLoaderStrategy
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
		$config = Yaml::parseFile(
			sprintf(
				"%s/%s",
				$this->resolveAbsolutePathname(),
				$file
			)
		);

		$container = $this->getContainer();

		foreach ($config as $key => $conf) {
			$params = !isset($conf['parameters']) ? [] : $conf['parameters'];

			foreach ($params as $k => $q) {
				if (is_string($q) && false !== strpos($q, '@ref')) {
					$spl = explode(':', $q);

					if (!isset($spl[1]) && count($spl) !== 2) {
						throw new ContainerException(
							"Reference value must be '@ref:<reference_id>' in YAML config."
						);
					}

					$params[$k] = new Reference($spl[1]);
				}
			}

			$container->register($key, $conf['class'])
				->setArguments($params);
		}
	}
}
