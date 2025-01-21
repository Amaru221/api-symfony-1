<?php

namespace App\Factory;

use App\Entity\DragonTreasure;
use App\Repository\DragonTreasureRepository;
use Doctrine\ORM\EntityRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<DragonTreasure>
 *
 * @method        DragonTreasure|Proxy create(array|callable $attributes = [])
 * @method static DragonTreasure|Proxy createOne(array $attributes = [])
 * @method static DragonTreasure|Proxy find(object|array|mixed $criteria)
 * @method static DragonTreasure|Proxy findOrCreate(array $attributes)
 * @method static DragonTreasure|Proxy first(string $sortedField = 'id')
 * @method static DragonTreasure|Proxy last(string $sortedField = 'id')
 * @method static DragonTreasure|Proxy random(array $attributes = [])
 * @method static DragonTreasure|Proxy randomOrCreate(array $attributes = [])
 * @method static DragonTreasureRepository|ProxyRepositoryDecorator repository()
 * @method static DragonTreasure[]|Proxy[] all()
 * @method static DragonTreasure[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static DragonTreasure[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static DragonTreasure[]|Proxy[] findBy(array $attributes)
 * @method static DragonTreasure[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static DragonTreasure[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class DragonTreasureFactory extends PersistentProxyObjectFactory{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return DragonTreasure::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'coolFactor' => self::faker()->randomNumber(),
            'description' => self::faker()->text(),
            'isPublished' => self::faker()->boolean(),
            'name' => self::faker()->text(255),
            'plunderedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'value' => self::faker()->randomNumber(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(DragonTreasure $dragonTreasure): void {})
        ;
    }
}
