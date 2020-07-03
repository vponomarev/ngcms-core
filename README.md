ngcms-core
==========

[![Join the chat at https://gitter.im/vponomarev/ngcms-core](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/vponomarev/ngcms-core?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

Репозиторий файлов ядра NGCMS.
Не забывайте, что ядро не может работать самостоятельно без набора необходимых плагинов.

# Установка
1. Скачать содержимое данного репозитория в корневой каталог WEB сервера.
2. Скачать содержимое репозитория (полностью, либо выбранные плагины) https://github.com/vponomarev/ngcms-plugins в каталог engine/plugins/
3. Установить composer (если он ещё не установлен)
4. Выполнить установку зависимостей composer'а командой composer install
5. Открыть в WEB сервере ваш будущий сайт и следовать по указанным шагам.


# Автоматизированная установка в Docker контейнер
Для автоматизированной установки в Docker контейнер можно использовать скрипты из каталога _docker_build_scripts.
Последовательность установки:
1. Установить docker-compose на системе: `apt install docker-compose`
2. Скачать скрипты автоустановки в текущий каталог из каталога `/_docker-build-scripts/`
3. Запустить `docker-build-image.sh` для сборки image
4. Запустить `docker-start.sh` (или `docker-compose up`) для запуска контейнеров

Git версия NGCMS разворачивается в каталог ngcms/ и содержимое сохраняется при перезапуске контейнеров.

При необходимости обновления исходных кодов (пересборка контейнера с новой версией) необходимо запустить скрипт `docker-redeploy-with-config-preserve.sh` и перезапустить контейнеры.

Для сборки версии NGCMS из конкретного PULL Request'а вместо шага #3 необходимо запустить скрипт `docker-build-image-pull-request.sh` с передачей ему параметра окружения PULL_REQUEST=<ID Pull Request'а>.

Пример запуска: `PULL_REQUEST=57 ./docker-build-image-pull-request.sh`
