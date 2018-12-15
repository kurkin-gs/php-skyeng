<?php

/*
  1. Нет смысла наследовать класс DataProvider, нужно передавать экземпляр этого класса в конструктор DecoratorManager, сответственно сам класс DecoratorManager можно назвать более осмысленно: dataManager.
  2. Данные для соединения указывать только в одном классе DataProvider.
  3. Метод setLogger можно убрать и передавать логгер через коснтруктор.
  4. В методе getCacheKey лучше возвращать не сам ключ в формате json а только его хеш.
  5. После установки кеша нужно его сохранить.
  6. Свойства $dataProvider, $cache и $logger нет смысла делать публичными, нужно установить их область видимости либо private либо protected, в данном случае private.
  7. inheritdoc не имеет смысла, так как метод getResponse не существует в DataProvider.
 */

namespace src;

use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;

class DataManager {

    /**
     * @var DataProviderInterface
     */
    private $dataProvider;

    /**
     * @var CacheItemPoolInterface
     */
    private $cache;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param DataProviderInterface $dataProvider
     * @param CacheItemPoolInterface $cache
     * @param LoggerInterface $logger
     */
    public function __construct(DataProviderInterface $dataProvider, CacheItemPoolInterface $cache, LoggerInterface $logger) {
        $this->dataProvider = $dataProvider;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    /**
     * Returns response of dataProvider's request and stores data in cache
     * 
     * @param array $input
     * @return array
     * @throws Exception
     */
    public function getResponse(array $input): array {
        try {
            $cacheKey = $this->getCacheKey($input);
            $cacheItem = $this->cache->getItem($cacheKey);
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }

            $result = $this->dataProvider->get($input);
            $cacheItem
                    ->set($result)
                    ->expiresAt(
                            (new DateTime())->modify('+1 day')
            );
            $this->cache->save($cacheItem);

            return $result;
        } catch (Exception $e) {
            $this->logger->critical('Error');
        }

        return [];
    }

    /**
     * Returns hash of input array
     * 
     * @param array $input
     * @return string
     */
    public function getCacheKey(array $input) {
        return sha1(json_encode($input));
    }

}
