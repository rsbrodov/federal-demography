<p align="center">
    <p>
        <img src="https://www.niig.su/templates/niig_tpl/images/logo.png" height="100px">
    </p>
    <h1 align="center">Федеральный проект "Демография"</h1>
    <br>
</p>
На GitHub представлена только малая часть исходного кода, чтобы не нарушать авторские права.
<br>
<br>
Проект предназначен для мониторинга здоровья детей, поставки продуктов в образовательные учреждения, а также для создания меню. Разработан целый модуль с актуальными нормативами, который позволяет получить заключение по меню, его химический анализ и значения БЖУ. Надзорные органы имееют доступ к отчетности и могут мониторировать ситуацию по своей области.
Доступ открыт только авторизированным пользователям [Ссылка](https://demography.site/)
Плейлист по разработтанной программе для ознакомления в YouTube [Ссылка](https://www.youtube.com/watch?v=JkNNbeFgAug&list=PLgTNF8hfADHk5D_ouj2JvaSspTyoSUzTE&index=9&ab_channel=%D0%A4%D0%91%D0%A3%D0%9D%D0%9D%D0%9E%D0%92%D0%9E%D0%A1%D0%98%D0%91%D0%98%D0%A0%D0%A1%D0%9A%D0%98%D0%99%D0%9D%D0%98%D0%98%D0%93%D0%98%D0%93%D0%98%D0%95%D0%9D%D0%AB%D0%A0%D0%9E%D0%A1%D0%9F%D0%9E%D0%A2%D0%A0%D0%95%D0%91%D0%9D%D0%90%D0%94%D0%97%D0%9E%D0%A0%D0%90/)

ИНСТРУМЕНТЫ И ТЕХНОЛОГИИ:
-------------------
1.Yii2;<br>
2.PHP 7.2;<br>
3.JavaScript;<br>
4.Jquery;<br>
5.Bootstrap 4;<br>
6.Chart.js;<br>

СТРУКТУРА ПРОЕКТА:
-------------------
1. ТЕХНИЧЕСКАЯ ЧАСТЬ:<br>
Контроллеров:73;<br>
Вью-директорий:75;<br>
Моделей:148, из них Search-моделей - 6.<br>
2. БАЗА ДАННЫХ:<br>
Количество таблиц: 113<br>
Количество ролей(уровней доступа): 17<br>

![image](https://user-images.githubusercontent.com/55738777/162392996-fe6bf00c-4052-4bb4-9597-aeeb150bd243.png)

3. СКРИНЫ:<br>

![screen1](https://user-images.githubusercontent.com/55738777/162129459-3122a511-ec79-4a4b-8568-35776b59e359.PNG)

![image](https://user-images.githubusercontent.com/55738777/162399536-f5557414-e732-4d35-ad7e-3c987b6d5a40.png)

![image](https://user-images.githubusercontent.com/55738777/162399749-6c1e5f49-5d6e-496a-869a-9b2b7c95a7f2.png)

![image](https://user-images.githubusercontent.com/55738777/162400048-be04bfea-c2b3-49da-86ab-abe5c6c0096f.png)

![image](https://user-images.githubusercontent.com/55738777/162400517-59555b97-76c2-42c4-b2d1-1965a8cdd675.png)

![image](https://user-images.githubusercontent.com/55738777/162403557-b7ab3fdb-78ce-4d94-bdcd-2ef5f51ba0b8.png)

![image](https://user-images.githubusercontent.com/55738777/162400823-3c4b6659-0b9b-41ea-80de-824a6e1bc30e.png)

![image](https://user-images.githubusercontent.com/55738777/162401591-a16e7673-7536-4c6b-8703-0414dcd2d117.png)

![image](https://user-images.githubusercontent.com/55738777/162402167-88c90259-29ae-42fc-b668-a317e0a5e288.png)

![image](https://user-images.githubusercontent.com/55738777/162402613-04c67f4f-e9e8-4d7f-822e-279d6706f1a5.png)

![image](https://user-images.githubusercontent.com/55738777/162403295-3ad4a9ce-770a-4e74-9fcf-38cd2f14381a.png)

![image](https://user-images.githubusercontent.com/55738777/162408640-cc040d8a-a149-4a06-8458-02e94785e322.png)


```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    tests/               contains tests for common classes    
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for backend application    
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
```
