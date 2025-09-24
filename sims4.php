<?php
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
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 20px;
            border: 1px solid #ddd;
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
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
        }
        .submit-btn {
            padding: 10px 20px;
            border: 1px solid #ddd;
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

        <form method="POST">
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
                    <option value="short" <?= isset($hair_style) && $hair_style === 'short' ? 'selected' : '' ?>>Короткие</option>
                    <option value="medium" <?= isset($hair_style) && $hair_style === 'medium' ? 'selected' : '' ?>>Средние</option>
                    <option value="long" <?= isset($hair_style) && $hair_style === 'long' ? 'selected' : '' ?>>Длинные</option>
                    <option value="bald" <?= isset($hair_style) && $hair_style === 'bald' ? 'selected' : '' ?>>Лысый</option>
                    <option value="iroquois" <?= isset($hair_style) && $hair_style === 'iroquois' ? 'selected' : '' ?>>Ирокез</option>
                </select>
            </div>
            <div class="form-group">
                <label for="hair_color">Цвет волос:</label>
                <select id="hair_color" name="hair_color" required>
                    <option>-- Выберите цвет --</option>
                    <option value="black" <?= isset($hair_color) && $hair_color === 'black' ? 'selected' : '' ?>>Черный</option>
                    <option value="brown" <?= isset($hair_color) && $hair_color === 'brown' ? 'selected' : '' ?>>Брюнет</option>
                    <option value="blonde" <?= isset($hair_color) && $hair_color === 'blonde' ? 'selected' : '' ?>>Блондин</option>
                    <option value="red" <?= isset($hair_color) && $hair_color === 'red' ? 'selected' : '' ?>>Рыжий</option>
                    <option value="gray" <?= isset($hair_color) && $hair_color === 'gray' ? 'selected' : '' ?>>Седой</option>
                    <option value="pink" <?= isset($hair_color) && $hair_color === 'pink' ? 'selected' : '' ?>>Розовый</option>
                </select>
            </div>

            <div class="form-group">
                <label for="beard_style">Стиль бороды:</label>
                <select id="beard_style" name="beard_style" required>
                    <option>-- Выберите стиль --</option>
                    <option value="beard_bald" <?= isset($beard_style) && $beard_style === 'beard_bald' ? 'selected' : '' ?>>Без бороды</option>
                    <option value="stubble" <?= isset($beard_style) && $beard_style === 'stubble' ? 'selected' : '' ?>>Щетина</option>
                    <option value="short_beard" <?= isset($beard_style) && $beard_style === 'short_beard' ? 'selected' : '' ?>>Короткая борода</option>
                    <option value="long_beard" <?= isset($beard_style) && $beard_style === 'long_beard' ? 'selected' : '' ?>>Длинная борода</option>
                    <option value="goatee" <?= isset($beard_style) && $beard_style === 'goatee' ? 'selected' : '' ?>>Козлиная бородка</option>
                </select>
            </div>
            <div class="form-group">
                <label for="beard_color">Цвет бороды:</label>
                <select id="beard_color" name="beard_color" required>
                    <option>-- Выберите цвет --</option>
                    <option value="black" <?= isset($beard_color) && $beard_color === 'black' ? 'selected' : '' ?>>Чёрный</option>
                    <option value="brown" <?= isset($beard_color) && $beard_color === 'brown' ? 'selected' : '' ?>>Коричневый</option>
                    <option value="blonde" <?= isset($beard_color) && $beard_color === 'blonde' ? 'selected' : '' ?>>Блондин</option>
                    <option value="red" <?= isset($beard_color) && $beard_color === 'red' ? 'selected' : '' ?>>Рыжий</option>
                    <option value="gray" <?= isset($beard_color) && $beard_color === 'gray' ? 'selected' : '' ?>>Седой</option>
                    <option value="pink" <?= isset($hair_color) && $hair_color === 'pink' ? 'selected' : '' ?>>Розовый</option>
                </select>
            </div>

            <div class="form-group">
                <label for="skin_color">Цвет кожи:</label>
                <select id="skin_color" name="skin_color" required>
                    <option>-- Выберите цвет --</option>
                    <option value="white" <?= isset($skin_color) && $skin_color === 'white' ? 'selected' : '' ?>>Сахарок</option>
                    <option value="black" <?= isset($skin_color) && $skin_color === 'black' ? 'selected' : '' ?>>Негр</option>
                </select>
            </div>

            <div class="form-group">
                <label for="eyes_color">Цвет глаз:</label>
                <select id="eyes_color" name="eyes_color" required>
                    <option>-- Выберите цвет --</option>
                    <option value="blue" <?= isset($eyes_color) && $eyes_color === 'blue' ? 'selected' : '' ?>>Голубые</option>
                    <option value="green" <?= isset($eyes_color) && $eyes_color === 'green' ? 'selected' : '' ?>>Зелёные</option>
                    <option value="brown" <?= isset($eyes_color) && $eyes_color === 'brown' ? 'selected' : '' ?>>Карие</option>
                    <option value="red" <?= isset($eyes_color) && $eyes_color === 'red' ? 'selected' : '' ?>>Красные</option>
                </select>
            </div>

            <button type="submit" class="submit-btn">Создать персонажа</button>
        </form>
    </div>
</body>
</html>