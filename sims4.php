<?php
session_start(); // Добавляем в самое начало

// Если есть данные в сессии, используем их для предзаполнения формы
if (isset($_SESSION['character_data'])) {
    $character_data = $_SESSION['character_data'];
    $programist = $character_data['Язык программирования'] ?? '';
    $hair_style = $character_data['Стиль волос'] ?? '';
    $hair_color = $character_data['Цвет волос'] ?? '';
    $beard_style = $character_data['Стиль бороды'] ?? '';
    $beard_color = $character_data['Цвет бороды'] ?? '';
    $skin_color = $character_data['Цвет кожи'] ?? '';
    $eyes_color = $character_data['Цвет глаз'] ?? '';
} else {
    // Инициализируем переменные, если нет данных в сессии
    $programist = '';
    $hair_style = '';
    $hair_color = '';
    $beard_style = '';
    $beard_color = '';
    $skin_color = '';
    $eyes_color = '';
}

// Если пришел POST-запрос, обновляем переменные
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $programist = $_POST['programist'] ?? '';
    $hair_style = $_POST['hair_style'] ?? '';
    $hair_color = $_POST['hair_color'] ?? '';
    $beard_style = $_POST['beard_style'] ?? '';
    $beard_color = $_POST['beard_color'] ?? '';
    $skin_color = $_POST['skin_color'] ?? '';
    $eyes_color = $_POST['eyes_color'] ?? '';
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #0e0e0eff;
        }
        .container {
            background: #08091fff;
            padding: 20px;
            border: 1px solid #ffd700;
            color: #e8e8e8ff;
            text-shadow: 0 0 10px #ffd700, 2px 2px 0 #000;
            border-radius: 10px;
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
            background: #08091fff;
            border: 1px solid #ffd700;
            color: #e8e8e8ff;
            text-shadow: 0 0 10px #ffd700, 2px 2px 0 #000;
            border-radius: 4px;
            cursor: pointer;
        }
        .submit-btn {
            padding: 10px 20px;
            background: #08091fff;
            border: 1px solid #ffd700;
            color: #e8e8e8ff;
            text-shadow: 0 0 10px #ffd700, 2px 2px 0 #000;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .submit-btn:hover {
            background: #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Редактор персонажа</h1>

        <form method="POST" action="index.php">
            <div class="form-group">
                <label for="programist">Язык программирования:</label>
                <select id="programist" name="programist" required>
                    <option>-- Выберите язык --</option>
                    <option value="python" <?= isset($programist) && $programist === 'python' ? 'selected' : '' ?>>Python</option>
                    <option value="c#" <?= isset($programist) && $programist === 'c#' ? 'selected' : '' ?>>C#</option>
                    <option value="c++" <?= isset($programist) && $programist === 'c++' ? 'selected' : '' ?>>C++</option>
                </select>
            </div>

            <div class="form-group">
                <label for="hair_style">Стиль волос:</label>
                <select id="hair_style" name="hair_style" required>
                    <option>-- Выберите стиль --</option>
                    <option value="короткие" <?= isset($hair_style) && $hair_style === 'short' ? 'selected' : '' ?>>Короткие</option>
                    <option value="средние" <?= isset($hair_style) && $hair_style === 'medium' ? 'selected' : '' ?>>Средние</option>
                    <option value="длинные" <?= isset($hair_style) && $hair_style === 'long' ? 'selected' : '' ?>>Длинные</option>
                    <option value="лысый" <?= isset($hair_style) && $hair_style === 'bald' ? 'selected' : '' ?>>Лысый</option>
                    <option value="ирокез" <?= isset($hair_style) && $hair_style === 'iroquois' ? 'selected' : '' ?>>Ирокез</option>
                </select>
            </div>
            <div class="form-group">
                <label for="hair_color">Цвет волос:</label>
                <select id="hair_color" name="hair_color" required>
                    <option>-- Выберите цвет --</option>
                    <option value="черные" <?= isset($hair_color) && $hair_color === 'black' ? 'selected' : '' ?>>Черный</option>
                    <option value="брюнет" <?= isset($hair_color) && $hair_color === 'brown' ? 'selected' : '' ?>>Брюнет</option>
                    <option value="блондин" <?= isset($hair_color) && $hair_color === 'blonde' ? 'selected' : '' ?>>Блондин</option>
                    <option value="рыжий" <?= isset($hair_color) && $hair_color === 'red' ? 'selected' : '' ?>>Рыжий</option>
                    <option value="седой" <?= isset($hair_color) && $hair_color === 'gray' ? 'selected' : '' ?>>Седой</option>
                    <option value="розовый" <?= isset($hair_color) && $hair_color === 'pink' ? 'selected' : '' ?>>Розовый</option>
                </select>
            </div>

            <div class="form-group">
                <label for="beard_style">Стиль бороды:</label>
                <select id="beard_style" name="beard_style" required>
                    <option>-- Выберите стиль --</option>
                    <option value="без бороды" <?= isset($beard_style) && $beard_style === 'beard_bald' ? 'selected' : '' ?>>Без бороды</option>
                    <option value="щетина" <?= isset($beard_style) && $beard_style === 'stubble' ? 'selected' : '' ?>>Щетина</option>
                    <option value="короткая борода" <?= isset($beard_style) && $beard_style === 'short_beard' ? 'selected' : '' ?>>Короткая борода</option>
                    <option value="длинная борода" <?= isset($beard_style) && $beard_style === 'long_beard' ? 'selected' : '' ?>>Длинная борода</option>
                    <option value="козлиная бородка" <?= isset($beard_style) && $beard_style === 'goatee' ? 'selected' : '' ?>>Козлиная бородка</option>
                </select>
            </div>
            <div class="form-group">
                <label for="beard_color">Цвет бороды:</label>
                <select id="beard_color" name="beard_color" required>
                    <option>-- Выберите цвет --</option>
                    <option value="черный" <?= isset($beard_color) && $beard_color === 'black' ? 'selected' : '' ?>>Чёрный</option>
                    <option value="коричневый" <?= isset($beard_color) && $beard_color === 'brown' ? 'selected' : '' ?>>Коричневый</option>
                    <option value="блондин" <?= isset($beard_color) && $beard_color === 'blonde' ? 'selected' : '' ?>>Блондин</option>
                    <option value="рыжий" <?= isset($beard_color) && $beard_color === 'red' ? 'selected' : '' ?>>Рыжий</option>
                    <option value="седой" <?= isset($beard_color) && $beard_color === 'gray' ? 'selected' : '' ?>>Седой</option>
                    <option value="розовый" <?= isset($beard_color) && $beard_color === 'pink' ? 'selected' : '' ?>>Розовый</option>
                </select>
            </div>

            <div class="form-group">
                <label for="skin_color">Цвет кожи:</label>
                <select id="skin_color" name="skin_color" required>
                    <option>-- Выберите цвет --</option>
                    <option value="сахарок" <?= isset($skin_color) && $skin_color === 'white' ? 'selected' : '' ?>>Сахарок</option>
                    <option value="негр" <?= isset($skin_color) && $skin_color === 'black' ? 'selected' : '' ?>>Негр</option>
                </select>
            </div>

            <div class="form-group">
                <label for="eyes_color">Цвет глаз:</label>
                <select id="eyes_color" name="eyes_color" required>
                    <option>-- Выберите цвет --</option>
                    <option value="голубые" <?= isset($eyes_color) && $eyes_color === 'blue' ? 'selected' : '' ?>>Голубые</option>
                    <option value="зеленые" <?= isset($eyes_color) && $eyes_color === 'green' ? 'selected' : '' ?>>Зелёные</option>
                    <option value="карие" <?= isset($eyes_color) && $eyes_color === 'brown' ? 'selected' : '' ?>>Карие</option>
                    <option value="красные" <?= isset($eyes_color) && $eyes_color === 'red' ? 'selected' : '' ?>>Красные</option>
                </select>
            </div>

            <button type="submit" class="submit-btn">Создать персонажа</button>
        </form>
    </div>
</body>
</html>