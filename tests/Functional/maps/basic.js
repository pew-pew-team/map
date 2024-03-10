(function (name, data) {
    if (typeof onTileMapLoaded === 'undefined') {
        if (typeof TileMaps === 'undefined') TileMaps = {};
        TileMaps[name] = data;
    } else {
        onTileMapLoaded(name, data);
    }
    if (typeof module === 'object' && module && module.exports) {
        module.exports = data;
    }
})("basic",
    {
        "compressionlevel": -1,
        "height": 4,
        "infinite": false,
        "layers": [
            {
                "data": [1, 0, 2, 0,
                    0, 3, 0, 4,
                    2, 0, 1, 0,
                    0, 4, 0, 3],
                "height": 4,
                "id": 1,
                "name": "tiles",
                "opacity": 1,
                "type": "tilelayer",
                "visible": true,
                "width": 4,
                "x": 0,
                "y": 0
            }],
        "nextlayerid": 2,
        "nextobjectid": 1,
        "orientation": "orthogonal",
        "renderorder": "right-down",
        "tiledversion": "1.10.2",
        "tileheight": 32,
        "tilesets": [
            {
                "columns": 2,
                "firstgid": 1,
                "image": "example.png",
                "imageheight": 64,
                "imagewidth": 64,
                "margin": 0,
                "name": "included",
                "spacing": 0,
                "tilecount": 4,
                "tileheight": 32,
                "tilewidth": 32
            }],
        "tilewidth": 32,
        "type": "map",
        "version": "1.10",
        "width": 4
    });
