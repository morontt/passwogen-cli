# passwogen-cli

Консольный генератор и менеджер паролей, сделанный по мотивам другого [проекта](https://github.com/morontt/passwogen).

## Установка

Скачать phar-архив [отсюда](https://github.com/morontt/passwogen-cli/releases) или собрать его самостоятельно.

```bash
    ./install.sh

    # Далее можно скопировать получившийся файл в папку, откуда он будет
    # доступен для запуска, например:
    mv passwogen.phar ~/bin/passwogen
```

Так же можно установить его глобально через [composer](https://getcomposer.org):

```bash
    composer global require morontt/passwogen-cli
```

Для копирования пароля в буфер обмена в системе **Linux** необходимо установить *xclip*, если он ещё не установлен.

```bash
    # Debian, Ubuntu и другие производные:
    sudo apt-get install xclip
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
    
    # при создании и обновлении пароль можно указать явно, через опцию -p
    # или --password

    # поиск пароля с mail в ключе
    # если возвращается одна запись, то пароль попадает в буфер обмена
    passwogen find mail
    # или
    passwogen f mail

    # удаление пароля для ключа example.org
    passwogen delete example.org
    # или
    passwogen d example.org

    # список просроченных паролей (не обновлялись более полугода)
    passwogen outdated
    # или
    passwogen o
```

#### Настройка

Конфигурационный файл *$HOME/.passwogen/config.json*. В нём можно настроить длину генерируемых паролей и расположение
зашифрованного файла с паролями.
