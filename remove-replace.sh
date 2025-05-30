#!/bin/bash

COMPOSER_JSON="composer.json"

# Проверяем, существует ли composer.json
if [[ ! -f "$COMPOSER_JSON" ]]; then
    echo "Файл $COMPOSER_JSON не найден."
    exit 1
fi

# Создаём временный файл
TMP_COMPOSER=$(mktemp)

# Удаляем блок "replace", если он есть
jq 'del(.replace)' "$COMPOSER_JSON" > "$TMP_COMPOSER" && mv "$TMP_COMPOSER" "$COMPOSER_JSON"

echo "Блок \"replace\" успешно удален из $COMPOSER_JSON"