#!/bin/bash

COMPOSER_JSON="composer.json"
REPLACE_JSON="replace.json"

# Проверяем наличие файлов
if [[ ! -f "$REPLACE_JSON" ]]; then
    echo "Файл $REPLACE_JSON не найден."
    exit 1
fi

if [[ ! -f "$COMPOSER_JSON" ]]; then
    echo "Файл $COMPOSER_JSON не найден."
    exit 1
fi

# Создаем временный файл
TMP_COMPOSER=$(mktemp)

# Удаляем существующий replace, если есть
jq 'del(.replace)' "$COMPOSER_JSON" > "$TMP_COMPOSER" && mv "$TMP_COMPOSER" "$COMPOSER_JSON"

# Загружаем replace из файла и добавляем в composer.json
REPLACE_CONTENT=$(jq -c '.replace' "$REPLACE_JSON")
jq --argjson replace "$REPLACE_CONTENT" '. + {replace: $replace}' "$COMPOSER_JSON" > "$TMP_COMPOSER" && mv "$TMP_COMPOSER" "$COMPOSER_JSON"

echo "Блок \"replace\" успешно добавлен в $COMPOSER_JSON"