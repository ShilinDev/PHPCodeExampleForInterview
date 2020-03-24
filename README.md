**Пример PHP кода.** 

Реализация функционала взаимодейсвия между сервиса в рамках Laravel framework для "прогрева" кешей в системе с использованием брокера сообщений Nats.

Задача:

В SearchJob, которая запускается паралельно для каждого поиска в системе, получить результат работы поиска, отправить эти данные в брокер сообщений с учетом предварительного
приведению к формату согласованному для брокера.

В качестве библиотеки для работы с брокером используется https://github.com/vladitot/phpnats2