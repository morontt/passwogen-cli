# passwogen-cli

Консольный генератор и менеджер паролей, сделанный по мотивам другого [проекта](https://github.com/morontt/passwogen).

## Установка

Скачать phar-архив [отсюда](https://github.com/morontt/passwogen-cli/releases) или собрать его самостоятельно.

```bash
    ./install.sh

    # Далее можно скопировать получившийся файл в папку, откуда он будет
    # доступен для запуска, например:
    mv passwogen.sh ~/bin/passwogen
```

### Использование

```bash
    # создание пароля для ключа mail.ru
    passwogen generate mail.ru
    # или
    passwogen g mail.ru

    # обновление пароля по ключу mail.ru
    passwogen update mail.ru
    # или
    passwogen u mail.ru

    # посмотреть пароль для mail.ru
    passwogen show mail.ru
    # или
    passwogen s mail.ru

    # поиск пароля с mail в ключе
    passwogen find mail
    # или
    passwogen f mail

    # список просроченных паролей (не обновлялись более полугода)
    passwogen outdated
    # или
    passwogen o
```

#### Настройка

Конфигурационный файл *$HOME/.passwogen/config.json*. В нём можно настроить длину генерируемых паролей и расположение
зашифрованного файла с паролями.
