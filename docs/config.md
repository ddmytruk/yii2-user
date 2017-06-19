# Настройка модуля

Все доступные параметры конфигурации перечислены ниже со значениями по умолчанию.

---

### ddmytruk\user\abstracts\UserAbstract

Базовый класс пользователей с описанием возможных сценариев

### Константы

#### SIGN_UP_SCENARIO (Type: string, Default: 'singUp')

Сценарий для формы регистрации

---

#### SIGN_IN_SCENARIO (Type: string, Default: 'singIn')

Сценарий для формы входа

---



### Свойства

#### usernameRegexp (Type: string, Default: "/^[-a-zA-Z]+$/") 

Регулярное выражения для проверки валидности username

---

#### phoneRegexp (Type: string, Default: "/^(\d{12})$/") 

Регулярное выражения для проверки валидности phone

---

#### scenarioConfig (Type: array, Default: [])

Масив конфигурации сценариев. 
Используется для указания полей для регистрации или авторизации.

Для формы `ddmytruk\user\models\form\SignInForm` используется `$signUpScenarioConfig`

Для формы `ddmytruk\user\models\form\SignUpForm` используется `$signInScenarioConfig`

---

### Методы

####

#### signUpScenarioConfig (type: array, Default: )



---

