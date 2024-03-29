## Модуль интеграции с CMS Bitrix24 (облачная версия)
Данный модуль обеспечивает взаимодействие между интернет-магазином на базе Bitrix24 cloud и сервисом платежей [EPOS](https://e-pos.by)

Важно: модуль добавляет новую платежную систему для оплаты заказов, созданных клиентами в интернет-магазине (не в CRM)

## Инструкция по установке:
1. Зайдите в административную часть Вашего портала
1. Перейдите в меню "Приложения > Маркет"
1. В строке поиска введите "epos"
1. В результатах поиска выберите приложение "EPOS. QR платежи (Беларусь)"
1. Выполните установку

## Инструкция по настройке
1. Перейдите в меню "Сайты и магазины" > "Платежи и доставки" > "Платежные системы"
1. Перейдите к редактированию новой платежной системы "EPOS (оплата через ЕРИП)"
1. В секции _"configform_common"_ укажите обязательные параметры
    * EPOS процессинг - выбор организации, выполняющей интеграцию с EPOS
    * Идентификатор клиента – Ваш персональный логин для работы с сервисом EPOS
    * Секрет – Ваш секретный ключ для работы с сервисом EPOS
    * Код ПУ – код поставщика услуги в системе EPOS
    * Код услуги EPOS – код услуги у поставщика услуг в системе EPOS (один ПУ может предоставлять несколько разных услуг)
    * Код торговой точки – код торговой точки ПУ (у одного ПУ может быть несколько торговых точек)    
    * Debug mode - запись и отображение дополнительных сообщений при работе модуля
    * Sandbox - перевод модуля в тестовый режим работы. В этом режиме счета выставляются в тестовую систему
    * Срок действия счета - как долго счет, будет доступен в ЕРИП для оплаты    
    * Статус при выставлении счета  - какой статус выставить заказу при успешном выставлении счета в ЕРИП (идентификатор существующего статуса)
    * Статус при успешной оплате счета - какой статус выставить заказу при успешной оплате выставленного счета (идентификатор существующего статуса)
    * Статус при отмене оплаты счета - какой статус выставить заказу при отмене оплаты счета (идентификатор существующего статуса)
    * Статус при ошибке оплаты счета - какой статус выставить заказу при ошибке выставленния счета (идентификатор существующего статуса)
    * Секция "Инструкция" - если включена, то на итоговом экране клиенту будет доступна пошаговая инструкция по оплате счета в ЕРИП
    * Секция QR-code - если включена, то на итоговом экране клиенту будет доступна оплата счета по QR-коду
    * Секция Webpay - если включена, то на итоговом экране клиенту отобразится кнопка для оплаты счета картой (переход на Webpay)
    * Текст успешного выставления счета - текст, отображаемый кленту после успешного выставления счета. Может содержать html. В тексте допустимо ссылаться на переменные @order_id, @order_number, @order_total, @order_currency, @order_fullname, @order_phone, @order_address
1. Установите у системы статус "Активность"
1. Сохраните изменения

### Тестовые данные
Для настройки оплаты в тестовом режиме:
 * воспользуйтесь данными для подключения к тестовой системе, полученными при регистрации в EPOS
 * включите в настройках модуля тестовый режим 



