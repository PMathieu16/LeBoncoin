<?php

namespace App\Factory;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Ad>
 *
 * @method static Ad|Proxy createOne(array $attributes = [])
 * @method static Ad[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Ad|Proxy find(object|array|mixed $criteria)
 * @method static Ad|Proxy findOrCreate(array $attributes)
 * @method static Ad|Proxy first(string $sortedField = 'id')
 * @method static Ad|Proxy last(string $sortedField = 'id')
 * @method static Ad|Proxy random(array $attributes = [])
 * @method static Ad|Proxy randomOrCreate(array $attributes = [])
 * @method static Ad[]|Proxy[] all()
 * @method static Ad[]|Proxy[] findBy(array $attributes)
 * @method static Ad[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Ad[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static AdRepository|RepositoryProxy repository()
 * @method Ad|Proxy create(array|callable $attributes = [])
 */
final class AdFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'title' => self::faker()->text(20),
            'description' => self::faker()->text(100),
            'price' => self::faker()->randomFloat(100, 10),
//            'image' => "https://www.yateo.com/blog/wp-content/uploads/2020/03/symfony.jpg",
            'user' => UserFactory::random(),
            'tags' => TagFactory::randomRange(0, 5)
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Ad $ad): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Ad::class;
    }
}
