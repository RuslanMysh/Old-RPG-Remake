<?php

use Dom\CharacterData;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $character_data = [
        'Язык программирования' => $_POST['programist'] ?? 'Не указано',
        'Стиль волос' => $_POST['hair_style'] ?? 'Не указано',
        'Цвет волос' => $_POST['hair_color'] ?? 'Не указано',
        'Стиль бороды' => $_POST['beard_style'] ?? 'Не указано',
        'Цвет бороды' => $_POST['beard_color'] ?? 'Не указано',
        'Цвет кожи' => $_POST['skin_color'] ?? 'Не указано',
        'Цвет глаз' => $_POST['eyes_color'] ?? 'Не указано'
    ];
}
?>

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
        .character-data {
            position: absolute;
            top: 20px;
            left: 20px;
            color: #fff;
            font-family: Arial, sans-serif;
            padding: 15px;
            border-radius: 10px;
            border: 2px solid #ffffffff;
            max-width: 300px;
            z-index: 1000;
        }
        
        .character-data h2 {
            color: #ffffffff;
            margin-top: 0;
            text-align: center;
        }
        
        .character-data ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .character-data li {
            margin-bottom: 8px;
            padding: 5px;
        }
        
        .character-data strong {
            color: #ffffffff;
        }
    </style>
</head>
<body>
    <div class="container">
        <p class="instructions">Исследуй ПЛЮСОВЫЕ ХОЛМЫ.</p>
    </div>

    <?php if (isset($character_data)): ?>
    <div class="character-data">
        <h2>Данные персонажа</h2>
        <ul>
            <?php foreach ($character_data as $key => $value): ?>
                <li><strong><?= htmlspecialchars($key) ?>:</strong> <?= htmlspecialchars($value) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

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
                this.playerStartX = 4; // координата X в тайлах
                this.playerStartY = 7; // координата Y в тайлах
            }

            preload() {
                // Создаем текстуры программно
                this.createTextures();

                this.load.image('grass', 'assets/grass.png');
                this.load.image('water', 'assets/water.png');
                this.load.image('mountain', 'assets/rock.png');
                this.load.image('sand', 'assets/sand.png');
                this.load.image('forest', 'assets/tree.png');
                this.load.image('path', 'assets/path.png');
            }

            createTextures() {
                
                const tileSize = this.tileSize;
    
                const playerTexture = this.textures.createCanvas('player', tileSize, tileSize);
                const playerCtx = playerTexture.getContext();

                // Основные цвета персонажа
                let tunicColor = '#006400'; // Зеленый по умолчанию
                let hairColor = '#ffd700';  // Золотистый по умолчанию
                
                // PHP условие для изменения цвета туники
                <?php if(isset($character_data['Язык программирования']) && $character_data['Язык программирования'] === 'c#'): ?>
                    tunicColor = '#800080'; // Фиолетовый для C#
                <?php endif; ?>
                
                // Рисуем персонажа с учетом выбранных цветов
                playerCtx.fillStyle = tunicColor;
                playerCtx.fillRect(8, 8, 16, 16);
                
                playerCtx.fillStyle = '#1f411fff';
                playerCtx.fillRect(24, 10, 3, 13);
                playerCtx.fillStyle = '#1f411fff';
                playerCtx.fillRect(5, 10, 3, 13);
                
                playerCtx.fillStyle = '#ffdbac';
                playerCtx.fillRect(12, 4, 8, 8);
                
                playerCtx.fillStyle = tunicColor;
                playerCtx.fillRect(10, 2, 12, 4);
                
                playerCtx.fillStyle = hairColor;
                playerCtx.fillRect(12, 8, 8, 4);

                playerCtx.fillStyle = '#080808ff';
                playerCtx.fillRect(8, 22, 7, 3);

                playerCtx.fillStyle = '#080808ff';
                playerCtx.fillRect(17, 22, 7, 3);
                
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

                this.checkForLocationTransition();
            }
            checkForLocationTransition() {
                // Координаты верхней границы тропинки (ряд 6, столбцы 14-15)
                const pathTopRow = 1;
                const pathStartCol = 14;
                const pathEndCol = 15;
                
                // Получаем текущую позицию игрока в тайлах
                const playerTileX = Math.floor(this.player.x / this.tileSize);
                const playerTileY = Math.floor(this.player.y / this.tileSize);
                
                // Проверяем, находится ли игрок на верхней границе тропинки
                if (playerTileY === pathTopRow && 
                    playerTileX >= pathStartCol && 
                    playerTileX <= pathEndCol) {
                    
                    // Переход на другую страницу сайта
                    window.location.href = 'sharpField.php'; // Замените на URL вашей следующей страницы
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