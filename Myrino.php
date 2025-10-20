<?php
session_start();

if (!isset($_SESSION['quest_data'])) {
    $_SESSION['quest_data'] = [
        'active' => false,
        'title' => '',
        'objective' => '',
        'reward' => '',
        'giver' => '',
        'status' => 'Не активно'
    ];
}

if (isset($_SESSION['character_data'])) {
    $character_data = $_SESSION['character_data'];
} else {
    $character_data = [];
}

$quest_data = $_SESSION['quest_data'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Good Ass Coder - Мурино</title>
    <script src="https://cdn.jsdelivr.net/npm/phaser@3.70.0/dist/phaser.min.js"></script>
    <style>
        #body {
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
            margin-top: 80px;
            margin-left: 0px;
            color: #fff;
            text-shadow: 0 0 10px #ffffffff, 2px 2px 0 #000;
            font-family: Arial, sans-serif;
            padding: 15px;
            border-radius: 15px;
            border: 2px solid #ffffffff;
            max-width: 600px;
            font-size: 16px;
            text-align: center;
            z-index: 1000;
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
        .quest-info {
            position: absolute;
            top: 20px;
            right: 20px;
            color: #fff;
            font-family: Arial, sans-serif;
            padding: 15px;
            border-radius: 10px;
            border: 2px solid #ffd700;
            max-width: 300px;
            z-index: 1000;
            background-color: rgba(0, 0, 0, 0.8);
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.5);
        }

        .quest-info h2 {
            color: #ffd700;
            margin-top: 0;
            text-align: center;
            font-size: 1.2rem;
            text-shadow: 0 0 5px rgba(255, 215, 0, 0.7);
        }

        .quest-info p {
            margin: 8px 0;
            line-height: 1.4;
            font-size: 0.9rem;
        }

        .quest-info .quest-title {
            color: #ff6b6b;
            font-weight: bold;
            font-size: 1rem;
        }

        .quest-info .quest-objective {
            color: #4ecdc4;
        }

        .quest-info .quest-reward {
            color: #ffe66d;
            font-style: italic;
        }

        .quest-info .quest-status {
            color: #90ee90;
            font-weight: bold;
        }
    </style>
</head>
<body id="body">
    <div class="container">
        <p class="instructions">Вы прибыли в город ПитоМурино - здесь живут опытные программитсы Python.</p>
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
    <?php if (isset($quest_data) && $quest_data['active']): ?>
    <div class="quest-info">
        <h2>АКТИВНОЕ ЗАДАНИЕ</h2>
        <p class="quest-title"><?= htmlspecialchars($quest_data['title']) ?></p>
        <p class="quest-objective"><?= htmlspecialchars($quest_data['objective']) ?></p>
        <p class="quest-reward"><?= htmlspecialchars($quest_data['reward']) ?></p>
        <p class="quest-status">Статус: <?= htmlspecialchars($quest_data['status']) ?></p>
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
                this.npc = null;
                this.questText = null;

                this.playerStartX = 14; 
                this.playerStartY = 13; 
            }

            preload() {
                
                this.createTextures();

                this.load.image('grass', 'assets/grass.png');
                this.load.image('water', 'assets/water.png');
                this.load.image('mountain', 'assets/rock.png');
                this.load.image('sand', 'assets/sand.png');
                this.load.image('forest', 'assets/tree.png');
                this.load.image('path', 'assets/path.png');
                this.load.image('oldWood', 'assets/oldWood.png');
                this.load.image('oldWoodFloor', 'assets/oldWoodFloor.png');
            }

            createTextures() {
                
                const tileSize = this.tileSize;
    
                const playerTexture = this.textures.createCanvas('player', tileSize, tileSize);
                const playerCtx = playerTexture.getContext();
                const body = document.getElementById('body');
                
                let bodyColor = '#006400'; 
                let hairColor = '#ffd700';  
                let beardColor = '#f1e496ff';
                let handsColor = '#043608ff';  
                let skinColor = '#fdcc9eff';
                let eyesColor = '#00a2ffff';

                <?php if(isset($character_data['Язык программирования']) && $character_data['Язык программирования'] === 'c#'): ?>
                    bodyColor = '#720072ff';
                    handsColor = '#b10093ff';  
                    body.style.background = '#1d001dff';
                <?php endif; ?>
                <?php if(isset($character_data['Язык программирования']) && $character_data['Язык программирования'] === 'c++'): ?>
                    bodyColor = '#1582ffff';
                    handsColor = '#5599e6ff';  
                    body.style.background = '#3ec2ffff';
                <?php endif; ?>
                <?php if(isset($character_data['Язык программирования']) && $character_data['Язык программирования'] === 'python'): ?>
                    bodyColor = '#006400'; 
                    handsColor = '#043608ff';  
                    body.style.background = '#619e00ff';
                <?php endif; ?>

                <?php if(isset($character_data['Цвет кожи']) && $character_data['Цвет кожи'] === 'сахарок'): ?>               
                    skinColor = '#fdcc9eff'; 
                <?php endif; ?>
                <?php if(isset($character_data['Цвет кожи']) && $character_data['Цвет кожи'] === 'негр'): ?>               
                    skinColor = '#2e1c0cff'; 
                <?php endif; ?>

                <?php if(isset($character_data['Цвет глаз']) && $character_data['Цвет глаз'] === 'голубые'): ?>               
                    eyesColor = '#00a2ffff'; 
                <?php endif; ?>
                <?php if(isset($character_data['Цвет глаз']) && $character_data['Цвет глаз'] === 'зеленые'): ?>               
                    eyesColor = '#306321ff'; 
                <?php endif; ?>
                <?php if(isset($character_data['Цвет глаз']) && $character_data['Цвет глаз'] === 'карие'): ?>               
                    eyesColor = '#773000ff'; 
                <?php endif; ?>
                <?php if(isset($character_data['Цвет глаз']) && $character_data['Цвет глаз'] === 'красные'): ?>               
                    eyesColor = '#5e0808ff'; 
                <?php endif; ?>

                <?php if(isset($character_data['Цвет волос']) && $character_data['Цвет волос'] === 'черные'): ?>               
                    hairColor = '#0a080aff'; 
                <?php endif; ?>
                <?php if(isset($character_data['Цвет волос']) && $character_data['Цвет волос'] === 'брюнет'): ?>               
                    hairColor = '#141414ff'; 
                <?php endif; ?>
                <?php if(isset($character_data['Цвет волос']) && $character_data['Цвет волос'] === 'блондин'): ?>               
                    hairColor = '#fffc5fff'; 
                <?php endif; ?>
                <?php if(isset($character_data['Цвет волос']) && $character_data['Цвет волос'] === 'рыжий'): ?>               
                    hairColor = '#ff9532ff'; 
                <?php endif; ?>
                <?php if(isset($character_data['Цвет волос']) && $character_data['Цвет волос'] === 'седой'): ?>               
                    hairColor = '#d1d1d1ff'; 
                <?php endif; ?>
                <?php if(isset($character_data['Цвет волос']) && $character_data['Цвет волос'] === 'розовый'): ?>               
                    hairColor = '#ff6edbff'; 
                <?php endif; ?>

                <?php if(isset($character_data['Цвет бороды']) && $character_data['Цвет бороды'] === 'черный'): ?>               
                    beardColor = '#0a080aff'; 
                <?php endif; ?>
                <?php if(isset($character_data['Цвет бороды']) && $character_data['Цвет бороды'] === 'коричневый'): ?>               
                    beardColor = '#523525ff'; 
                <?php endif; ?>
                <?php if(isset($character_data['Цвет бороды']) && $character_data['Цвет бороды'] === 'блондин'): ?>               
                    beardColor = '#fff562ff'; 
                <?php endif; ?>
                <?php if(isset($character_data['Цвет бороды']) && $character_data['Цвет бороды'] === 'рыжий'): ?>               
                    beardColor = '#ff8e24ff'; 
                <?php endif; ?>
                <?php if(isset($character_data['Цвет бороды']) && $character_data['Цвет бороды'] === 'седой'): ?>               
                    beardColor = '#d1d1d1ff'; 
                <?php endif; ?>
                <?php if(isset($character_data['Цвет бороды']) && $character_data['Цвет бороды'] === 'розовый'): ?>               
                    beardColor = '#ff6edbff'; 
                <?php endif; ?>
                
                playerCtx.fillStyle = bodyColor;
                playerCtx.fillRect(8, 8, 16, 16);
                
                playerCtx.fillStyle = handsColor;
                playerCtx.fillRect(24, 10, 3, 13);
                playerCtx.fillStyle = handsColor;
                playerCtx.fillRect(5, 10, 3, 13);
                
                playerCtx.fillStyle = skinColor;
                playerCtx.fillRect(12, 4, 8, 8);
                
                playerCtx.fillStyle = hairColor;
                playerCtx.fillRect(10, 2, 12, 4);
                
                playerCtx.fillStyle = eyesColor;
                playerCtx.fillRect(13, 7, 2, 1);

                playerCtx.fillStyle = eyesColor;
                playerCtx.fillRect(17, 7, 2, 1);

                playerCtx.fillStyle = beardColor;
                playerCtx.fillRect(12, 9, 8, 4);

                playerCtx.fillStyle = '#080808ff';
                playerCtx.fillRect(8, 22, 7, 3);

                playerCtx.fillStyle = '#080808ff';
                playerCtx.fillRect(17, 22, 7, 3);
                
                playerTexture.refresh();

                // Текстура для NPC (Мудрец Мурино)
                const npcTexture = this.textures.createCanvas('npc', tileSize, tileSize);
                const npcCtx = npcTexture.getContext();
                
                // Тело NPC (фиолетовые одежды мудреца)
                npcCtx.fillStyle = '#8B008B';
                npcCtx.fillRect(6, 6, 20, 18);
                
                // Голова
                npcCtx.fillStyle = '#FFE4B5';
                npcCtx.fillRect(12, 4, 8, 8);
                
                // Борода (длинная седая)
                npcCtx.fillStyle = '#D3D3D3';
                npcCtx.fillRect(10, 12, 12, 8);
                
                // Волосы (седые)
                npcCtx.fillStyle = '#D3D3D3';
                npcCtx.fillRect(10, 2, 12, 4);
                
                // Глазы (мудрые)
                npcCtx.fillStyle = '#000080';
                npcCtx.fillRect(13, 7, 2, 2);
                npcCtx.fillRect(17, 7, 2, 2);
                
                // Посох мудреца
                npcCtx.fillStyle = '#8B4513';
                npcCtx.fillRect(3, 8, 4, 14);
                npcCtx.fillStyle = '#FFD700';
                npcCtx.fillRect(2, 6, 6, 4);
                
                npcTexture.refresh();
            }
        
            create() {
                this.mapData = [
                    [0, 0, 0, 0, 0, 0, 4, 0, 0, 0, 4, 0, 0, 0, 2, 2, 2, 2, 2, 2],
                    [0, 0, 1, 1, 0, 0, 0, 0, 6, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2],
                    [0, 1, 1, 1, 3, 3, 0, 0, 6, 6, 0, 0, 0, 0, 0, 6, 6, 0, 0, 2],
                    [0, 1, 1, 1, 3, 3, 0, 0, 0, 0, 0, 0, 4, 0, 0, 6, 6, 0, 0, 2],
                    [0, 0, 3, 3, 3, 0, 0, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2],
                    [0, 0, 3, 3, 0, 0, 0, 0, 0, 6, 6, 6, 6, 0, 4, 0, 0, 0, 0, 0],
                    [2, 0, 0, 0, 0, 0, 0, 0, 0, 6, 7, 7, 6, 0, 0, 0, 0, 6, 6, 0],
                    [2, 0, 0, 0, 0, 0, 0, 4, 0, 6, 7, 7, 6, 0, 0, 0, 0, 6, 6, 0],
                    [2, 0, 0, 0, 0, 0, 0, 0, 0, 6, 5, 5, 6, 0, 0, 4, 0, 0, 0, 0],
                    [2, 0, 0, 6, 6, 6, 0, 0, 0, 0, 5, 5, 5, 0, 0, 0, 0, 0, 0, 0],
                    [2, 0, 0, 6, 6, 6, 0, 0, 4, 0, 0, 5, 5, 5, 0, 0, 0, 4, 0, 0],
                    [2, 0, 0, 6, 6, 6, 0, 0, 0, 0, 0, 0, 5, 5, 5, 0, 0, 0, 0, 2],
                    [2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, 0, 0, 5, 5, 5, 0, 4, 0, 2],
                    [2, 0, 0, 0, 4, 0, 0, 4, 0, 0, 0, 0, 0, 0, 5, 5, 0, 0, 0, 2],
                    [2, 2, 0, 4, 0, 0, 4, 0, 0, 0, 4, 0, 4, 0, 5, 5, 0, 0, 2, 2]
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
                            case 6: textureKey = 'oldWood'; break;
                            case 7: textureKey = 'oldWoodFloor'; break;
                        }
                        
                        this.add.image(x * this.tileSize + this.tileSize/2, y * this.tileSize + this.tileSize/2, textureKey);
                    }
                }

                // Создаем физические объекты для коллизий
                this.collisionLayer = this.physics.add.staticGroup();

                // Добавляем коллизии для гор (тип 2) и домов (тип 6)
                for (let y = 0; y < this.mapData.length; y++) {
                    for (let x = 0; x < this.mapData[y].length; x++) {
                        const tileType = this.mapData[y][x];
                        if (tileType === 2 || tileType === 6) { 
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

                // Создаем персонажа
                const startPixelX = this.playerStartX * this.tileSize + this.tileSize/2;
                const startPixelY = this.playerStartY * this.tileSize + this.tileSize/2;
                
                this.player = this.physics.add.sprite(startPixelX, startPixelY, 'player');
                this.player.setCollideWorldBounds(true);

                // Добавляем NPC (Мудрец Мурино) - располагаем его в центре города
                this.npc = this.physics.add.sprite(10 * this.tileSize + this.tileSize/2, 7 * this.tileSize + this.tileSize/2, 'npc');
                this.npc.setImmovable(true);

                // Добавляем коллизии
                this.physics.add.collider(this.player, this.collisionLayer);
                this.physics.add.collider(this.player, this.npc, this.interactWithNPC, null, this);

                // Текст для диалога
                this.questText = this.add.text(320, 400, '', {
                    font: '14px Arial',
                    fill: '#ffffff',
                    backgroundColor: '#000000',
                    padding: { x: 10, y: 5 },
                    align: 'center'
                }).setOrigin(0.5,0.7).setVisible(false);

                this.add.text(160, 20, 'ГОРОД ПИТОМУРИНО', {
                    font: '16px Arial',
                    fill: '#9370DB',
                    stroke: '#FFFFFF',
                    strokeThickness: 4,
                    shadow: {
                        offsetX: 2,
                        offsetY: 2,
                        color: '#000000ff',
                        blur: 0,
                        stroke: true
                    }
                }).setOrigin(0.5);

                // Подсказка для игрока
                this.add.text(320, 450, 'Подойдите к Меллстрою для разговора', {
                    font: '12px Arial',
                    fill: '#DDA0DD'
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
                this.checkNPCDistance();
            }

            interactWithNPC() {
                const message = "Я уже пьяный! Подраться у меня не получится!\n\n" +
                              "*Меллстрой отрубился*\n" +
                              "*Вы сорвали с него изи брижди и ушли с богом*";
                
                this.showQuestText(message);
                
                this.time.delayedCall(5000, () => {
                    this.hideQuestText();
                });
            }

            showQuestText(text) {
                this.questText.setText(text);
                this.questText.setVisible(true);
            }

            hideQuestText() {
                this.questText.setVisible(false);
            }

            checkNPCDistance() {
                // Автоматически скрываем текст, если игрок отошел от NPC
                const distance = Phaser.Math.Distance.Between(
                    this.player.x, this.player.y,
                    this.npc.x, this.npc.y
                );

                if (distance > 80 && this.questText.visible) {
                    this.hideQuestText();
                }
            }

            checkForLocationTransition() {
                const pathTopRow = 14;
                const pathStartCol = 14;
                const pathEndCol = 16;
                
                const playerTileX = Math.floor(this.player.x / this.tileSize);
                const playerTileY = Math.floor(this.player.y / this.tileSize);
                
                if (playerTileY === pathTopRow && 
                    playerTileX >= pathStartCol && 
                    playerTileX <= pathEndCol) {
                    
                    window.location.href = 'sharpField.php'; 
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
