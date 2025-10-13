<?php
session_start();
?>
<?php
if (isset($_SESSION['character_data'])) {
    $character_data = $_SESSION['character_data'];
} else {
    $character_data = [];
}

// Если пришел POST-запрос, сохраняем данные в сессию
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['character_data'] = [
        'Язык программирования' => $_POST['programist'] ?? 'Не указано',
        'Стиль волос' => $_POST['hair_style'] ?? 'Не указано',
        'Цвет волос' => $_POST['hair_color'] ?? 'Не указано',
        'Стиль бороды' => $_POST['beard_style'] ?? 'Не указано',
        'Цвет бороды' => $_POST['beard_color'] ?? 'Не указано',
        'Цвет кожи' => $_POST['skin_color'] ?? 'Не указано',
        'Цвет глаз' => $_POST['eyes_color'] ?? 'Не указано'
    ];
    
    // Обновляем переменную
    $character_data = $_SESSION['character_data'];
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
            color: #ffffffff;
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
        
        /* Стили для отображения здоровья */
        .health-display {
            position: absolute;
            top: 20px;
            right: 20px;
            color: #fff;
            font-family: Arial, sans-serif;
            padding: 15px;
            border-radius: 10px;
            border: 2px solid #ffffffff;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            min-width: 200px;
        }
        
        .health-bar-container {
            width: 100%;
            height: 20px;
            background-color: #333;
            border-radius: 10px;
            margin-top: 5px;
            overflow: hidden;
        }
        
        .health-bar {
            height: 100%;
            background: linear-gradient(to right, #ff0000, #ffff00, #00ff00);
            border-radius: 10px;
            transition: width 0.3s ease;
        }
        
        .health-text {
            text-align: center;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .combat-info {
            position: absolute;
            bottom: 20px;
            left: 20px;
            color: #fff;
            font-family: Arial, sans-serif;
            padding: 10px;
            border-radius: 10px;
            background-color: rgba(0, 0, 0, 0.69);
            z-index: 1000;
            max-width: 300px;
        }
    </style>
</head>
<body id="body">
    <div class="container">
        <p class="instructions">Вы прибыли в пригород Цивиль. Осторожно, здесь водятся крысы!</p>
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

    <!-- Отображение здоровья в HTML -->
    <div class="health-display">
        <div class="health-text" id="health-text">Здоровье: 100/100</div>
        <div class="health-bar-container">
            <div class="health-bar" id="health-bar" style="width: 100%;"></div>
        </div>
    </div>

    <!-- Информация о боях -->
    <div class="combat-info">
        <h3>Управление в бою:</h3>
        <p>ПРОБЕЛ - атака</p>
        <p>Стрелки - движение</p>
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
            this.rats = [];
            this.playerHealth = 100;
            this.playerMaxHealth = 100;
            this.attackCooldown = 0;
            this.attackKey = null;
            this.isAttacking = false;
            this.attackArea = null;
            this.regenCooldown = 0; // Таймер для регенерации
            this.lastDamageTime = 0; // Время последнего получения урона

            this.playerStartX = 5; 
            this.playerStartY = 2; 
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

                // Текстура для крысы
                const ratTexture = this.textures.createCanvas('rat', tileSize, tileSize);
                const ratCtx = ratTexture.getContext();
                
                // Тело крысы
                ratCtx.fillStyle = '#818181ff';
                ratCtx.fillRect(8, 12, 16, 8);
                
                // Голова
                ratCtx.fillStyle = 'rgba(112, 112, 112, 1)';
                ratCtx.fillRect(4, 8, 8, 8);
                
                // Уши
                ratCtx.fillStyle = '#FF69B4';
                ratCtx.beginPath();
                ratCtx.arc(5, 6, 3, 0, Math.PI * 2);
                ratCtx.fill();
                ratCtx.beginPath();
                ratCtx.arc(12, 6, 3, 0, Math.PI * 2);
                ratCtx.fill();
                
                // Глаза
                ratCtx.fillStyle = '#FF0000';
                ratCtx.fillRect(6, 10, 2, 2);
                ratCtx.fillRect(10, 10, 2, 2);
                
                // Хвост
                ratCtx.strokeStyle = '#ff79f4ff';
                ratCtx.lineWidth = 2;
                ratCtx.beginPath();
                ratCtx.moveTo(24, 16);
                ratCtx.lineTo(30, 12);
                ratCtx.stroke();
                
                ratTexture.refresh();

                // Текстура для зоны атаки
                const attackTexture = this.textures.createCanvas('attack', tileSize, tileSize);
                const attackCtx = attackTexture.getContext();
                
                attackCtx.fillStyle = '#FF0000';
                attackCtx.globalAlpha = 0.5;
                attackCtx.fillRect(0, 0, tileSize, tileSize);
                
                attackTexture.refresh();
            }
        
             create() {
            this.mapData = [
                [2, 2, 2, 2, 4, 5, 4, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2],
                [2, 2, 2, 2, 0, 5, 0, 4, 4, 0, 4, 4, 4, 0, 4, 2, 2, 2, 2, 2],
                [2, 2, 2, 0, 0, 5, 0, 4, 0, 0, 0, 0, 0, 0, 0, 4, 2, 2, 2, 2],
                [2, 2, 0, 4, 0, 5, 5, 0, 0, 4, 0, 0, 0, 0, 2, 0, 0, 2, 2, 2],
                [2, 4, 0, 2, 0, 5, 5, 5, 0, 0, 0, 0, 6, 6, 2, 2, 0, 0, 2, 2],
                [2, 0, 0, 2, 0, 0, 5, 5, 5, 0, 4, 0, 6, 7, 6, 2, 2, 0, 0, 2],
                [2, 2, 0, 0, 0, 0, 0, 5, 5, 5, 0, 4, 0, 7, 7, 6, 2, 2, 0, 2],
                [2, 2, 2, 0, 4, 0, 4, 0, 5, 5, 5, 0, 0, 0, 7, 7, 6, 0, 0, 2],
                [2, 2, 2, 2, 0, 0, 0, 4, 0, 5, 5, 5, 0, 0, 6, 6, 6, 0, 0, 2],
                [2, 2, 2, 2, 0, 4, 0, 0, 0, 0, 5, 5, 5, 0, 0, 0, 0, 4, 0, 2],
                [2, 2, 2, 2, 2, 0, 0, 0, 4, 0, 0, 5, 5, 5, 0, 0, 0, 0, 4, 2],
                [2, 2, 2, 2, 2, 2, 0, 4, 0, 0, 0, 4, 5, 5, 5, 0, 0, 4, 0, 2],
                [2, 2, 2, 0, 0, 0, 4, 0, 0, 4, 0, 0, 0, 5, 5, 5, 4, 0, 0, 2],
                [2, 2, 2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5, 5, 5, 0, 0, 4, 2],
                [2, 2, 2, 2, 2, 0, 0, 4, 4, 0, 4, 0, 0, 0, 5, 5, 0, 0, 0, 2]
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

            // Добавляем коллизии для воды (тип 1) и гор (тип 2)
            for (let y = 0; y < this.mapData.length; y++) {
                for (let x = 0; x < this.mapData[y].length; x++) {
                    const tileType = this.mapData[y][x];
                    if (tileType === 1 || tileType === 2 || tileType === 6) { 
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

            // Создаем зону атаки как физический спрайт
            this.attackArea = this.physics.add.sprite(0, 0, 'attack');
            this.attackArea.setVisible(false);
            this.attackArea.setActive(false);
            this.attackArea.body.setSize(this.tileSize, this.tileSize);

            // Создаем крыс-врагов
            this.createRats();

            // Добавляем коллизии
            this.physics.add.collider(this.player, this.collisionLayer);
            this.physics.add.collider(this.rats, this.collisionLayer);
            this.physics.add.overlap(this.player, this.rats, this.playerHit, null, this);
            
            // Добавляем overlap для зоны атаки и крыс
            this.physics.add.overlap(this.attackArea, this.rats, this.attackRat, null, this);

            // Обновляем отображение здоровья в HTML
            this.updateHealthDisplay();

            this.add.text(90, 10, 'ПРИГОРОД ЦИВИЛЬ', {
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
            this.attackKey = this.input.keyboard.addKey(Phaser.Input.Keyboard.KeyCodes.SPACE);

            // Инициализация таймера регенерации
            this.lastDamageTime = this.time.now;
            this.regenCooldown = 0;
        }

        createRats() {
            this.rats = this.physics.add.group();
            
            // Создаем несколько крыс в разных местах карты
            const ratPositions = [
                {x: 8, y: 3},
                {x: 12, y: 5},
                {x: 6, y: 8},
                {x: 14, y: 10}
            ];
            
            ratPositions.forEach(pos => {
                const rat = this.rats.create(
                    pos.x * this.tileSize + this.tileSize/2,
                    pos.y * this.tileSize + this.tileSize/2,
                    'rat'
                );
                
                // Настройки крысы
                rat.setCollideWorldBounds(true);
                rat.health = 10;
                rat.speed = 40;
                rat.attackCooldown = 2;
                rat.damage = 10;
                rat.receivedDamage = false; // Флаг получения урона от текущей атаки
            });
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

            // Обработка атаки
            if (this.attackKey.isDown && this.attackCooldown <= 0) {
                this.attack();
            }

            // Обновление кулдауна атаки
            if (this.attackCooldown > 0) {
                this.attackCooldown--;
            }

            // Движение крыс к игроку
            this.moveRats();

            // Авторегенерация здоровья
            this.autoRegenerateHealth();

            this.checkForLocationTransition();
            this.checkForLocationTransition2();
        }

        // НОВЫЙ МЕТОД: Авторегенерация здоровья
        autoRegenerateHealth() {
            const currentTime = this.time.now;
            
            // Если прошло 3 секунды после последнего получения урона и здоровье не полное
            if (currentTime - this.lastDamageTime > 3000 && this.playerHealth < this.playerMaxHealth) {
                // Уменьшаем кулдаун регенерации
                if (this.regenCooldown <= 0) {
                    // Восстанавливаем здоровье
                    this.playerHealth = Math.min(this.playerHealth + 2, this.playerMaxHealth);
                    
                    // Обновляем отображение здоровья
                    this.updateHealthDisplay();
                    
                    // Визуальный эффект регенерации (легкое зеленое свечение)
                    this.player.setTint(0x00FF00);
                    this.time.delayedCall(100, () => {
                        this.player.clearTint();
                    });
                    
                    // Устанавливаем кулдаун регенерации (1 секунда)
                    this.regenCooldown = 60;
                } else {
                    this.regenCooldown--;
                }
            } else {
                // Сбрасываем кулдаун регенерации, если условие не выполняется
                this.regenCooldown = 0;
            }
        }

        attack() {
            // Устанавливаем кулдаун атаки
            this.attackCooldown = 30; // 0.5 секунды при 60 FPS
            
            // Сбрасываем флаги получения урона у всех крыс
            this.rats.getChildren().forEach(rat => {
                if (rat.active) {
                    rat.receivedDamage = false;
                }
            });
            
            // Активируем зону атаки
            this.attackArea.setPosition(this.player.x, this.player.y);
            this.attackArea.setVisible(true);
            this.attackArea.setActive(true);
            
            // Через 200ms скрываем зону атаки
            this.time.delayedCall(200, () => {
                this.attackArea.setVisible(false);
                this.attackArea.setActive(false);
            });
        }

        attackRat(attackArea, rat) {
            // Проверяем, не получала ли уже эта крыса урон от текущей атаки
            if (!rat.receivedDamage) {
                // Помечаем, что крыса получила урон от этой атаки
                rat.receivedDamage = true;
                
                // Наносим урон крысе
                rat.health -= 20;
                
                // Эффект получения урона (мигание)
                rat.setTint(0xFF0000);
                this.time.delayedCall(200, () => {
                    rat.clearTint();
                });
                
                // Если здоровье крысы <= 0, удаляем её
                if (rat.health <= 0) {
                    rat.destroy();
                }
                
                // Сбрасываем флаг получения урона через небольшое время
                this.time.delayedCall(100, () => {
                    if (rat.active) {
                        rat.receivedDamage = false;
                    }
                });
            }
        }

        playerHit(player, rat) {
            // Проверяем кулдаун атаки крысы
            if (rat.attackCooldown <= 0) {
                // Наносим урон игроку
                this.playerHealth -= rat.damage;
                
                // Обновляем время последнего получения урона
                this.lastDamageTime = this.time.now;
                
                // Обновляем отображение здоровья в HTML
                this.updateHealthDisplay();
                
                // Эффект получения урона
                player.setTint(0xFF0000);
                this.time.delayedCall(200, () => {
                    player.clearTint();
                });
                
                // Устанавливаем кулдаун атаки крысы
                rat.attackCooldown = 60; // 1 секунда при 60 FPS
                
                // Проверяем смерть игрока
                if (this.playerHealth <= 0) {
                    this.gameOver();
                }
            }
            
            // Обновляем кулдаун атаки крысы
            if (rat.attackCooldown > 0) {
                rat.attackCooldown--;
            }
        }

        moveRats() {
            this.rats.getChildren().forEach(rat => {
                if (rat.active) {
                    // Вычисляем направление к игроку
                    const angle = Phaser.Math.Angle.Between(
                        rat.x, rat.y,
                        this.player.x, this.player.y
                    );
                    
                    // Двигаем крысу к игроку
                    rat.setVelocity(
                        Math.cos(angle) * rat.speed,
                        Math.sin(angle) * rat.speed
                    );
                    
                    // Обновляем кулдаун атаки крысы
                    if (rat.attackCooldown > 0) {
                        rat.attackCooldown--;
                    }
                }
            });
        }

        updateHealthDisplay() {
            // Обновляем текст здоровья
            document.getElementById('health-text').textContent = `Здоровье: ${this.playerHealth}/${this.playerMaxHealth}`;
            
            // Обновляем полоску здоровья
            const healthPercent = (this.playerHealth / this.playerMaxHealth) * 100;
            document.getElementById('health-bar').style.width = `${healthPercent}%`;
            
            // Меняем цвет полоски в зависимости от здоровья
            const healthBar = document.getElementById('health-bar');
            if (healthPercent > 70) {
                healthBar.style.background = 'linear-gradient(to right, #00ff00, #80ff00)';
            } else if (healthPercent > 30) {
                healthBar.style.background = 'linear-gradient(to right, #ffff00, #ff8000)';
            } else {
                healthBar.style.background = 'linear-gradient(to right, #ff0000, #ff4000)';
            }
        }

        gameOver() {
            // Останавливаем игру
            this.physics.pause();
            this.player.setTint(0xFF0000);
            
            // Показываем сообщение о Game Over
            const gameOverText = this.add.text(320, 240, 'ВЫ ПОГИБЛИ!\nНажмите R для перезапуска', {
                font: '24px Arial',
                fill: '#ff0000',
                align: 'center'
            }).setOrigin(0.5);
            
            // Добавляем возможность перезапуска
            this.input.keyboard.once('keydown-R', () => {
               window.location.href = 'sims4.php'; 
            });
        }

        checkForLocationTransition() {
            const pathTopRow = 14;
            const pathStartCol = 15;
            const pathEndCol = 16;
            
            const playerTileX = Math.floor(this.player.x / this.tileSize);
            const playerTileY = Math.floor(this.player.y / this.tileSize);
            
            if (playerTileY === pathTopRow && 
                playerTileX >= pathStartCol && 
                playerTileX <= pathEndCol) {
                
                window.location.href = 'index.php'; 
            }
        }
        checkForLocationTransition2() {
              
                const pathTopRow = 1;
                const pathStartCol = 4;
                const pathEndCol = 5;
                
                
                const playerTileX = Math.floor(this.player.x / this.tileSize);
                const playerTileY = Math.floor(this.player.y / this.tileSize);
                
                
                if (playerTileY === pathTopRow && 
                    playerTileX >= pathStartCol && 
                    playerTileX <= pathEndCol) {
                    
                   
                    window.location.href = 'index.php'; 
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