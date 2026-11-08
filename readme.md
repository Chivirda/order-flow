# Order Flow

Laravel-приложение. Docker Compose запускает Nginx, PHP-FPM 8.4, MySQL 8.4 и Redis 7.4.

## Первый запуск

Нужны Docker с Compose и Node.js с npm для сборки фронтенда (версия, совместимая с Vite 8).

```sh
cp .env.example .env
```

Укажите в `.env` параметры локального окружения, задав собственные пароли:

```dotenv
APP_URL=http://localhost:8080
APP_TIMEZONE=Europe/Moscow
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=order_flow
DB_USERNAME=order_flow
DB_PASSWORD=replace_with_app_password
DB_ROOT_PASSWORD=replace_with_root_password
REDIS_HOST=redis
```

Соберите PHP-образ, установите зависимости и запустите сервисы:

```sh
docker compose build php
docker compose run --rm --no-deps php composer install
docker compose run --rm --no-deps php php artisan key:generate
npm install
npm run build
docker compose up -d
docker compose exec php php artisan migrate
```

Откройте http://localhost:8080. MySQL доступен с хоста по адресу `127.0.0.1:3307`, внутри сети Compose — `mysql:3306`.

Если нужны фоновые задания, запустите обработчик в отдельном терминале:

```sh
docker compose exec php php artisan queue:work
```

## Таймзона

Для PHP и Laravel в Compose задана `Europe/Moscow`; PHP также читает `docker/php/timezone.ini`. MySQL использует `TZ=Europe/Moscow` и явную настройку SQL-таймзоны `+03:00` (московское время), которая не зависит от загрузки таблиц часовых поясов.

Чтобы применить изменения к существующим контейнерам:

```sh
docker compose up -d --force-recreate php mysql nginx
docker compose exec php php artisan config:clear
```

Пересборка образов для этих изменений не нужна: достаточно пересоздать контейнеры. Обычный `docker compose restart` не применяет новые переменные окружения. При изменении Dockerfile образ PHP нужно пересобрать: `docker compose up -d --build php`.

Изменение таймзоны не конвертирует ранее сохранённые значения `DATETIME`. Если запущены отдельные обработчики очередей, перезапустите их после изменения настроек.

## Полезные команды

```sh
docker compose ps
docker compose logs -f
docker compose exec php php artisan test
docker compose down
```

Данные MySQL и Redis сохраняются в именованных томах после `docker compose down`. Флаг `-v` удаляет эти тома вместе с данными.
