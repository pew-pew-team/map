<p align="center">
    <a href="https://github.com/pew-pew-team"><img src="https://avatars.githubusercontent.com/u/161106276?s=128&v=4"/></a>
</p>

<p align="center">
    <a href="https://packagist.org/packages/pew-pew/map"><img src="https://poser.pugx.org/pew-pew/map/require/php?style=for-the-badge" alt="PHP 8.3+"></a>
    <a href="https://packagist.org/packages/pew-pew/map"><img src="https://poser.pugx.org/pew-pew/map/version?style=for-the-badge" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/pew-pew/map"><img src="https://poser.pugx.org/pew-pew/map/v/unstable?style=for-the-badge" alt="Latest Unstable Version"></a>
    <a href="https://raw.githubusercontent.com/pew-pew-team/map/blob/master/LICENSE"><img src="https://poser.pugx.org/pew-pew/map/license?style=for-the-badge" alt="License MIT"></a>
</p>
<p align="center">
    <a href="https://github.com/pew-pew-team/map/actions"><img src="https://github.com/pew-pew-team/map/workflows/tests/badge.svg"></a>
    <a href="https://github.com/pew-pew-team/map/actions"><img src="https://github.com/pew-pew-team/map/workflows/codestyle/badge.svg"></a>
    <a href="https://github.com/pew-pew-team/map/actions"><img src="https://github.com/pew-pew-team/map/workflows/security/badge.svg"></a>
    <a href="https://github.com/pew-pew-team/map/actions"><img src="https://github.com/pew-pew-team/map/workflows/static-analysis/badge.svg"></a>
</p>

# Map

This component provides basic DTOs for storing, analyzing and 
transmitting game maps data, as well as loaders for 
loading this data from arbitrary formats.

## Installation

PewPew Map is available as Composer repository and can be installed using the 
following command in a root of your project:

```bash
$ composer require pew-pew/map
```

More detailed installation [instructions are here](https://getcomposer.org/doc/01-basic-usage.md).

## Usage

Below is a list of available component value objects, their 
parameters and methods.

### Size

The size object is responsible for the physical two-dimensional dimensions 
of arbitrary objects.

```php
$size = new \PewPew\Map\Data\Size(
    width: 100, // optional, default = 1
    height: 50, // optional, default = 1
);
```

**Size Area**

Get the total area of an object provided by size.

```php
$size = new \PewPew\Map\Data\Size(100, 50);

echo $size->getArea(); // 5000
```

**Position By ID**

In particular, any 3x3 object can be represented in the following form, where 
each number denotes the corresponding identifier:

```
┌─────┬─────┬─────┐   ┌─────┬─────┬─────┐
│ 0x0 │ 1x0 │ 2x0 │   │  0  │  2  │  3  │
├─────┼─────┼─────┤   ├─────┼─────┼─────┤
│ 0x1 │ 1x1 │ 2x1 │ → │  4  │  5  │  6  │
├─────┼─────┼─────┤   ├─────┼─────┼─────┤
│ 0x2 │ 1x2 │ 2x2 │   │  7  │  8  │  9  │
└─────┴─────┴─────┘   └─────┴─────┴─────┘
```

Any coordinates can be represented as a scalar ID. To obtain a position by this
ID, you can use the following methods.

```php
$size = new \PewPew\Map\Data\Size(3, 3);

echo $size->getX(id: 1); // X = 1
echo $size->getY(id: 1); // Y = 0

echo $size->getPosition(id: 1); // object<Position> { x: 1, y: 0 }
```

### Position

A position is a primitive object that provides coordinates within an object.

The object does not provide any additional methods.

```php
$position = new \PewPew\Map\Data\Position(
    x: 1, // optional, default = 0
    y: 0, // optional, default = 0
);
```

### Layers

A layer is one of the map elements that can define a set of objects to draw, 
a set of collisions, a set of triggers, or anything else.

Each layer contains a position within the map and its own size.

```php
$layer = new \PewPew\Map\Data\Layer(
    // optional, default = { width: 1, height: 1 }
    size: new \PewPew\Map\Data\Size(
        width: 3,
        height: 3,
    ),
    // optional, default = { x: 0, y: 0 }
    position: new \PewPew\Map\Data\Position(
        x: 0, 
        y: 0,
    ),
);
```

**Tiles Layer**

For a layer containing tiles, you should create a corresponding
`PewPew\Map\Data\TilesLayer` object. In addition to `size` and `position`, 
it also contains an array of tile IDs.

The size of the tiles array directly depends on the size of the layer and can
be obtained using the `Size::getArea()` method.

> [!NOTE]
> For the 3x3 layer, the number of used tile elements corresponds to 9. 
> If there are extra tiles, they are not used.

> [!NOTE]
> If any tile ID is missing, then it corresponds to 0, that means no tile.

```php
$layer = new \PewPew\Map\Data\TilesLayer(
    tiles: [
        0, 2, 1,
        1, 2, 1,
        0, 0, 1,
    ],
    size: new \PewPew\Map\Data\Size(3, 3),
);
```

### TileSet

A tile set is an object containing information about an image containing a set 
of other images (tiles).

```php
$tileSet = new \PewPew\Map\Data\TileSet(
    // required
    pathname: __DIR__ . '/tiles.png',
    // optional, default = 1
    tileIdStartsAt: 1,
    // optional, default = { width: 1, height: 1 }
    size: new \PewPew\Map\Data\Size(
        width: 3,
        height: 3,
    ),
);
```

The `tileIdStartsAt` constructor parameter is responsible for the starting
ID of the tile, so a 2x2 tile set with `tileIdStartsAt: 42` will contains
`42`, `43`, `44` and `45` tile IDs.

> [!WARNING]
> The "tileIdStartsAt" cannot be less than 1.

**Tile ID Availability**

To check the availability of a tile ID, you can use 
the `containsId()` method.

```php
$set = new \PewPew\Map\Data\TileSet(
    pathname: ...,
    tileIdStartsAt: 1,
    size: new \PewPew\Map\Data\Size(1, 1),
);

$set->containsId(tileId: 0); // bool(false)
$set->containsId(tileId: 1); // bool(true)
$set->containsId(tileId: 2); // bool(false)
```

**Position Of A Tile**

```php
$set = new \PewPew\Map\Data\TileSet( ... );

$set->getX(tileId: 1);          // X = 0
$set->getY(tileId: 1);          // Y = 0

$set->getPosition(tileId: 1);   // object<Position> { x: 0, y: 0 }
```

**Updating Tile Pathname**

```php
$previous = new \PewPew\Map\Data\TileSet(
    pathname: __DIR__ . '/tiles-1.png',
);

$new = $previous->withPathname(
    pathname: __DIR__ . '/tiles-2.png',
);

echo $previous->pathname; // string(".../tiles-1.png")
echo $new->pathname; // string(".../tiles-2.png")
```
