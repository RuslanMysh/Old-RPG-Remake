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
                this.player = null;
                this.cursorKeys = null;
                this.mapData = null;
                this.tileSize = 32;
                this.collisionLayer = null;
                // Начальная позиция игрока (можно изменить)
                this.playerStartX = 3; // координата X в тайлах
                this.playerStartY = 3; // координата Y в тайлах
            }

            preload() {
                // Создаем текстуры программно
                //this.createTextures();

                this.load.image('grass', 'assets/grass.png');
                this.load.image('water', 'assets/water.png');
                this.load.image('mountain', 'assets/rock.png');
                this.load.image('sand', 'assets/sand.png');
                this.load.image('forest', 'assets/tree.png');
                this.load.image('path', 'assets/path.png');
            }

            createTextures() {
                const tileSize = this.tileSize;
                
                // Трава
                const grassTexture = this.textures.createCanvas('grass', tileSize, tileSize);
                const grassCtx = grassTexture.getContext();
                grassCtx.fillStyle = '#3a7d34';
                grassCtx.fillRect(0, 0, tileSize, tileSize);
                grassCtx.fillStyle = '#4a8d3c';
                for (let i = 0; i < tileSize; i += 4) {
                    for (let j = 0; j < tileSize; j += 4) {
                        if ((i + j) % 8 === 0) grassCtx.fillRect(i, j, 2, 2);
                    }
                }
                grassTexture.refresh();

                // Вода
                const waterTexture = this.textures.createCanvas('water', tileSize, tileSize);
                const waterCtx = waterTexture.getContext();
                waterCtx.fillStyle = '#2a5c8a';
                waterCtx.fillRect(0, 0, tileSize, tileSize);
                waterCtx.strokeStyle = '#3a7cba';
                waterCtx.lineWidth = 2;
                for (let i = -4; i < tileSize; i += 8) {
                    waterCtx.beginPath();
                    waterCtx.arc(i, tileSize/2, 3, 0, Math.PI);
                    waterCtx.stroke();
                }
                waterTexture.refresh();

                // Горы
                const mountainTexture = this.textures.createCanvas('mountain', tileSize, tileSize);
                const mountainCtx = mountainTexture.getContext();
                mountainCtx.fillStyle = '#6d6d6d';
                mountainCtx.fillRect(0, 0, tileSize, tileSize);
                mountainCtx.fillStyle = '#8d8d8d';
                mountainCtx.fillRect(4, 4, 6, 6);
                mountainCtx.fillRect(18, 10, 8, 8);
                mountainCtx.fillRect(10, 18, 7, 7);
                mountainTexture.refresh();

                // Песок
                const sandTexture = this.textures.createCanvas('sand', tileSize, tileSize);
                const sandCtx = sandTexture.getContext();
                sandCtx.fillStyle = '#d9c8a3';
                sandCtx.fillRect(0, 0, tileSize, tileSize);
                sandCtx.fillStyle = '#e9d8b3';
                for (let i = 0; i < 20; i++) {
                    sandCtx.fillRect(Phaser.Math.Between(0, tileSize-2), Phaser.Math.Between(0, tileSize-2), 2, 2);
                }
                sandTexture.refresh();

                // Лес
                const forestTexture = this.textures.createCanvas('forest', tileSize, tileSize);
                const forestCtx = forestTexture.getContext();
                forestCtx.fillStyle = '#3a7d34';
                forestCtx.fillRect(0, 0, tileSize, tileSize);
                forestCtx.fillStyle = '#4a3c1a';
                forestCtx.fillRect(12, 18, 4, 10);
                forestCtx.fillRect(24, 14, 3, 8);
                forestCtx.fillStyle = '#2d5a2d';
                forestCtx.beginPath();
                forestCtx.arc(14, 16, 6, 0, Math.PI * 2);
                forestCtx.fill();
                forestCtx.beginPath();
                forestCtx.arc(25, 12, 5, 0, Math.PI * 2);
                forestCtx.fill();
                forestTexture.refresh();

                // Дорога
                const pathTexture = this.textures.createCanvas('path', tileSize, tileSize);
                const pathCtx = pathTexture.getContext();
                pathCtx.fillStyle = '#a68a64';
                pathCtx.fillRect(0, 0, tileSize, tileSize);
                pathCtx.fillStyle = '#b69a74';
                for (let i = 0; i < 4; i++) {
                    for (let j = 0; j < 4; j++) {
                        if ((i + j) % 2 === 0) pathCtx.fillRect(i*8, j*8, 4, 4);
                    }
                }
                pathTexture.refresh();

                // Персонаж
                const playerTexture = this.textures.createCanvas('player', tileSize, tileSize);
                const playerCtx = playerTexture.getContext();
                playerCtx.fillStyle = '#006400';
                playerCtx.fillRect(8, 8, 16, 16);
                playerCtx.fillStyle = '#ffdbac';
                playerCtx.fillRect(12, 4, 8, 8);
                playerCtx.fillStyle = '#006400';
                playerCtx.fillRect(10, 2, 12, 4);
                playerCtx.fillStyle = '#ffd700';
                playerCtx.fillRect(12, 8, 8, 4);
                playerTexture.refresh();
            }

            create() {
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

                // Отрисовываем карту
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
                        
                        this.add.image(x * this.tileSize + this.tileSize/2, y * this.tileSize + this.tileSize/2, textureKey);
                    }
                }

                // Создаем физические объекты для коллизий
                this.collisionLayer = this.physics.add.staticGroup();

                // Добавляем коллизии для воды (тип 1) и гор (тип 2)
                for (let y = 0; y < this.mapData.length; y++) {
                    for (let x = 0; x < this.mapData[y].length; x++) {
                        const tileType = this.mapData[y][x];
                        if (tileType === 1 || tileType === 2) { // вода или горы
                            const collisionRect = this.collisionLayer.create(
                                x * this.tileSize + this.tileSize/2, 
                                y * this.tileSize + this.tileSize/2, 
                                null
                            );
                            collisionRect.setSize(this.tileSize, this.tileSize);
                            collisionRect.setVisible(false);
                        }
                    }
                }

                // Создаем персонажа в указанной позиции
                const startPixelX = this.playerStartX * this.tileSize + this.tileSize/2;
                const startPixelY = this.playerStartY * this.tileSize + this.tileSize/2;
                
                this.player = this.physics.add.sprite(startPixelX, startPixelY, 'player');
                this.player.setCollideWorldBounds(true);

                // Добавляем коллизии между персонажем и препятствиями
                this.physics.add.collider(this.player, this.collisionLayer);

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
                // Обработка перемещения персонажа
                this.player.setVelocity(0);

                if (this.cursorKeys.left.isDown) {
                    this.player.setVelocityX(-100);
                } else if (this.cursorKeys.right.isDown) {
                    this.player.setVelocityX(100);
                }

                if (this.cursorKeys.up.isDown) {
                    this.player.setVelocityY(-100);
                } else if (this.cursorKeys.down.isDown) {
                    this.player.setVelocityY(100);
                }
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
            },
            physics: {
                default: 'arcade',
                arcade: {
                    gravity: { y: 0 },
                    debug: false
                }
            }
        };

        const game = new Phaser.Game(config);
    </script>
</body>
</html>