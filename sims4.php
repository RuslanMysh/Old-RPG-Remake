<?php
// Обработка выбора характеристик
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $direction = $_POST['direction'] ?? '';
    $hair_style = $_POST['hair_style'] ?? '';
    $hair_color = $_POST['hair_color'] ?? '';
    $beard_style = $_POST['beard_style'] ?? '';
    $beard_color = $_POST['beard_color'] ?? '';
    $skin_color = $_POST['skin_color'] ?? '';
    $eye_color = $_POST['eye_color'] ?? '';
    
    $character_created = true;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактор персонажа</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .character-preview {
            border: 2px solid #333;
            padding: 20px;
            margin-top: 20px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        .character-trait {
            margin: 5px 0;
        }
        .submit-btn {
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .submit-btn:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Редактор персонажа программиста</h1>
        
        <form method="POST">
            <!-- Выбор направления -->
            <div class="form-group">
                <label for="direction">Основное направление:</label>
                <select id="direction" name="direction" required>
                    <option value="">-- Выберите язык --</option>
                    <option value="python" <?= isset($direction) && $direction === 'python' ? 'selected' : '' ?>>Python</option>
                    <option value="csharp" <?= isset($direction) && $direction === 'csharp' ? 'selected' : '' ?>>C#</option>
                    <option value="cpp" <?= isset($direction) && $direction === 'cpp' ? 'selected' : '' ?>>C++</option>
                    <option value="other" <?= isset($direction) && $direction === 'other' ? 'selected' : '' ?>>Другое</option>
                </select>
            </div>

            <!-- Стиль и цвет волос -->
            <div class="form-group">
                <label for="hair_style">Стиль волос:</label>
                <select id="hair_style" name="hair_style" required>
                    <option value="">-- Выберите стиль --</option>
                    <option value="short" <?= isset($hair_style) && $hair_style === 'short' ? 'selected' : '' ?>>Короткие</option>
                    <option value="medium" <?= isset($hair_style) && $hair_style === 'medium' ? 'selected' : '' ?>>Средние</option>
                    <option value="long" <?= isset($hair_style) && $hair_style === 'long' ? 'selected' : '' ?>>Длинные</option>
                    <option value="bald" <?= isset($hair_style) && $hair_style === 'bald' ? 'selected' : '' ?>>Лысый</option>
                </select>
            </div>

            <div class="form-group">
                <label for="hair_color">Цвет волос:</label>
                <select id="hair_color" name="hair_color" required>
                    <option value="">-- Выберите цвет --</option>
                    <option value="black" <?= isset($hair_color) && $hair_color === 'black' ? 'selected' : '' ?>>Чёрный</option>
                    <option value="brown" <?= isset($hair_color) && $hair_color === 'brown' ? 'selected' : '' ?>>Коричневый</option>
                    <option value="blonde" <?= isset($hair_color) && $hair_color === 'blonde' ? 'selected' : '' ?>>Блондин</option>
                    <option value="red" <?= isset($hair_color) && $hair_color === 'red' ? 'selected' : '' ?>>Рыжий</option>
                    <option value="gray" <?= isset($hair_color) && $hair_color === 'gray' ? 'selected' : '' ?>>Седой</option>
                </select>
            </div>

            <!-- Стиль и цвет бороды -->
            <div class="form-group">
                <label for="beard_style">Стиль бороды:</label>
                <select id="beard_style" name="beard_style" required>
                    <option value="">-- Выберите стиль --</option>
                    <option value="none" <?= isset($beard_style) && $beard_style === 'none' ? 'selected' : '' ?>>Без бороды</option>
                    <option value="stubble" <?= isset($beard_style) && $beard_style === 'stubble' ? 'selected' : '' ?>>Щетина</option>
                    <option value="short_beard" <?= isset($beard_style) && $beard_style === 'short_beard' ? 'selected' : '' ?>>Короткая борода</option>
                    <option value="long_beard" <?= isset($beard_style) && $beard_style === 'long_beard' ? 'selected' : '' ?>>Длинная борода</option>
                    <option value="goatee" <?= isset($beard_style) && $beard_style === 'goatee' ? 'selected' : '' ?>>Козлиная бородка</option>
                </select>
            </div>

            <div class="form-group">
                <label for="beard_color">Цвет бороды:</label>
                <select id="beard_color" name="beard_color" required>
                    <option value="">-- Выберите цвет --</option>
                    <option value="black" <?= isset($beard_color) && $beard_color === 'black' ? 'selected' : '' ?>>Чёрный</option>
                    <option value="brown" <?= isset($beard_color) && $beard_color === 'brown' ? 'selected' : '' ?>>Коричневый</option>
                    <option value="blonde" <?= isset($beard_color) && $beard_color === 'blonde' ? 'selected' : '' ?>>Блондин</option>
                    <option value="red" <?= isset($beard_color) && $beard_color === 'red' ? 'selected' : '' ?>>Рыжий</option>
                    <option value="gray" <?= isset($beard_color) && $beard_color === 'gray' ? 'selected' : '' ?>>Седой</option>
                </select>
            </div>

            <!-- Цвет кожи и глаз -->
            <div class="form-group">
                <label for="skin_color">Цвет кожи:</label>
                <select id="skin_color" name="skin_color" required>
                    <option value="">-- Выберите цвет --</option>
                    <option value="light" <?= isset($skin_color) && $skin_color === 'light' ? 'selected' : '' ?>>Светлый</option>
                    <option value="medium" <?= isset($skin_color) && $skin_color === 'medium' ? 'selected' : '' ?>>Средний</option>
                    <option value="olive" <?= isset($skin_color) && $skin_color === 'olive' ? 'selected' : '' ?>>Оливковый</option>
                    <option value="dark" <?= isset($skin_color) && $skin_color === 'dark' ? 'selected' : '' ?>>Тёмный</option>
                </select>
            </div>

            <div class="form-group">
                <label for="eye_color">Цвет глаз:</label>
                <select id="eye_color" name="eye_color" required>
                    <option value="">-- Выберите цвет --</option>
                    <option value="blue" <?= isset($eye_color) && $eye_color === 'blue' ? 'selected' : '' ?>>Голубые</option>
                    <option value="green" <?= isset($eye_color) && $eye_color === 'green' ? 'selected' : '' ?>>Зелёные</option>
                    <option value="brown" <?= isset($eye_color) && $eye_color === 'brown' ? 'selected' : '' ?>>Карие</option>
                    <option value="gray" <?= isset($eye_color) && $eye_color === 'gray' ? 'selected' : '' ?>>Серые</option>
                    <option value="hazel" <?= isset($eye_color) && $eye_color === 'hazel' ? 'selected' : '' ?>>Ореховые</option>
                </select>
            </div>

            <button type="submit" class="submit-btn">Создать персонажа</button>
        </form>

        <?php if (isset($character_created) && $character_created): ?>
            <div class="character-preview">
                <h2>Ваш персонаж:</h2>
                <div class="character-trait"><strong>Направление:</strong> 
                    <?php 
                    switch($direction) {
                        case 'python': echo 'Python разработчик'; break;
                        case 'csharp': echo 'C# разработчик'; break;
                        case 'cpp': echo 'C++ разработчик'; break;
                        case 'other': echo 'Другое направление'; break;
                    }
                    ?>
                </div>
                <div class="character-trait"><strong>Волосы:</strong> 
                    <?php 
                    switch($hair_style) {
                        case 'short': echo 'Короткие'; break;
                        case 'medium': echo 'Средние'; break;
                        case 'long': echo 'Длинные'; break;
                        case 'bald': echo 'Лысый'; break;
                    }
                    ?>, 
                    <?php 
                    switch($hair_color) {
                        case 'black': echo 'чёрные'; break;
                        case 'brown': echo 'коричневые'; break;
                        case 'blonde': echo 'белокурые'; break;
                        case 'red': echo 'рыжие'; break;
                        case 'gray': echo 'седые'; break;
                    }
                    ?>
                </div>
                <div class="character-trait"><strong>Борода:</strong> 
                    <?php 
                    switch($beard_style) {
                        case 'none': echo 'Отсутствует'; break;
                        case 'stubble': echo 'Щетина'; break;
                        case 'short_beard': echo 'Короткая борода'; break;
                        case 'long_beard': echo 'Длинная борода'; break;
                        case 'goatee': echo 'Козлиная бородка'; break;
                    }
                    ?>
                    <?php if ($beard_style !== 'none'): ?>, 
                    <?php 
                    switch($beard_color) {
                        case 'black': echo 'чёрная'; break;
                        case 'brown': echo 'коричневая'; break;
                        case 'blonde': echo 'белокурая'; break;
                        case 'red': echo 'рыжая'; break;
                        case 'gray': echo 'седая'; break;
                    }
                    ?>
                    <?php endif; ?>
                </div>
                <div class="character-trait"><strong>Кожа:</strong> 
                    <?php 
                    switch($skin_color) {
                        case 'light': echo 'Светлая'; break;
                        case 'medium': echo 'Средняя'; break;
                        case 'olive': echo 'Оливковая'; break;
                        case 'dark': echo 'Тёмная'; break;
                    }
                    ?>
                </div>
                <div class="character-trait"><strong>Глаза:</strong> 
                    <?php 
                    switch($eye_color) {
                        case 'blue': echo 'Голубые'; break;
                        case 'green': echo 'Зелёные'; break;
                        case 'brown': echo 'Карие'; break;
                        case 'gray': echo 'Серые'; break;
                        case 'hazel': echo 'Ореховые'; break;
                    }
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>