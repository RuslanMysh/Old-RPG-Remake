<?php
session_start();

// Инициализация данных об убитых крысах, если их нет
if (!isset($_SESSION['dead_rats'])) {
    $_SESSION['dead_rats'] = [];
}

// Инициализация крыс, которых нужно убить для квеста
if (!isset($_SESSION['rats_to_kill'])) {
    $_SESSION['rats_to_kill'] = ['rat_8_3', 'rat_12_5', 'rat_6_8', 'rat_14_10'];
}

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

// Обработка обновления квеста через POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quest_update'])) {
    $_SESSION['quest_data'] = [
        'active' => $_POST['quest_active'] === 'true',
        'title' => $_POST['quest_title'] ?? '',
        'objective' => $_POST['quest_objective'] ?? '',
        'reward' => $_POST['quest_reward'] ?? '',
        'giver' => $_POST['quest_giver'] ?? '',
        'status' => $_POST['quest_status'] ?? 'Не активно'
    ];
    echo json_encode(['status' => 'success']);
    exit;
}

// Проверка выполнения квеста при загрузке страницы
if ($_SESSION['quest_data']['active']) {
    $allRatsKilled = true;
    foreach ($_SESSION['rats_to_kill'] as $ratId) {
        if (!in_array($ratId, $_SESSION['dead_rats'])) {
            $allRatsKilled = false;
            break;
        }
    }
    
    if ($allRatsKilled) {
        $_SESSION['quest_data']['status'] = 'Выполнено';
        $_SESSION['quest_data']['active'] = false;
    }
}

// Обработка завершения квеста через AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_quest'])) {
    $allRatsKilled = true;
    foreach ($_SESSION['rats_to_kill'] as $ratId) {
        if (!in_array($ratId, $_SESSION['dead_rats'])) {
            $allRatsKilled = false;
            break;
        }
    }
    
    if ($allRatsKilled) {
        $_SESSION['quest_data']['status'] = 'Выполнено';
        $_SESSION['quest_data']['active'] = false;
        echo json_encode(['status' => 'success', 'quest_completed' => true]);
    } else {
        echo json_encode(['status' => 'success', 'quest_completed' => false]);
    }
    exit;
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
        <p class="instructions">Вы прибыли на в город Цивиль, столицу Сиплюсов.</p>
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
                this.questGiven = <?php echo $quest_data['active'] || $quest_data['status'] === 'Выполнено' ? 'true' : 'false'; ?>;
                this.questText = null;
                this.questActive = <?php echo $quest_data['active'] ? 'true' : 'false'; ?>;
                this.currentQuest = null;
                this.questCompleted = <?php echo $quest_data['status'] === 'Выполнено' ? 'true' : 'false'; ?>;

                this.playerStartX = 9; 
                this.playerStartY = 0; 
            }

            preload() {
                
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

                // Текстура для NPC (Король Быков)
                const npcTexture = this.textures.createCanvas('npc', tileSize, tileSize);
                const npcCtx = npcTexture.getContext();
                
                // Тело NPC (королевская мантия C++)
                npcCtx.fillStyle = '#0055a4'; // Синий цвет C++
                npcCtx.fillRect(6, 6, 20, 18);
                
                // Голова
                npcCtx.fillStyle = '#ffd09aff';
                npcCtx.fillRect(12, 4, 8, 8);
                
                // Корона
                npcCtx.fillStyle = '#ffd700';
                npcCtx.fillRect(8, 0, 16, 4);
                // Шипы короны
                npcCtx.fillRect(8, 0, 3, 6);
                npcCtx.fillRect(13, 0, 3, 6);
                npcCtx.fillRect(18, 0, 3, 6);
                
         
                
                // Глазы
                npcCtx.fillStyle = '#0047ab';
                npcCtx.fillRect(13, 7, 2, 2);
                npcCtx.fillRect(17, 7, 2, 2);
                
               
                
                // Руки
                npcCtx.fillStyle = '#003366';
                npcCtx.fillRect(3, 7, 4, 14);
                npcCtx.fillStyle = '#003366';
                npcCtx.fillRect(25, 7, 4, 14);

                // Ноги
                npcCtx.fillStyle = '#1a1a1a';
                npcCtx.fillRect(6, 21, 8, 4);
                npcCtx.fillStyle = '#1a1a1a';
                npcCtx.fillRect(18, 21, 8, 4);
                
                // Королевский жезл (символ C++)
                npcCtx.fillStyle = '#ffd700';
                npcCtx.fillRect(26, 8, 2, 10);
                npcCtx.fillStyle = '#0055a4';
                npcCtx.fillRect(24, 10, 6, 2);
                npcCtx.fillRect(25, 9, 4, 4);
                
                npcTexture.refresh();
            }
        
            create() {
                this.mapData = [
                    [2, 0, 0, 4, 0, 4, 0, 4, 4, 5, 5, 4, 0, 4, 0, 4, 0, 4, 4, 2],
                    [2, 4, 0, 0, 4, 0, 4, 0, 4, 5, 5, 0, 4, 0, 4, 0, 4, 0, 4, 2],
                    [2, 0, 4, 0, 0, 4, 0, 4, 4, 5, 5, 4, 0, 4, 0, 4, 0, 4, 0, 2],
                    [2, 0, 2, 4, 2, 0, 4, 0, 4, 5, 5, 4, 4, 0, 4, 0, 4, 0, 4, 2],
                    [2, 2, 2, 2, 2, 2, 0, 4, 4, 5, 5, 4, 0, 4, 0, 4, 0, 4, 0, 2],
                    [2, 0, 2, 0, 2, 0, 4, 0, 4, 5, 5, 4, 4, 0, 4, 2, 4, 2, 4, 2],
                    [2, 4, 0, 4, 0, 4, 0, 4, 4, 5, 5, 4, 0, 4, 2, 2, 2, 2, 2, 2],
                    [2, 0, 4, 0, 4, 0, 4, 0, 4, 5, 5, 4, 4, 0, 4, 2, 4, 2, 4, 2],
                    [2, 4, 2, 4, 2, 4, 0, 4, 4, 5, 5, 4, 0, 4, 0, 4, 0, 4, 0, 2],
                    [2, 2, 2, 2, 2, 2, 0, 0, 0, 0, 0, 0, 0, 2, 0, 2, 0, 0, 0, 2],
                    [2, 0, 2, 4, 2, 0, 0, 0, 0, 0, 0, 0, 2, 2, 2, 2, 2, 0, 4, 2],
                    [2, 0, 0, 0, 0, 0, 0, 4, 0, 0, 0, 4, 0, 2, 0, 2, 0, 4, 0, 2],
                    [2, 2, 0, 0, 0, 0, 4, 0, 0, 4, 0, 0, 0, 0, 0, 0, 4, 0, 2, 2],
                    [2, 2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 2, 2],
                    [2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2]
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
                        if (tileType === 1 || tileType === 2) { 
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

                // Добавляем NPC (Король Быков) - располагаем его на координатах 8, 6
                this.npc = this.physics.add.sprite(8 * this.tileSize + this.tileSize/2, 6 * this.tileSize + this.tileSize/2, 'npc');
                this.npc.setImmovable(true);

                // Добавляем коллизии
                this.physics.add.collider(this.player, this.collisionLayer);
                this.physics.add.collider(this.player, this.npc, this.interactWithNPC, null, this);

                // Текст для задания
                this.questText = this.add.text(320, 400, '', {
                    font: '14px Arial',
                    fill: '#ffffff',
                    backgroundColor: '#000000',
                    padding: { x: 10, y: 5 },
                    align: 'center'
                }).setOrigin(0.5,0.7).setVisible(false);

                this.add.text(160, 20, 'Город Цивиль', {
                    font: '16px Arial',
                    fill: '#0089e4ff',
                    stroke: '#ecad00ff',
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
                this.add.text(320, 450, 'Подойдите к Королю Быкову для получения королевского задания', {
                    font: '12px Arial',
                    fill: '#0f0f0fff'
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

            checkQuestCompletion() {
                // Проверяем, убиты ли все крысы для квеста
                const ratsToKill = <?php echo json_encode($_SESSION['rats_to_kill']); ?>;
                const deadRats = <?php echo json_encode($_SESSION['dead_rats']); ?>;
                
                let allRatsKilled = true;
                ratsToKill.forEach(ratId => {
                    if (!deadRats.includes(ratId)) {
                        allRatsKilled = false;
                    }
                });
                
                return allRatsKilled;
            }

            interactWithNPC() {
                // Проверяем, выполнены ли условия квеста
                const allRatsKilled = this.checkQuestCompletion();
                
                // Если квест уже выполнен, ничего не делаем
                if (this.questCompleted) {
                    const completedMessage = "Приветствую тебя, путник!\nОдин, мягко говоря, нигодяй, по-имени Меллстрой, украл легендарные изи-бриджи.\n Эти бриджи передовались нашему королевскому роду по наследству.\nОтправляйся в страну Питонистов и найди их!";
                    this.showQuestText(completedMessage);
                    this.time.delayedCall(3000, () => {
                        this.hideQuestText();
                    });
                    return;
                }
                
                // Основная логика взаимодействия с NPC
                if (!this.questGiven && !allRatsKilled) {
                    // Квест еще не взят и крысы не убиты - предлагаем квест
                    this.giveQuest();
                } else if (!this.questGiven && allRatsKilled) {
                    // Квест не взят, но крысы уже убиты - сразу завершаем
                    this.giveAndCompleteQuest();
                } else if (this.questActive && allRatsKilled) {
                    // Квест активен и крысы убиты - завершаем квест
                    this.completeQuest();
                } else if (this.questGiven && !allRatsKilled) {
                    // Квест взят, но крысы еще не все убиты - показываем прогресс
                    this.showQuestProgress();
                }
            }

            giveAndCompleteQuest() {
                this.questGiven = true;
                this.questActive = false;
                this.questCompleted = true;
                
                const questMessage = "Приветствую, странник!\n\n" +
                                "Я - Король Быков, правитель великого королевства C++.\n" +
                                "Вижу, ты уже выполнил моё поручение!\n" +
                                "Ты настоящий герой нашего королевства!\n\n" +
                                "Задание выполнено! Ты принёс нам знание об изи-бриджах!";
                
                this.showQuestText(questMessage);
                
                // Сохраняем завершение квеста
                this.completeQuestInSession();
                
                this.time.delayedCall(4000, () => {
                    this.hideQuestText();
                    location.reload();
                });
            }

            showQuestProgress() {
                const ratsToKill = <?php echo json_encode($_SESSION['rats_to_kill']); ?>;
                const deadRats = <?php echo json_encode($_SESSION['dead_rats']); ?>;
                const killedCount = deadRats.filter(ratId => ratsToKill.includes(ratId)).length;
                const totalCount = ratsToKill.length;
                
                const progressMessage = `Задание в процессе!\n\n` +
                                    `Убито крыс: ${killedCount}/${totalCount}\n` +
                                    `Вернись ко мне, когда выполнишь задание.`;
                
                this.showQuestText(progressMessage);
                
                this.time.delayedCall(3000, () => {
                    this.hideQuestText();
                });
            }

            completeQuest() {
                this.hideQuestText();
                this.questActive = false;
                this.questCompleted = true;
                
                // Отправляем запрос на завершение квеста
                this.completeQuestInSession();
                
                const completeMessage = "Задание выполнено!\n\n" +
                                    "Ты доказал свою преданность королевству C++!\n" +
                                    "Знание об изи-бриджах поможет нам укрепить могущество!\n\n" +
                                    "Благодарю тебя, достойный странник!";
                
                this.showQuestText(completeMessage);
                
                this.time.delayedCall(4000, () => {
                    this.hideQuestText();
                    location.reload();
                });
            }

            completeQuestInSession() {
                const formData = new FormData();
                formData.append('complete_quest', 'true');
                
                fetch('sharpField.php', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                .then(data => {
                    console.log('Quest completed:', data);
                }).catch(error => {
                    console.error('Error completing quest:', error);
                });
            }

            giveQuest() {
                this.questGiven = true;
                this.questActive = true;
                
                const questMessage = "Приветствую, странник!\n\n" +
                                   "Я - Король Быков, правитель великого королевства C++.\n" +
                                   "Мне нужна твоя помощь в важном деле.\n\n" +
                                   "Легенда гласит о мифических штанах - изи-бриджах,\n" +
                                   "которые даруют невероятную скорость кодирования.\n\n" +
                                   "Отправляйся к Меллстрою в его башню знаний\n" +
                                   "и узнай у него всё, что можно, об изи-бриджах.\n\n" +
                                   "Нажмите ПРОБЕЛ для принятия королевского задания";
                
                this.showQuestText(questMessage);
                
                // Добавляем обработчик клавиши пробела
                this.spaceKey = this.input.keyboard.addKey(Phaser.Input.Keyboard.KeyCodes.SPACE);
            }

            showQuestText(text) {
                this.questText.setText(text);
                this.questText.setVisible(true);
            }

            hideQuestText() {
                this.questText.setVisible(false);
            }

            checkNPCDistance() {
                if (this.questText.visible && this.spaceKey && Phaser.Input.Keyboard.JustDown(this.spaceKey)) {
                    this.acceptQuest();
                }

                // Автоматически скрываем текст, если игрок отошел от NPC
                const distance = Phaser.Math.Distance.Between(
                    this.player.x, this.player.y,
                    this.npc.x, this.npc.y
                );

                if (distance > 80 && this.questText.visible && !this.questGiven) {
                    this.hideQuestText();
                }
            }

            acceptQuest() {
                this.hideQuestText();
                
                this.currentQuest = {
                    title: "Тайна изи-бриджей",
                    objective: "Отправиться к Меллстрою и узнать про мифические штаны - изи-бриджи",
                    reward: "Награда: Благословение Короля Быков",
                    giver: "Король Быков (Правитель C++)",
                    status: "Активно"
                };
                
                this.saveQuestToSession(this.currentQuest);
                
                const acceptMessage = "Королевское задание принято!\n\n" +
                                    "Цель: Найти Меллстроя и узнать тайну изи-бриджей.\n" +
                                    "Вернитесь к Королю Быкову после выполнения";
                
                this.showQuestText(acceptMessage);
                
                this.time.delayedCall(3000, () => {
                    this.hideQuestText();
                    location.reload();
                });
            }

            // Сохранения квеста в сессию
            saveQuestToSession(questData) {
                const formData = new FormData();
                formData.append('quest_update', 'true');
                formData.append('quest_active', 'true');
                formData.append('quest_title', questData.title);
                formData.append('quest_objective', questData.objective);
                formData.append('quest_reward', questData.reward);
                formData.append('quest_giver', questData.giver);
                formData.append('quest_status', questData.status);
                
                fetch('sharpField.php', {
                    method: 'POST',
                    body: formData
                }).then(response => {
                    console.log('Quest saved to session');
                }).catch(error => {
                    console.error('Error saving quest:', error);
                });
            }

            showQuestPanel() {
                // Показываем красивую панель с информацией о задании
                const questInfo = document.getElementById('questInfo');
                if (questInfo) {
                    questInfo.style.display = 'block';
                }
            }

            checkForLocationTransition() {
                const pathTopRow = 0;
                const pathStartCol = 10;
                const pathEndCol = 11;
                
                const playerTileX = Math.floor(this.player.x / this.tileSize);
                const playerTileY = Math.floor(this.player.y / this.tileSize);
                
                if (playerTileY === pathTopRow && 
                    playerTileX >= pathStartCol && 
                    playerTileX <= pathEndCol) {
                    
                    window.location.href = 'preCivil.php'; 
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