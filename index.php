<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Good Ass Coder</title>
    <script src="https://cdn.jsdelivr.net/npm/phaser@3.70.0/dist/phaser.min.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #0c0b0cff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
        }
        
        .title {
            color: #f0e6d2;
            text-align: center;
            margin-top: -190%;
            margin-left: 100%;
            text-shadow: 0 0 10px #ffd700, 2px 2px 0 #000;
            font-size: 2.5rem;
            letter-spacing: 3px;
        }
        .instructions {
            color: #c0c0c0;
            text-align: center;
            margin-top: 20%;
            font-size: 1rem;
            max-width: 320px;
            line-height: 1.4;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        .loading {
            color: #ffd700;
            font-size: 1.2rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title">ПЕРСОНАЖ</h1>
        <p class="instructions">Исследуй ПЛЮСОВЫЕ ХОЛМЫ.</p>
    </div>

    <script>
        class ZeldaScene extends Phaser.Scene {
            constructor() {
                super({ key: 'ZeldaScene' });
                this.marker = null;
                this.cursorKeys = null;
                this.mapData = null;
                this.tileSize = 32;
            }

            preload() {
                            
                // Загрузка текстур для тайлов
                this.load.image('grass', 'assets/grass.png');
                this.load.image('water', 'assets/water.png');
                this.load.image('mountain', 'assets/rock.png');
                this.load.image('sand', 'assets/sand.png');
                this.load.image('forest', 'assets/tree.png');
                this.load.image('path', 'assets/path.png');
                             
                // Отображение индикатора загрузки
                let loadingText = this.add.text(160, 140, 'Loading Hyrule...', { 
                    font: '16px Arial', 
                    fill: '#ffd700' 
                }).setOrigin(0.5);
                
                /*
                // Прогресс загрузки
                this.load.on('progress', function(value) {
                    loadingText.setText('Loading Hyrule: ' + Math.round(value * 100) + '%');
                });
                */
            }

            create() {
                // Определяем данные карты (0-трава, 1-вода, 2-горы, 3-песок, 4-лес, 5-дорога)
                this.mapData = [
                    [2, 0, 0, 4, 0, 4, 0, 0, 4, 0, 0, 4, 0, 0, 5, 5, 0, 4, 4, 2],
                    [2, 4, 0, 0, 0, 0, 0, 0, 4, 0, 0, 0, 0, 0, 5, 5, 4, 0, 4, 2],
                    [2, 0, 0, 0, 0, 4, 0, 4, 0, 0, 4, 4, 0, 5, 5, 5, 0, 0, 0, 2],
                    [2, 0, 0, 4, 0, 0, 0, 0, 0, 0, 0, 0, 5, 5, 5, 4, 0, 0, 4, 2],
                    [2, 0, 4, 0, 0, 0, 0, 0, 0, 5, 5, 5, 5, 5, 4, 0, 0, 0, 0, 2],
                    [2, 0, 0, 0, 0, 0, 5, 5, 5, 5, 4, 5, 5, 0, 0, 0, 0, 4, 0, 2],
                    [2, 3, 3, 3, 3, 5, 5, 4, 2, 4, 2, 4, 5, 5, 3, 3, 3, 3, 3, 2],
                    [1, 1, 1, 1, 3, 5, 4, 2, 2, 2, 2, 2, 4, 5, 3, 1, 1, 1, 1, 1],
                    [2, 3, 3, 3, 3, 5, 5, 4, 2, 4, 2, 4, 5, 5, 3, 3, 3, 3, 3, 2],
                    [2, 0, 0, 0, 0, 0, 5, 5, 5, 5, 4, 5, 5, 0, 0, 0, 0, 0, 0, 2],
                    [2, 4, 0, 0, 0, 4, 5, 0, 0, 5, 5, 5, 3, 3, 3, 3, 0, 0, 0, 2],
                    [2, 0, 0, 4, 0, 5, 5, 4, 0, 0, 0, 3, 3, 1, 3, 3, 3, 0, 0, 2],
                    [2, 0, 0, 0, 4, 5, 4, 0, 0, 0, 3, 3, 1, 1, 1, 3, 3, 0, 0, 2],
                    [2, 4, 0, 0, 0, 5, 0, 0, 4, 3, 3, 1, 1, 1, 1, 1, 3, 3, 4, 2],
                    [2, 2, 2, 2, 4, 5, 4, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2]
                ];

                // Отрисовываем карту с использованием загруженных текстур
                for (let y = 0; y < this.mapData.length; y++) {
                    for (let x = 0; x < this.mapData[y].length; x++) {
                        const tileType = this.mapData[y][x];
                        let textureKey = 'grass';
                        
                        switch (tileType) {
                            case 0: textureKey = 'grass'; break;
                            case 1: textureKey = 'water'; break;
                            case 2: textureKey = 'mountain'; break;
                            case 3: textureKey = 'sand'; break;
                            case 4: textureKey = 'forest'; break;
                            case 5: textureKey = 'path'; break;
                        }
                        
                        // Создаем тайл с загруженной текстурой
                        this.add.image(x * this.tileSize + this.tileSize/2, y * this.tileSize + this.tileSize/2, textureKey);
                    }
                }

                
                this.add.text(160, 20, 'ПЛЮСОВЫЕ ХОЛМЫ', {
                    font: '16px Arial',
                    fill: '#00aeffff',
                    stroke: '#eaf6ffff',
                    strokeThickness: 4,
                    shadow: {
                        offsetX: 2,
                        offsetY: 2,
                        color: '#000000ff',
                        blur: 0,
                        stroke: true
                    }
                }).setOrigin(0.5);

                // Настройка управления
                this.cursorKeys = this.input.keyboard.createCursorKeys();
            }

            update() {
                /*
                // Обработка перемещения маркера
                if (this.cursorKeys.left.isDown && this.marker.x > this.tileSize/2) {
                    this.marker.x -= this.tileSize;
                } else if (this.cursorKeys.right.isDown && this.marker.x < (20 * this.tileSize - this.tileSize/2)) {
                    this.marker.x += this.tileSize;
                }
                
                if (this.cursorKeys.up.isDown && this.marker.y > this.tileSize/2) {
                    this.marker.y -= this.tileSize;
                } else if (this.cursorKeys.down.isDown && this.marker.y < (15 * this.tileSize - this.tileSize/2)) {
                    this.marker.y += this.tileSize;
                }
                    */
            }
        }

        const config = {
            type: Phaser.AUTO,
            width: 640,
            height: 480,
            backgroundColor: '#000000',
            scene: ZeldaScene,
            scale: {
                mode: Phaser.Scale.FIT,
                autoCenter: Phaser.Scale.CENTER_BOTH
            },
            render: {
                pixelArt: false
            }
        };

        const game = new Phaser.Game(config);
    </script>
</body>
</html>